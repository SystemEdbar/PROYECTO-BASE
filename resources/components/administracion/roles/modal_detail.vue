<template>
    <b-container fluid class="bg-white text-dark">
        <ValidationObserver v-slot="{ handleSubmit, reset }">
            <form @submit.prevent="handleSubmit(onSubmit)" @reset.prevent="reset" id="id_formulario">
                <div class="row">
                    <ValidationProvider class="mb-3 mx-5 col-lg" rules="required|alpha_spaces|min:8|max:30" v-slot="{ errors , classes}">
                        <label for="name">Nombre</label>
                        <input type="text" :class="classes" class="form-control" id="name" name="name"
                               placeholder="Ingrese Nombre de Role" v-model="data.name" v-bind:autocomplete="'off'">
                        <span class="text-danger">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>
                <div class="col-lg">
                    <button class="btn btn-success form-control" type="submit" id="id-btn-submit">Guardar <i
                        class="far fa-save"></i></button>
                </div>
            </form>
        </ValidationObserver>
    </b-container>
</template>
<script>
export default {
    props: ['roleModal'],
    name: "modal_detail",
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
        onSubmit() {
            let url =this.roleModal.url+(this.roleModal.edit?'/'+this.data.id:'')
            console.log(this.data)
            axios.post(url, this.data).then((res) => {
                SwalAlert(res.data.status, res.data.message, 2000)
                this.$emit('updateRoles')
                this.$root.$emit('bv::toggle::modal', this.roleModal.id)
            }).catch(function (error) {
                if (error.response.data.status) {
                    SwalAlert(error.response.data.status, error.response.data.message, 6000)
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
