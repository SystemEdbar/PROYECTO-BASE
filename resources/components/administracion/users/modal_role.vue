<template>
    <b-container fluid class="bg-white text-dark">
        <template>
            <b-container class="auto-scroll">
                <div v-for="option in nameRole" class="my-2">
                    <div v-for="option2 in roles" style="display: none">
                        <div v-if="option.name === option2.name" v-model="option.id=true"></div>
                    </div>
                    <b-card :header="option.name" class="text-center" no-body bg-variant="light">
                        <b-card-text>
                            <div class="row ml-3">
                                <b-form-checkbox v-model="option.id" v-on:input='updateRole(option.name)' switch>
                                    Selecciona para Asignar o Desasignar un Role
                                </b-form-checkbox>
                            </div>
                        </b-card-text>
                    </b-card>
                </div>
            </b-container>
        </template>
        <div class="col-lg">
            <b-button class="mt-3" variant="outline-danger" block @click="hideModal">Cerrar</b-button>
        </div>
    </b-container>
</template>
<script>
export default {
    props: ['roleModal'],
    name: "modal_role",
    data() {
        return {
            data: {
                name: null,
            },
            nameRole: null,
            roles: null,
        }
    },
    mounted() {
        this.roles = this.roleModal.userRole
        this.nameRole = this.roleModal.roles
    },
    methods: {
        updateRole(role){
            this.data.name = role
            let url =this.roleModal.url+(this.roleModal.edit?'/'+this.roleModal.userId:'')
            axios.post(url, this.data).then((res) => {
                ToastAlert(res.data.status, res.data.message, 1500, 'succes')
                this.$emit('updateUsers')
            }).catch(function (error) {
                if (error.response.data.status) {
                    ToastAlert(error.response.data.status, error.response.data.message, 2000)
                } else {
                    Swal.fire("Algo salio mal", `${Object.values(error.response.data.errors)}`, "error");
                }
            })
        },
        hideModal(){
            this.$emit('updateRoles')
            this.$root.$emit('bv::toggle::modal', this.roleModal.id)
        },
    }
}
</script>

<style>
</style>
