<template>
    <div>
        <table-index
                :items="clientes"
                :length="clientes.length"
                :fields="campos"
                :filters="filtros"
        >
        </table-index>
    </div>

</template>

<script>
    import TableIndex from "../general/table-index";
    export default {
        components: {TableIndex},
        props:[
            'listado'
        ],
        data(){
            return{
                clientes:[],
                campos:[],
                filtros:[{text: 'Nombres', value: 'nombres'}, {text:'Cedula/Ruc', value:'cedula'}]
            }
        },
        name: "clientes",
        mounted() {
            if(this.listado){
                let listado = JSON.parse(this.listado);
                this.clientes = listado.map((v)=>{
                    v.cedula = v.dni.numero;
                    delete v.dni;
                    this.establecerAcciones(v);
                    return v;
                })
                if(this.clientes.length > 0){
                    let cliente = this.clientes[0];
                    this.campos = Object.keys(cliente).map(k =>{
                        return {text: k, value: k}
                    });
                }
            }
        },
        methods:{
            establecerAcciones(v){
                v.actions = [];
                v.actions.push({
                    color: 'warning',
                    texto: 'Editar',
                    callback: async ()=>{
                        await this.editar(v);
                    }
                });
                //if(v.estadoSri == 'ANULADA') v._rowVariant = 'danger';

            },
            async editar(v){
                let link = '/cliente/edit/'+v.id;
                location.href = link;
                //return false;
            }
        }
    }
</script>

<style scoped>

</style>