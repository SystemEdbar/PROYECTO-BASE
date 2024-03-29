<template>
    <b-container fluid class="bg-white text-dark rounded p-3 shadow container-component">
        <h4 class="text-center">Usuarios
            <i class="fas fa-plus text-success" @click="create()" v-b-tooltip.hover title="Crear nuevo usuario" role="button" aria-hidden="true"></i></h4>
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

        <div class="table-responsive table-component">
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
                stacked="md"
                empty-text="Sin registros que mostrar"
                show-empty
                small
                :thead-class="'bg-thead'"
                @filtered="onFiltered">
                <template #cell(status)="row">
                    <div v-if="row.item.status === 0" class="table-danger">
                        SUSPENDIDO
                    </div>
                    <div v-else class="table-success">
                        DISPONIBLE
                    </div>
                </template>
                <template v-slot:cell(roles)="data">
                    <li v-for="option in data.item.roles">
                        {{ option.name }}
                    </li>
                </template>
                <template #cell(actions)="row">
                    <div v-if="row.item.roles.some(roles => roles.name === 'Super Administrador')">
                    </div>
                    <div v-else>
                        <i class="fas fa-edit text-success" @click="edit(row.item)"
                           title="Editar usuario" role="button" aria-hidden="true"></i>
                        <i class="fas fa-user-tag text-primary" @click="updateRole(row.item.id, row.item.roles)"
                           title="Asignar Roles" role="button" aria-hidden="true"></i>
                        <i class="fas fa-user-lock text-secundary" @click="suspendedUser(row.item.id)"
                           title="Habilitar o Deshabilitar" role="button" aria-hidden="true"></i>
                        <i class="fas fa-trash text-danger" @click="deleteUser(row.item.id)"
                           title="Eliminar usuario" role="button" aria-hidden="true"></i>
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
        <b-modal :id="userModal.id" centered size="xl" content-class="text-dark"  :hide-footer="true">
            <div slot="modal-title">
                <h4 class="font-weight-bold">{{ userModal.title }}</h4>
            </div>
            <modal_detail :userModal="userModal" @updateUsers="getUsers()"></modal_detail>
        </b-modal>
        <b-modal :id="roleModal.id" centered size="l" content-class="text-dark"  :hide-footer="true">
            <div slot="modal-title">
                <h4 class="font-weight-bold">{{ roleModal.title }}</h4>
            </div>
            <modal_role :roleModal="roleModal" @updateUsers="getUsers()"></modal_role>
        </b-modal>
    </b-container>
</template>

<script>


import modal_detail from "./modal_detail";
import modal_role from "./modal_role";
export default {
    name: "user-index",
    props: ['user', 'role', 'url'],
    components: {
        modal_detail,
        modal_role
    },
    data() {
        return {
            items: this.user,
            roles: this.role,
            fields: [
                {key: 'id', label: 'Codigo', sortable: true, class: 'text-center'},
                {key: 'name', label: 'Nombre', sortable: true, class: 'text-center'},
                {key: 'lastname', label: 'Apellido', sortable: true, class: 'text-center'},
                {key: 'email', label: 'Correo', sortable: true, class: 'text-center'},
                {key: 'roles', label: 'Roles', sortable: true, class: 'text-center'},
                {key: 'status', label: 'Estado', sortable: true, class: 'text-center'},
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
            userModal: {
                id: 'id_modal_user',
                title: '',
                edit: false,
                user: [],
                url:null,
            },
            roleModal:{
                id: 'id_modal_role',
                title: 'Asignar Roles',
                edit: true,
                userId: null,
                userRole: [],
                roles: [],
                url: this.url+'/roles/update'
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
        edit(item) {
            this.userModal.title='Editar Usuario'
            this.userModal.edit =true
            this.userModal.url =this.url+'/update'
            const {id, name, lastname, email, password} = item
            this.userModal.user = {id, name, lastname, email, password}
            this.$root.$emit('bv::toggle::modal', this.userModal.id)
        },
        create() {
            this.userModal.title='Crear Usuario'
            this.userModal.edit =false
            this.userModal.user =null
            this.userModal.url =this.url+'/save'
            this.$root.$emit('bv::toggle::modal', this.userModal.id)
        },
        updateRole(id, item) {
            this.getRoles()
            this.roleModal.userId = id
            this.roleModal.userRole = item
            this.roleModal.roles = this.roles
            this.$root.$emit('bv::toggle::modal', this.roleModal.id)
        },
        getUsers() {
            axios.get(this.url).then(res => {
                this.items = res.data
            })
            this.totalRows = this.items.length
        },
        getRoles() {
            axios.get(this.url+"/roles").then(res => {
                this.roles = res.data
            })
        },
        suspendedUser(id){
            let url = this.url + "/suspended/" + id
            Swal.fire({
                icon: 'question',
                title: '¿Esta opción Habilita/Deshabilita este usuario?',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "Confirmar",
                reverseButtons: true,
            }).then(result => {
                if (result.value) {
                    axios.delete(url).then((res) => {
                        SwalAlert(res.data.status, res.data.message, 6000)
                        this.getUsers()
                    }).catch(
                        function (error) {
                            if (error.response.data.status) {
                                SwalAlert(error.response.data.status, error.response.data.message, 6000)
                            }
                        })
                }

            })
        },
        deleteUser(id) {
            let url = this.url + "/delete/" + id
            Swal.fire({
                icon: 'question',
                title: '¿Estas seguro que deseas eliminar este usuario?',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "Confirmar",
                reverseButtons: true,
            }).then(result => {
                if (result.value) {
                    axios.delete(url).then((res) => {
                        SwalAlert(res.data.status, res.data.message, 6000)
                        this.getUsers()
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



