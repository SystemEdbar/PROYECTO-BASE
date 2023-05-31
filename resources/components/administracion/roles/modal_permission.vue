<template>
    <b-container fluid class="bg-white text-dark">
        <template>
            <b-container class="auto-scroll">
                <div v-for="option in namePermission" class="my-2">
                    <b-card bg-variant="light" :header="option.resource" class="text-center" no-body>
                        <b-card-text>
                            <div class="row ml-3">
                                <b-form-checkbox v-model="option.show" v-on:input='updateRole("show "+option.name)' class="mx-2" switch>
                                    Ver
                                </b-form-checkbox>
                                <b-form-checkbox v-model="option.create" v-on:input='updateRole("create "+option.name)' class="mx-2" switch>
                                    Crear
                                </b-form-checkbox>
                                <b-form-checkbox v-model="option.update" v-on:input='updateRole("update "+option.name)' class="mx-2" switch>
                                    Editar
                                </b-form-checkbox>
                                <b-form-checkbox v-model="option.delete" v-on:input='updateRole("delete "+option.name)' class="mx-2" switch>
                                    Eliminar
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
    props: ['permissionModal'],
    name: "modal_permission",
    data() {
        return {
            data: {
                name: null,
            },
            namePermission: {},
            rolePermission: null,
            get:{
                namePermission: [],
                rolePermission: [],
                linkPermission: []
            },
        }
    },
    mounted() {
        this.getNamePermission()
        this.getRolePermission()
    },
    methods: {
        updateRole(role){
            this.data.name = role.toLowerCase()
            let url =this.permissionModal.url+(this.permissionModal.edit?'/'+this.permissionModal.roleId:'')
            axios.post(url, this.data).then((res) => {
                ToastAlert(res.data.status, res.data.message, 1500)
                this.$emit('updateRoles')
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
            this.$root.$emit('bv::toggle::modal', this.permissionModal.id)
        },

        getNamePermission(){
            this.namePermission = {}
            for (let name in this.permissionModal.permissions){
                let resourceName = this.permissionModal.permissions[name].name.split('/',[2])[1].toUpperCase()
                let resourceName1 = this.permissionModal.permissions[name].name.split(' ',[2])[1]
                this.get.namePermission.push(resourceName)
                this.get.linkPermission.push(resourceName1)
            }
            this.get.namePermission = this.get.namePermission.filter((item,index)=>{
                return this.get.namePermission.indexOf(item) === index;
            })
            this.get.linkPermission = this.get.linkPermission.filter((item,index)=>{
                return this.get.linkPermission.indexOf(item) === index;
            })
            console.log(this.get.linkPermission)
            for (let name in this.get.namePermission){
                this.namePermission[name] = {resource: this.get.namePermission[name], name: this.get.linkPermission[name]
                    ,show: false, false: false, update: false, delete: false}
            }
            console.log(this.namePermission)
        },
        getRolePermission(){
            this.rolePermission = {}
            for (let name in this.permissionModal.rolePermission){
                let resourceName = this.permissionModal.rolePermission[name].name.split(' ')[0].toUpperCase()
                let resourceName1 = this.permissionModal.rolePermission[name].name.split('/')[1].toUpperCase()
                this.get.rolePermission.push(resourceName+' '+resourceName1)
            }
            for (let role in this.namePermission){
                let resource = this.namePermission[role].resource
                for (let name in this.get.rolePermission){
                    if(this.get.rolePermission[name].includes(resource)){
                        if (this.get.rolePermission[name].includes('SHOW')) {
                            this.namePermission[role].show=true
                        } else if (this.get.rolePermission[name].includes('CREATE')) {
                            this.namePermission[role].create=true
                        } else if (this.get.rolePermission[name].includes('UPDATE')) {
                            this.namePermission[role].update=true
                        } else if (this.get.rolePermission[name].includes('DELETE')) {
                            this.namePermission[role].delete=true
                        }
                    }
                }

            }
        }
    }
}
</script>

<style>
.auto-scroll {
    position:relative;
    height:550px;
    overflow-y:scroll;
}
</style>
