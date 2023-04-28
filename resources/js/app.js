import Vue from "vue";

require('./bootstrap');
require('alpinejs');

import {ValidationObserver, ValidationProvider} from "vee-validate";
require('livewire-vue');
window.Vue = Vue
import swal from 'sweetalert2';
window.Swal = swal;
import Multiselect from 'vue-multiselect';
import 'vue-multiselect/dist/vue-multiselect.min.css'
import { BootstrapVue, IconsPlugin ,ModalPlugin } from 'bootstrap-vue'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import './validations'
Vue.component('ValidationObserver', ValidationObserver);
Vue.component('ValidationProvider', ValidationProvider);
Vue.component('Multiselect', Multiselect)
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
Vue.use(ModalPlugin)
import './components'
const app = new Vue({
        el :"#app",
    }
);
