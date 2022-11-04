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
                    return v;
                })
                if(this.clientes.length > 0){
                    let cliente = this.clientes[0];
                    this.campos = Object.keys(cliente).map(k =>{
                        return {text: k, value: k}
                    });
                }
            }
        }
    }
</script>

<style scoped>

</style>