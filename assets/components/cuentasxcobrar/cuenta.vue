<template>
    <div>
        <div class="invoice" v-if="loading == false">
            <div class="invoice-header">
                <div class="">
                    <slot>
                        <form action="" id="factura"></form>
                    </slot>
                </div>
                <!-- Row start -->
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12">
                        <div class="form-group row">
                            <label class="col-4 col-form-label">#ID</label>
                            <div class="col-8">
                                <input type="text" class="form-control" v-model="factura.id" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-4 col-form-label">Emisión</label>
                            <div class="col-8">
                                <b-form-datepicker
                                        :disabled="isDisabled"
                                        v-model="factura.fecha"
                                        left
                                        locale="es-EC"
                                        @context="onContext"
                                        size="sm"
                                        :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
                                ></b-form-datepicker>
                            </div>
                        </div>

                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12">
                        <div class="form-group row">
                            <label class="col-4 col-form-label">CI/RUC</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <input :disabled='isDisabled' type="text" v-model="cedula" class="form-control" form="factura" name="cedula" readonly>
                                    <div class="input-group-append">
                                        <lista-contratos :disabled='isDisabled' :baseurl="urlcontratos" :color_class="'btn-warning'" @agregarContrato="agregarContrato">
                                        </lista-contratos>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-4 col-form-label">Cliente</label>
                            <div class="col-8">
                                <input type="text" v-model="nombres" class="form-control" form="factura" name="nombre" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12">
                        <div class="form-group row">
                            <label class="col-4 col-form-label">Plazo(meses)</label>
                            <div class="col-8">
                                <input :disabled='isDisabled' type="number" min="0" step="1" class="form-control" form="factura" name="plazo" v-model="factura.plazo">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-4 col-form-label">Observaciones</label>
                            <div class="col-8">
                                <textarea :disabled='isDisabled' class="form-control" form="factura" name="usuario" v-model="factura.observaciones" ></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12">
                        <div class="form-group row">
                            <label class="col-4 col-form-label">Usuario</label>
                            <div class="col-8">
                                <input :disabled='isDisabled' type="text" class="form-control" form="factura" name="usuario" v-model="user" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 d-flex justify-content-end">
                        <lista-servicios :disabled='isDisabled' :baseurl="urlservicios" @agregarServicio="agregarDetalle"></lista-servicios>
                        <button :disabled='isDisabled' type="button" form="factura" class="btn btn-success btn-group-sm" @click="guardarFactura">
                            <i class="mdi mdi-content-save-all"></i>
                            <span>Guardar</span>
                        </button>
                        <button :disabled='!isDisabled' class="btn btn-group-sm btn-info" @click="inicializar">
                            <i class="mdi mdi-plus-circle"></i>
                            <span>Nuevo</span>
                        </button>
                    </div>
                </div>

                <!-- Row end -->
            </div>
            <div class="invoice-body">
                <detalle-cuenta ref="detalles" :detalles="factura.detalles" :disabled="isDisabled"></detalle-cuenta>
            </div>
        </div>
        <div  style="height: 75vh" v-if="loading == true">
            <div class="d-flex align-items-center">
                <strong class="m-3">{{loadingText}}</strong>
                <b-spinner class="ml-auto"></b-spinner>
            </div>
        </div>
    </div>
</template>
<script>
    import DetalleCuenta from "./detalle-cuenta";
    import ListaServicios from "../lista-servicios";
    import ListaContratos from "../lista-contratos";
    export default {
        name: "cuenta",
        components:{
            DetalleCuenta,
            ListaServicios,
            ListaContratos
        },
        data(){
            return{
                factura:{
                    fecha: '',
                    detalles: []
                },
                cedula: null,
                nombres: null,
                formatted: '',
                selected: '',
                loading: false,
                loadingText: 'Guardando',
                isDisabled: true
            }
        },
        props:[
            'urlservicios',
            'urlcontratos',
            'user',
            'datafactura'
        ],
        methods:{
            agregarDetalle(servicio){
                this.$refs.detalles.agregarDetalle(servicio);
            },
            agregarContrato(contrato){
                this.factura.cliente = contrato.cliente.id;
                this.nombres = contrato.cliente.nombres;
                this.cedula = contrato.cliente.dni.numero;
            },
            async guardarFactura(){
                this.factura.detalles = JSON.parse(JSON.stringify(this.$refs.detalles.detalles_local));
                let factura = this.factura;
                factura.detalles = factura.detalles.map(d => {
                    d.precio = d.precio.toString();
                    d.cantidad = d.cantidad.toString();
                    d.subtotal = d.subtotal.toString();
                    return d;
                })
                //factura.secuencial = this.secuencial;
                this.loadingText = 'Guardando';
                this.loading = true;
                let success = false;
                await axios.post('', factura).then(r => {
                    this.loading = false;
                    console.log(r);
                    if(r.status == 200){
                        this.factura.id = r.data.id;
                        success = true;
                    }
                }).catch(e => {
                    console.log(e);
                })
                this.loading = false;
                if(success){
                    $.toast({
                        text: "Su cuenta ha sido guardada correctamente.", // Text that is to be shown in the toast
                        heading: '! Éxito !', // Optional heading to be shown on the toast
                        icon: 'success', // Type of toast icon
                        position: 'top-right', // bottom-left or bottom-right or bottom-center or top-left or top-right or top-center or mid-center or an object representing the left, right, top, bottom values
                    });
                    this.isDisabled = true;
                }
                else{
                    $.toast({
                        text: "Inténtelo nuevamente.",
                        heading: 'Error',
                        icon: 'danger',
                        position: 'top-right',
                    });
                }

            },
            onContext(ctx) {
                // The date formatted in the locale, or the `label-no-date-selected` string
                this.formatted = ctx.selectedFormatted
                // The following will be an empty string until a valid date is entered
                this.selected = ctx.selectedYMD
            },
            async inicializar() {

                this.factura = {
                    cliente: {},
                    contrato: {},
                    puntoEmision: {},
                    detalles: []
                };
                if(this.$refs.detalles)this.$refs.detalles.inicializar();
                this.nombres = null;
                this.cedula = null;
                //this.secuencial = null;
                this.isDisabled = false;

                if(!this.datafactura){
                    let fecha = new Date();
                    let mes = (fecha.getMonth() + 1).toString().length == 1 ? `0${fecha.getMonth() + 1}` : `${fecha.getMonth() + 1}`;
                    let dia = (fecha.getDate()).toString().length == 1 ? `0${fecha.getDate()}` : `${fecha.getDate()}`;
                    let anio = fecha.getFullYear();
                    this.factura.fecha = `${anio}-${mes}-${dia}`;
                    this.factura.mesPago = fecha.getMonth() + 1;
                    this.factura.anioPago = fecha.getFullYear();
                }
            },
        },
        async beforeMount() {
            if(this.datafactura){
                this.loadingText = 'Cargando';
                this.loading = true;

                await this.inicializar();
                let data = JSON.parse(this.datafactura);
                this.factura = data.factura;
                this.factura.detalles = JSON.parse(JSON.stringify(data.detalles)).map(d => {
                    d.codigo = d.servicio.codigo;
                    d.incluyeIva = d.servicio.incluyeIva;
                    d.precioOriginal = d.servicio.precio;
                    d.porcentaje = d.servicio.porcentaje;
                    d.servicio = d.servicio.id;
                    return d;
                });

                this.factura.cliente = data.cliente?.id;
                this.cedula = data.cliente?.dni?.numero;
                this.nombres = data.cliente?.nombres;
                this.loading = false;
            }
        },
        watch:{

        }
    }
</script>

<style scoped>

</style>