<template>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="table-responsive">
                <div class="container-fluid">
                    <div class="row border border-2 bg-primary text-white">
                        <div class="col-1">#</div>
                        <div class="col-1">Cod</div>
                        <div class="col-5">Detalle</div>
                        <div class="col-2">Precio</div>
                        <div class="col-1">Cant.</div>
                        <div class="col-1">Dcto</div>
                        <div class="col-1">SubT.</div>
                    </div>
                    <div class="row border border-1" v-for="d in detalles_local">
                        <div class="col-1">
                            <button class="btn btn-sm btn-danger" @click="quitarDetalle(d)" :disabled="isDisabled">
                                <i class="mdi mdi-trash-can"></i>
                            </button>
                        </div>
                        <div class="col-1">
                            {{d.codigo}}
                        </div>
                        <div class="col-5">
                            <input type="text"  v-model="d.descripcion" class="form-control" :disabled="isDisabled">
                        </div>
                        <div class="col-2">
                            <input type="number" min="0" v-model="d.precio" class="form-control" :disabled="isDisabled">
                        </div>
                        <div class="col-1">
                            <input type="number" step="1" min="0" v-model="d.cantidad" class="form-control" :disabled="isDisabled">
                        </div>
                        <div class="col-1">
                            {{descuentoDetalle(d)}}
                        </div>
                        <div class="col-1">
                            {{subtotalDetalle(d)}}
                        </div>
                    </div>

                    <div class="row justify-content-end">
                        <div class="col-2">
                            <div class="align-content-end">
                                <p class="d-flex justify-content-end">Subtotal</p>
                                <p class="d-flex justify-content-end">IVA 12%</p>
                                <h4 class="d-flex justify-content-end text-success">TOTAL</h4>
                            </div>
                        </div>
                        <div class="col-2">
                            <p class="d-flex justify-content-end">
                                $<span>{{subTotalSinImpuestos().toFixed(2)}}</span>
                            </p>
                            <p class="d-flex justify-content-end">
                                $<span>{{iva12().toFixed(2)}}</span>
                            </p>
                            <h4 class="d-flex justify-content-end text-success">
                                <i class="mdi mdi-square-inc-cash"></i>
                                <strong>{{total().toFixed(2)}}</strong>
                            </h4 >
                        </div>
                    </div>

                </div>
                
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "detalle-factura",
        props:[
            'disabled',
            'detalles'
        ],
        data(){
            return {
                detalles_local: [],
                isDisabled: false
            }
        },
        methods:{
            subtotalDetalle(d){
                let porcentaje = 1 + d.porcentaje/100;
                return d.incluyeIva ? ((d.cantidad * d.precio)/(porcentaje)).toFixed(2):(d.cantidad * d.precio).toFixed(2)
            },
            descuentoDetalle(d){
                let descuento =  ( d.precioOriginal - d.precio ) * d.cantidad;
                return descuento.toFixed(2);
            },
            totalDetalle(d){
                return d.incluyeIva ? (d.cantidad * d.precio).toFixed(2):(d.cantidad * d.precio*(1+0.12)).toFixed(2)
            },
            subTotalSinImpuestos(){
                return this.detalles_local.reduce((p, c) => p + Number(this.subtotalDetalle(c)),0);
            },
            iva12(){
                return this.detalles_local.reduce((p, c) =>{
                    let subtotal = Number(this.subtotalDetalle(c));
                    let total = Number(this.totalDetalle(c));
                    return c.porcentaje == 12 ? p + total - subtotal:  p;
                },0);
            },
            total(){
                return this.subTotalSinImpuestos() + this.iva12();
            },
            quitarDetalle(d){
                this.detalles_local = this.detalles_local.filter( v => v.servicio != d.servicio);
            },
            agregarDetalle(servicio){
                let s =  JSON.parse(JSON.stringify(servicio))
                let detalle = {
                    servicio: servicio.id,
                    producto: null,
                    codigo: servicio.codigo,
                    descripcion: servicio.nombre,
                    precio: servicio.precio,
                    precioOriginal: servicio.precio,
                    cantidad: 1,
                    subtotal: servicio.precio,
                    esServicio: true,
                    incluyeIva: servicio.incluyeIva,
                    porcentaje: servicio.porcentaje,
                    descuento: 0.00
                }
                this.detalles_local.push(detalle);
            },
            inicializar(){
                this.detalles_local = [];
            }
        },
        beforeMount() {
            if(this.detalles) this.detalles_local = JSON.parse(JSON.stringify(this.detalles));
            this.isDisabled = this.disabled;
        },
        watch: {
            /*
            detalles_local: {
                handler(newValue) {
                    this.$emit('refresh', newValue);
                },
                deep: true
            },
             */
            disabled(value){
                this.isDisabled = value;
            }
        }
    }
</script>

<style scoped>

</style>