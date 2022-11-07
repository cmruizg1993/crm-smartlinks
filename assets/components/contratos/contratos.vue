<template>
    <div>
        <table-index
                :items="contratos"
                :length="contratos.length"
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
                contratos:[],
                campos:[],
                filtros:[
                    {text: 'Nombres', value: 'nombres'},
                    {text:'Cedula/Ruc', value:'cedula'},
                    {text:'Número', value:'numero'},
                    {text:'Estado', value:'estadoActual'}
                    ]
            }
        },
        name: "contratos",
        mounted() {
            if(this.listado){
                let listado = JSON.parse(this.listado);
                this.contratos = listado.map((v)=>{
                    //v.cedula = v.cliente.dni.numero;
                    //v.nombres = v.cliente.nombres;
                    v.fecha = moment(String(v.fecha)).format('DD/MM/YYYY');
                    if(v.estadoActual){
                        let estado = v.estadoActual.texto;
                        let variant = v.estadoActual.cssClass ;
                        v.estadoActual = estado;
                        v._rowVariant= variant;
                        //delete v.estadoActual;
                    }

                    return v;
                })
                if(this.contratos.length > 0){
                    let contrato = this.contratos[0];
                    this.campos = Object.keys(contrato).map(k =>{
                        return {text: k, value: k}
                    });
                    console.log(this.campos)
                }
            }
        }
    }
</script>

<style scoped>

</style>