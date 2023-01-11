/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';

// Import Bootstrap and BootstrapVue CSS files (order is important)
import "bootstrap/dist/css/bootstrap-utilities.min.css"
import "bootstrap-vue/dist/bootstrap-vue.css";

import Vue from 'vue'
import { BootstrapVue, IconsPlugin } from "bootstrap-vue";
import moment from 'moment';
import JsonExcel from "vue-json-excel";

Vue.filter('formatDate',(value)=>{
    if(value){
        return moment(String(value)).format('DD/MM/YYYY');
    }
})
Vue.component("downloadExcel", JsonExcel);
// Make BootstrapVue available throughout your project
Vue.use(BootstrapVue);
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin);

Vue.component('lista-servicios', require('./components/lista-servicios').default )
Vue.component('lista-contratos', require('./components/lista-contratos').default )
//FACTURACION
Vue.component('factura', require('./components/facturacion/factura').default )
Vue.component('facturas', require('./components/facturacion/facturas').default )
Vue.component('detalle-factura', require('./components/facturacion/detalle-factura').default )
Vue.component('factura_pagos', require('./components/facturacion/factura_pagos').default)
//GENERAL
Vue.component('table-index', require('./components/general/table-index').default )
//CLIENTES
Vue.component('clientes', require('./components/clientes/clientes').default )
//SERVICIOS
Vue.component('contratos', require('./components/contratos/contratos').default )
//CUENTAS X COBRAR
Vue.component('cuentas', require('./components/cuentasxcobrar/cuentas').default)
Vue.component('cuenta', require('./components/cuentasxcobrar/cuenta').default)


const app = new Vue({
    el: '#app',
    //template: '<lista-servicios></lista-servicios>'
})
app.$mount();