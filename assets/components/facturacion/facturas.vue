<template>
    <div>
        <table-index
                :items="facturas"
                :length="facturas.length"
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
            'urlenvio'
        ],
        data(){
            return{
                facturas:[],
                campos:[],
                filtros:[
                    {text: 'Secuencial', value: 'secuencial'},
                    {text:'Cedula/Ruc', value:'cedula'},
                    {text:'Cliente', value:'nombres'},
                    {text:'Contrato', value:'numero'}
                    ]
            }
        },
        name: "facturas",
        mounted() {
            if(this.listado){
                let listado = JSON.parse(this.listado);
                this.facturas = listado.map((v)=>{
                    v.fecha = moment(String(v.fecha)).format('DD/MM/YYYY');
                    this.establecerAcciones(v);
                    return v;
                })
                if(this.facturas.length > 0){
                    let factura = this.facturas[0];
                    this.campos = Object.keys(factura).map(k =>{
                        return {text: k, value: k}
                    });
                }
            }
        },
        methods:{
            actualizarFactura(factura){
                this.facturas = this.facturas.map(f => {
                    if(f.id == factura.id) f.estado = factura.estado;
                    return f;
                });

            },
            establecerAcciones(v){
                v.actions = [];
                if(v.estadoSri == 'RECIBIDA'){
                    let autorizar = (v)=>{
                        console.log(v)
                    }
                    v.actions.push({
                        color: 'primary',
                        texto: 'Autorizar',
                        callback: async ()=>{
                            await axios.put(this.urlautorizacion+'/'+v.id).then(r => {
                                if(r.data.estado){
                                    v.estadoSri = r.data.estado;
                                }
                            }).catch(e =>{
                                console.log(e)
                            })
                        }
                    });
                }
                if(v.estadoSri == 'AUTORIZADO'){
                    v.actions.push({
                        color: 'info',
                        texto: 'Enviar email',
                        callback: async ()=>{
                            await axios.put(this.urlenvio+'/'+v.id).then(r => {
                                if(r.data.estado){
                                    v.estadoSri = r.data.estado;
                                }
                            }).catch(e =>{
                                console.log(e)
                            })
                        }
                    });
                }
            }
        }
    }
</script>

<style scoped>

</style>