<template>
    <b-container fluid class="bg-white text-dark rounded p-3 shadow container-component-md">
        <h4 class="text-center">Roles y Permisos
            <i class="fas fa-plus text-success" @click="create()" v-b-tooltip.hover title="Crear nuevo role" role="button" aria-hidden="true"></i></h4>
        <b-container class="col-12 d-flex justify-content-center">
            <b-col lg="6" class="my-1">
                <b-form-group>
                    <b-input-group size="sm">
                        <b-form-input
                            id="filter-input"
                            v-model="filter"
                            type="search"
                            placeholder="Escribe para buscar"
                        ></b-form-input>
                        <b-input-group-append>
                            <b-button :disabled="!filter" @click="filter = ''">Limpiar</b-button>
                        </b-input-group-append>
                    </b-input-group>
                </b-form-group>
            </b-col>
        </b-container>
        <div class="table-responsive-sm table-component">
            <b-table
                :items="items"
                :fields="fields"
                :current-page="currentPage"
                :per-page="perPage"
                :filter="filter"
                :filter-included-fields="filterOn"
                :sort-by.sync="sortBy"
                :sort-desc.sync="sortDesc"
                :sort-direction="sortDirection"
                stacked="xl"
                empty-text="Sin registros que mostrar"
                show-empty
                small
                :thead-class="'bg-thead'"
                @filtered="onFiltered">
                <template #cell(actions)="row">
                    <div v-if="row.item.name != 'Super Administrador' && row.item.name != 'Administrador'">
                        <i class="fas fa-user-tag text-primary" @click="updatePermissions(row.item.id, row.item.permissions)"
                           title="Asignar Permisos" role="button" aria-hidden="true"></i>
                        <i class="fas fa-trash text-danger" @click="deleteRole(row.item.id)"
                           title="Eliminar Roles" role="button" aria-hidden="true"></i>
                    </div>
                </template>
                <template #cell(id)="row">
                    {{ row.index + 1 }}
                </template>
            </b-table>
        </div>
        <b-row>
            <b-col sm="2" md="4" class="my-1">
                <b-form-group
                    label="Por pagina"
                    label-for="per-page-select"
                    label-cols-sm="6"
                    label-cols-md="4"
                    label-cols-lg="3"
                    label-align-sm="center"
                    label-size="sm"
                    class="mb-0">
                    <b-form-select
                        id="per-page-select"
                        v-model="perPage"
                        :options="pageOptions"
                        size="sm"
                    ></b-form-select>
                </b-form-group>
            </b-col>

            <b-col sm="7" md="6" class="my-1">
                <b-pagination
                    v-model="currentPage"
                    :total-rows="totalRows"
                    :per-page="perPage"
                    align="fill"
                    size="sm"
                    :class="'bg-dark'"
                ></b-pagination>
            </b-col>
        </b-row>
        <b-modal :id="roleModal.id" centered size="l" content-class="text-dark"  :hide-footer="true">
            <div slot="modal-title">
                <h4 class="font-weight-bold">{{ roleModal.title }}</h4>
            </div>
            <modal_detail :roleModal="roleModal" @updateRoles="getRoles()"></modal_detail>
        </b-modal>
        <b-modal :id="permissionModal.id" centered size="l" content-class="text-dark" :hide-footer="true">
            <div slot="modal-title">
                <h4 class="font-weight-bold">{{ permissionModal.title }}</h4>
            </div>
            <modal_permission :permissionModal="permissionModal" @updateRoles="getRoles()"></modal_permission>
        </b-modal>
    </b-container>
</template>

<script>


import modal_detail from "./modal_detail";
import modal_permission from "./modal_permission";
export default {
    name: "user-index",
    props: ['role', 'permission', 'url'],
    components: {
        modal_detail,
        modal_permission,
    },
    data() {
        return {
            items: this.role,
            fields: [
                {key: 'id', label: 'Codigo', sortable: true, class: 'text-center'},
                {key: 'name', label: 'Nombre', sortable: true, class: 'text-center'},
                {key: 'actions', label: 'Acciones', class: 'text-center'}
            ],
            totalRows: 1,
            currentPage: 1,
            perPage: 15,
            pageOptions: [5, 10, 15, {value: 100, text: "Ver todo"}],
            sortBy: '',
            sortDesc: false,
            sortDirection: 'asc',
            filter: null,
            filterOn: [],
            roleModal: {
                id: 'id_modal_role',
                title: 'Crear Nuevo Role',
                edit: false,
                url: this.url+'/save'
            },
            permissionModal:{
                id: 'id_modal_permisson',
                title: 'Asignar Permisos',
                edit: true,
                roleId: null,
                rolePermission: [],
                permissions: this.permission,
                url: this.url+'/permisos/update'
            }
        }
    },
    computed: {
        sortOptions() {
            return this.fields
                .filter(f => f.sortable)
                .map(f => {
                    return {text: f.label, value: f.key}
                })
        }
    },
    mounted() {
        if(this.items!=null){this.totalRows = this.items.length}
    },
    methods: {
        onFiltered(filteredItems) {
            this.totalRows = filteredItems.length
            this.currentPage = 1
        },
        create() {
            this.$root.$emit('bv::toggle::modal', this.roleModal.id)
        },
        updatePermissions(id, item) {
            this.getPermissions()
            this.permissionModal.roleId = id
            this.permissionModal.rolePermission = item
            this.$root.$emit('bv::toggle::modal', this.permissionModal.id)
        },
        getRoles() {
            axios.get(this.url).then(res => {
                this.items = res.data
            })
            if(this.items!=null){this.totalRows = this.items.length}
        },
        getPermissions() {
            axios.get(this.url+"/permisos").then(res => {
                this.permissons = res.data
            })
        },
        deleteRole(id) {
            let url = this.url + "/delete/" + id
            Swal.fire({
                icon: 'question',
                title: '¿Estas seguro que deseas eliminar este Role?',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "Confirmar",
                reverseButtons: true,
            }).then(result => {
                if (result.value) {
                    axios.delete(url).then((res) => {
                        SwalAlert(res.data.status, res.data.message, 6000)
                        this.getRoles()
                    }).catch(
                        function (error) {
                            if (error.response.data.status) {
                                SwalAlert(error.response.data.status, error.response.data.message, 6000)
                            }
                        })
                }

            })
        },
    }
}
</script>

<style>
.page-item.active .page-link {
    background-color: #2d4b5b;
    border-color: #d2d4d5;
}
</style>



