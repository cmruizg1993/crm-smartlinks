<template>
    <div class="container-fluid">
        <div class="container">
            <div class="card">
                <h5 class="card-header">Cuenta por cobrar #{{cuenta.id}}</h5>
                <div class="card-body">
                    <h5 class="card-title">#ID: {{cuenta.id}}</h5>
                    <h5 class="card-title">Fecha: {{cuenta.fecha.toString().split('T')[0]}}</h5>
                    <h5 class="card-title">Total: {{cuenta.total}}</h5>
                    <p class="card-text"><b>Observaciones: </b>{{cuenta.observaciones}}</p>
                    <table class="table table-sm table-bordered table-striped m-3">
                        <tr class="bg-primary text-light p-0">
                            <th>#ID</th>
                            <th>Número</th>
                            <th>Vence</th>
                            <th>Valor</th>
                            <th>Estado</th>
                            <th>Recargo</th>
                            <th>Observ.</th>
                        </tr>
                        <tbody>
                            <tr v-for="cuota in cuenta.cuotas" >
                                <td>{{cuota.id}}</td>
                                <td>{{cuota.numero}}</td>
                                <td>{{cuota.fechaVencimiento.toString().split('T')[0]}}</td>
                                <td>{{cuota.valor}}</td>
                                <td>
                                    <b v-if="cuota.pagada">PAGADA</b>
                                </td>
                                <td>
                                    <input type="number" min="0" step="0.1" class="form-control form-control-sm" v-model="cuota.recargo">
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm" v-model="cuota.observaciones">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <button class="btn btn-primary" @click="agregarCuenta">Agregar a la factura</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "cuenta_cuotas",
        props: [
            "cuentadata"
        ],
        data(){
            return {
              cuenta: {},
            }
        },
        beforeMount() {
            if(this.cuentadata){
                this.cuenta = JSON.parse(this.cuentadata);
                console.log(this.cuentadata)
            }
        },
        watch:{

        },
        methods:{
            agregarCuenta(){
                this.$emit('agregarCuenta', this.cuenta);
            }
        }
    }
</script>

<style scoped>

</style>