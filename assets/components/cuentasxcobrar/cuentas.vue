<template>
    <div>
        <table-index
                :items="cuentas"
                :length="cuentas.length"
                :fields="campos"
                :filters="filtros"
        >

        </table-index>
    </div>

</template>

<script>
    import TableIndex from "../general/table-index";
    import moment from "moment";
    export default {
        components: {TableIndex},
        props:[
            'listado'
        ],
        data(){
            return{
                cuentas:[],
                campos:[{ text: 'id', key: 'id'},{ text: 'fecha', key: 'fecha'}, { text: 'cedula', key: 'cedula'}, { text: 'nombres', key: 'nombres'}, { text: 'total', key: 'total'}, { text: 'abono', key: 'abono'}, { text: 'actions', key: 'actions'}],
                filtros:[
                    {text: 'Nombres', value: 'nombres'},
                    {text:'Cedula/Ruc', value:'cedula'},
                    {text:'Fecha', value:'fecha'}
                ]
            }
        },
        name: "cuentas",
        mounted() {
            if(this.listado){
                let listado = JSON.parse(this.listado);
                this.cuentas = listado.map((v)=>{
                    v.cedula = v.cliente.dni.numero;
                    v.nombres = v.cliente.nombres;
                    v.fecha = moment(String(v.fecha)).format('DD/MM/YYYY');
                    if(v.estadoActual){
                        let estado = v.estadoActual.texto;
                        let variant = v.estadoActual.cssClass ;
                        v.estadoActual = estado;
                        v._rowVariant= variant;
                        //delete v.estadoActual;
                    }
                    v.actions = [];
                    v.actions.push({
                        color: 'warning',
                        texto: 'Editar',
                        callback: async ()=>{
                            window.location.href = '/cuenta/por/cobrar/'+v.id+'/edit'; //
                        }
                    });
                    return v;
                })
                /*
                if(this.cuentas.length > 0){
                    let cuenta = this.cuentas[0];
                    this.campos = Object.keys(cuenta).map(k =>{
                        return {text: k, value: k}
                    });
                    console.log(this.campos)
                }
                 */
            }
        }
    }
</script>

<style scoped>

</style>