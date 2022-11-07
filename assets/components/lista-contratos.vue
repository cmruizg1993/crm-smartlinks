<template>
    <div>
        <!-- Button trigger modal -->
        <button :disabled='disabled' type="button" class="btn btn-group-sm" :class="color_class" data-toggle="modal" data-target="#backdropContratos">
            <i class="icon-search"></i>
            <slot></slot>
        </button>

        <!-- Modal -->
        <div class="modal fade" id="backdropContratos"  tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Buscar Contrato</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="modalCerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row form-group">

                            <label class="col-form-label col-4">Buscar:</label>
                            <div class="col-8">
                                <input type="text" class="form-control"  @keyup="getItems" v-model="parametro" id="parametroContrato">
                            </div>
                        </div>
                        <table class="table table-hover table-bordered table-bordered">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Número</th>
                                <th>Cliente</th>
                                <th>CI/RUC</th>
                                <th>Parroquia
                                </th>
                            </tr>
                            </thead>
                            <tbody id="tContratos">
                            <tr v-for="c in contratos">
                                <td>
                                    <input type="radio" name="contrato_id" @click="contrato = c" v-model="c.selected">
                                </td>
                                <td>{{ c.numero }}</td>

                                <td>{{ c.cliente.nombres }}</td>
                                <td>
                                    <p v-if="c.cliente.dni">{{ c.cliente.dni.numero }}</p>
                                </td>
                                <td>
                                    <p v-if="c.parroquia">{{ c.parroquia.nombre }}</p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" id="modalAceptar2" @click="agregarContrato">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        name: "lista-contratos",
        props:[
            'baseurl',
            'color_class',
            'disabled'
        ],
        data(){
            return {
                parametro: '',
                contrato: null,
                contratos:[]
            }
        },
        methods:{
            async getItems(){
                this.contrato = null;
                this.contratos = [];
                if (this.parametro.length < 3 ) return;
                let param = this.parametro;

                await axios.post(this.baseurl,{param}).then(r => {
                    if(r.data.contratos.length > 0){
                        this.contratos = r.data.contratos.map(c => {
                            c.selected = false;
                            return c;
                        });
                    }
                });

            },
            agregarContrato(){
                this.$emit('agregarContrato', this.contrato);
            }
        },
        mounted() {
        }
    }
</script>

<style scoped>

</style>