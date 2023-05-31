<template>
    <b-container fluid class="bg-white text-dark">
        <ValidationObserver v-slot="{ handleSubmit, reset }">
            <form @submit.prevent="handleSubmit(onSubmit)" @reset.prevent="reset" id="id_formulario">
                <div class="row">
                    <ValidationProvider class="form-group col-md-4" rules="required|alpha_spaces|min:10|max:45" v-slot="{ errors , classes}">
                        <label>Nombres</label>
                        <input type="text" :class="classes" class="form-control" name="Nombre"
                               placeholder="Ingrese los Nombres" v-model="data.name" autocomplete="new-name">
                        <span class="text-danger">{{ errors[0] }}</span>
                    </ValidationProvider>
                    <ValidationProvider class="form-group col-md-4" rules="required|alpha_spaces|min:10|max:45" v-slot="{ errors , classes}">
                        <label>Apellidos</label>
                        <input type="text" :class="classes" class="form-control" name="Apellido"
                               placeholder="Ingrese los Apellidos" v-model="data.lastname" autocomplete="new-lastname">
                        <span class="text-danger">{{ errors[0] }}</span>
                    </ValidationProvider>
                    <ValidationProvider class="form-group col-md-4" rules="required|numeric|min:13|max:13" v-slot="{ errors , classes}">
                        <label>DPI</label>
                        <input type="text" :class="classes" class="form-control" name="DPI"
                               placeholder="Ingrese no. DPI" v-model="data.dpi" autocomplete="new-dpi">
                        <span class="text-danger">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>
                <div class="row center-align">
                    <ValidationProvider class="form-group col-md-5" rules="required|email|min:10|max:100" v-slot="{ errors , classes}">
                        <label>Correo</label>
                        <input type="email" :class="classes" class="form-control" name="Correo"
                               placeholder="Ingrese el correo" v-model="data.email" autocomplete="new-email">
                        <span class="text-danger">{{ errors[0] }}</span>
                    </ValidationProvider>
                    <ValidationProvider class="form-group col-md-6" rules="alpha_dash|min:10" v-slot="{ errors , classes}">
                        <label>Contraseña</label>
                        <input type="password" :class="classes" class="form-control" name="Contraseña"
                               placeholder="Deje este campo vacio si no desea modificar la contraseña" v-model="data.password" autocomplete="new-password">
                        <span class="text-danger">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>
                <div class="col-lg">
                    <button class="btn btn-success form-control" type="submit" id="id-btn-submit">Guardar<i
                        class="far fa-save"></i></button>
                </div>
            </form>
        </ValidationObserver>
    </b-container>
</template>
<script>

export default {
    props: ['userModal'],
    name: "modal_detail",
    data() {
        return {
            data: {
                id: null,
                dpi: null,
                name: null,
                lastname: null,
                email: null,
                password: null
            },
        }
    },
    mounted() {
        if (this.userModal.edit) {
            this.data = this.userModal.user
            this.data.password= null
        }
    },
    methods: {
        onSubmit() {
            let url =this.userModal.url+(this.userModal.edit?'/'+this.data.id:'')
            console.log(this.data)
            axios.post(url, this.data).then((res) => {
                SwalAlert(res.data.status, res.data.message, 2000)
                this.$emit('updateUsers')
                this.$root.$emit('bv::toggle::modal', this.userModal.id)
            }).catch(function (error) {
                if (error.response.data.status) {
                    SwalAlert(error.response.data.status, error.response.data.message, 6000)
                } else {
                    Swal.fire("Algo salio mal", `${Object.values(error.response.data.errors)}`, "error");
                }
            })
        }
    }
}
</script>

<style scoped>

</style>
