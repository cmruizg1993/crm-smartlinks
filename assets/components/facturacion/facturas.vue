<template>
    <div>
        <div class="container">
            <div class="row justify-content-end">
                <div class="col">
                    <button  @click="autorizarTodas" type="button" :class="'btn btn-sm btn-primary'">
                        Autorizar Todas
                    </button>
                    <button  @click="enviarTodas" type="button" :class="'btn btn-sm btn-info'">
                        Enviar Todas
                    </button>
                </div>
            </div>
        </div>
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
            'urlenvio',
            'urlanulacion'
        ],
        data(){
            return{
                facturas:[],
                camposReporte: [],
                campos:[{ text: 'id', key: 'id'}, { text: 'serie', key: 'serie'}, { text: 'secuencial', key: 'secuencial'}, { text: 'cedula', key: 'cedula'},{ text: 'nombres', key: 'nombres'}, { text: 'fecha', key: 'fecha'}, { text: 'numero', key: 'numero'}, { text: 'total', key: 'total'}, { text: 'formaPago', key: 'formaPago'}, { text: 'estadoSri', key: 'estadoSri'}, { text: 'actions', key: 'actions'}],
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
                    v.fecha = moment(String(v.strFecha)).format('DD/MM/YYYY');
                    delete v.strFecha;
                    this.establecerAcciones(v);
                    return v;
                })
                /*
                if(this.facturas.length > 0){
                    let factura = this.facturas[0];
                    this.campos = Object.keys(factura).map(k =>{
                        return {text: k, value: k}
                    });
                }*/
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
                v.estadoSri = v.estadoSri ? v.estadoSri:'';

               // if(v.estadoSri == 'DEVUELTA' || v.estadoSri == 'NO AUTORIZADO' || v.estadoSri == ''){
                    v.actions.push({
                        color: 'warning',
                        texto: 'Revisar',
                        callback: async ()=>{
                            this.revisar(v);
                        }
                    });
               // }
                if(v.estadoSri == 'RECIBIDA' || v.estadoSri == 'EN PROCESO'){

                    v.actions.push({
                        color: 'primary',
                        texto: 'Autorizar',
                        callback: async ()=>{
                            this.autorizar(v);
                        }
                    });
                }

                if(v.estadoSri == 'AUTORIZADO'){
                    v.actions.push({
                        color: 'info',
                        texto: 'Enviar email',
                        callback: async ()=>{
                            this.enviar(v);
                        }
                    });

                }
                if(v.estadoSri.indexOf('AUTORIZADO')>=0){
                    v.actions.push({
                        color: 'success',
                        texto: 'Descargar',
                        callback: async ()=>{
                            this.descargar(v);
                        }
                    });
                }
                if(v.estadoSri == 'ANULADA') v._rowVariant = 'danger';
                else {
                    v.actions.push({
                        color: 'danger',
                        texto: 'Anular',
                        callback: async ()=>{
                            await this.anular(v);
                        }
                    });
                }
            },
            async autorizarTodas(){
                this.facturas.forEach(v => {
                    if(v.estadoSri == 'RECIBIDA'){
                        this.autorizar(v);
                    }
                })
            },
            async enviarTodas(v){
                this.facturas.forEach(v => {
                    if(v.estadoSri == 'AUTORIZADO'){
                        this.enviar(v);
                    }
                })
            },
           async autorizar(v){
                axios.put(this.urlautorizacion+'/'+v.id).then(r => {
                    if(r.data.estado){
                        v.estadoSri = r.data.estado;
                        this.establecerAcciones(v);
                    }
                }).catch(e =>{
                    console.log(e)
                })
            },
            async enviar(v){
                axios.put(this.urlenvio+'/'+v.id).then(r => {
                    if(r.data.estado){
                        v.estadoSri = r.data.estado;
                        this.establecerAcciones(v);
                    }
                }).catch(e =>{
                    console.log(e)
                })
            },
            async anular(v){
                await axios.put(this.urlanulacion+'/'+v.id).then(r => {
                    if(r.data.estado){
                        v.estadoSri = r.data.estado;
                        this.establecerAcciones(v);
                    }
                }).catch(e =>{
                    console.log(e)
                })
            },
            async descargar(v){
                let link = '/factura/'+v.id+'/descargar';
                window.open(link,'_blank');
                return false;
            },
            async revisar(v){
                let link = '/factura/'+v.id+'/edit';
                location.href = link;
                //return false;
            }
        }
    }
</script>

<style scoped>

</style>