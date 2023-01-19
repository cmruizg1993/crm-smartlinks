<template>
    <div>
        <table-index
                :items="ordenes"
                :length="ordenes.length"
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
            'listado',
            'urlautorizacion',
            'urlrecepcion',
            'urledicion',
            'urlenvio',
            'urlanulacion'
        ],
        data(){
            return{
                ordenes:[],
                camposReporte: [],
                campos:['id', 'cedula','nombres', 'numero', 'tipo', 'estado' ,'fecha', 'tecnico', 'actions'],
                filtros:[
                    {text: 'Estado', value: 'estado'},
                    {text:'Cedula/Ruc', value:'cedula'},
                    {text:'Cliente', value:'nombres'},
                    {text:'Contrato', value:'numero'}
                ]
            }
        },
        name: "ordenes",
        mounted() {
            if(this.listado){
                let listado = JSON.parse(this.listado);
                this.ordenes = listado.map((v)=>{
                    v.fecha = moment(v.fecha).format('DD/MM/YYYY');
                    delete v.strFecha;
                    v.cedula = v.Contrato.cliente.dni.numero;
                    v.nombres = v.Contrato.cliente.nombres;
                    v.numero = v.Contrato.numero;
                    v.estado = v.estado.nombre;
                    v.tecnico = v.tecnico.nombres;
                    v.tipo = v.tipo.nombre;
                    this.establecerAcciones(v);
                    return v;
                })
            }
        },
        methods:{
            establecerAcciones(v){
                v.actions = [];
                v.actions.push({
                    color: 'warning',
                    texto: 'Editar',
                    callback: async ()=>{
                        this.editar(v);
                    }
                });
                v.actions.push({
                    color: 'info',
                    texto: 'Mostrar',
                    callback: async ()=>{
                        this.mostrar(v);
                    }
                });
            },
            async editar(v){
                let link = '/orden/'+v.id+'/edit';
                location.href = link;
            },
            async mostrar(v){
                let link = '/orden/'+v.id;
                location.href = link;
            }
        }
    }
</script>

<style scoped>

</style>