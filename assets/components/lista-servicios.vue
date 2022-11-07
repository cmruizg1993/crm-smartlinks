<template>
    <div>
        <!-- Button trigger modal -->
        <button :disabled='disabled' type="button" class="btn btn-primary btn-group-sm" data-toggle="modal" data-target="#backdropServicios">
            <i class="icon-search"></i>
            <span>Agregar Item</span>
        </button>

        <!-- Modal -->
        <div class="modal fade" id="backdropServicios"  tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Buscar Servicio</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="modalCerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row form-group">

                            <label class="col-form-label col-4">Buscar:</label>
                            <div class="col-8">
                                <input type="text" class="form-control" v-model="parametro" @keyup="getItems">
                            </div>
                        </div>
                        <table class="table table-hover table-bordered table-bordered">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Precio</th>
                            </tr>
                            </thead>
                            <tbody id="tServicios">
                                <tr v-for="s in servicios">
                                    <td>
                                        <input type="radio" name="servicio_id" @click="servicio = s" v-model="s.selected">
                                    </td>
                                    <td>{{ s.codigo }}</td>
                                    <td>{{ s.nombre }}</td>
                                    <td>{{ s.precio }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" id="modalAceptar2" @click="agregarServicio">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        name: "lista-servicios",
        props:[
            'baseurl',
            'disabled'
        ],
        data(){
            return {
                parametro: '',
                servicio: null,
                servicios:[]
            }
        },
        methods:{
            async getItems(){
                this.servicio = null;
                this.servicios = [];
                if (this.parametro.length < 3 ) return;
                let param = this.parametro;

                await axios.post(this.baseurl,{param}).then(r => {
                    if(r.data){
                        this.servicios = r.data.map(s => {
                            s.selected = false;
                            return s;
                        });
                        console.log(this.servicios)
                    }
                });

            },
            agregarServicio(){
                this.$emit('agregarServicio', this.servicio);
            }
        },
        mounted() {
        }
    }
</script>

<style scoped>

</style>