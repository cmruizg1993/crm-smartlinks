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
                        <div class="col-1">Dcto/U</div>
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
                            <input :readonly="!d.editable" type="text"  v-model="d.descripcion" class="form-control" :disabled="isDisabled">
                        </div>
                        <div class="col-2">
                            <input :readonly="!d.editable" type="number" min="0" :value="d.precioSinImp" @change="cambiarPrecio(d,$event)" class="form-control" :disabled="isDisabled">
                        </div>
                        <div class="col-1">
                            <input :readonly="!d.editable" type="number" step="1" min="0" v-model="d.cantidad" @change="cambiarCantidad(d,$event)"  class="form-control" :disabled="isDisabled">
                        </div>
                        <div class="col-1">
                            {{d.descuento.toFixed(3)}}
                        </div>
                        <div class="col-1">
                            {{d.subtotal.toFixed(3)}}
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
                                $<span>{{subTotalSinImpuestos().toFixed(3)}}</span>
                            </p>
                            <p class="d-flex justify-content-end">
                                $<span>{{iva12().toFixed(3)}}</span>
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
            cambiarCantidad(d,event){
                d.subtotal = Number(d.cantidad * d.precioSinImp).toFixed(3);
            },
            cambiarPrecio(d, event){
                const precioAnterior = Number(d.precioSinImp);
                const nuevoPrecio = Number(event.target.value);
                const descuento = precioAnterior - nuevoPrecio + Number(d.descuento);
                console.log(descuento, nuevoPrecio)
                if(descuento < 0){
                    event.target.value = precioAnterior;
                    $.toast('No se puede aumentar el precio original' );
                    return;
                }
                d.descuento = Number(descuento).toFixed(3);
                d.precioSinImp = Number(nuevoPrecio).toFixed(3);
                d.precio = Number(d.precioSinImp*(1 + (d.porcentaje/100))).toFixed(2);
                d.subtotal = Number(d.cantidad * d.precioSinImp).toFixed(3);

            },
            totalDetalle(d){
                return Number(d.precio*d.cantidad).toFixed(3);
            },
            subTotalSinImpuestos(){
                return this.detalles_local.reduce((p, c) => p + Number(c.subtotal),0);
            },
            iva12(){
                return this.detalles_local.reduce((p, c) =>{
                    const ivaDetalle = (c.subtotal*Number(c.porcentaje)/100);
                    return p + ivaDetalle;
                },0);
            },
            total(){
                return this.subTotalSinImpuestos() + this.iva12();
            },
            quitarDetalle(d){
                this.detalles_local = this.detalles_local.filter( v => v.esServicio ? v.servicio != d.servicio: true);
                this.detalles_local = this.detalles_local.filter( v => v.esCuota ? v.cuota != d.cuota: true);
            },
            agregarDetalle(item){

                let detalle = {
                    producto: item.esProducto ? item.id: null,
                    servicio: item.esServicio ? item.id: null,
                    cuota: item.esCuota ? item.id: null,
                    codigo: item.codigo,
                    descripcion: item.nombre,
                    precioSinImp: item.precioSinImp,
                    precio: item.precio,
                    cantidad: item.cantidad,
                    subtotal: item.subtotal,
                    esServicio: item.esServicio,
                    esCuota: item.esCuota,
                    incluyeIva: item.incluyeIva,
                    porcentaje: item.porcentaje,
                    descuento: item.descuento ? item.descuento: 0.00,
                    editable: item.editable === false ? false:true
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