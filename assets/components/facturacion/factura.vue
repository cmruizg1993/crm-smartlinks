<template>
    <div>
        <div class="invoice" v-if="!loading">
            <div class="invoice-header">
                <div class="d-none">
                    <slot>
                        <form action="" id="factura"></form>
                    </slot>
                </div>
                <!-- Row start -->
                <div class="row">
                    <div class="col-xl-3 col-lg-3secuencial col-md-3 col-sm-12 col-12">

                        <div class="form-group row">
                            <label class="col-4 col-form-label">Emisión</label>
                            <div class="col-8">
                                <b-form-datepicker
                                        v-model="factura.fecha"
                                        left
                                        locale="es-EC"
                                        @context="onContext"
                                        size="sm"
                                        :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
                                ></b-form-datepicker>
                            </div>
                            <!--div class="col-8">
                                <div class="input-group date datepicker navbar-date-picker">
                                    <span class="input-group-addon input-group-prepend border-right">

                                    </span>
                                    <input type="text" class="form-control" v-model="factura.fecha" autocomplete="off">
                                </div>
                            </div-->
                        </div>
                        <div class="form-group row">
                            <label class="col-4 col-form-label">Comprobante</label>
                            <div class="col-8">
                                <select class="form-control" name="tipoComprobante" form="factura" v-model="factura.tipoComprobante" @change="getSerie(factura.tipoComprobante)">
                                    <option v-for="c in comprobantes" :value="c.codigo" >{{c.texto}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-4 col-form-label">Serie</label>
                            <div class="col-8">
                                <input type="text" class="form-control" v-model="factura.serie" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-4 col-form-label">Secuencial</label>
                            <div class="col-8">
                                <input type="text" class="form-control"  v-model="secuencial" >
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12">
                        <div class="form-group row">
                            <label class="col-4 col-form-label">CI/RUC</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <input type="text" v-model="factura.cliente.cedula" class="form-control" form="factura" name="cedula" readonly>
                                    <div class="input-group-append">
                                        <lista-contratos :baseurl="urlcontratos" :color_class="'btn-warning'" @agregarContrato="agregarContrato">
                                        </lista-contratos>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-4 col-form-label">Cliente</label>
                            <div class="col-8">
                                <input type="text" v-model="factura.cliente.nombres" class="form-control" form="factura" name="nombre" readonly>
                            </div>
                        </div>
                        <div class="form-group row justify-content-end">
                            <label class="col-form-label col-4">
                                <span class="float-right">#Contrato</span>
                            </label>
                            <div class="col-8">
                                <input type="text" name="contrato" v-model="factura.contrato.numero" class='form-control' style='color: #ff0000; font-weight: 700; font-size: 2rem !important; line-height: 3rem; height: 3rem !important'  readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12">
                        <div class="form-group row">
                            <label class="col-4 col-form-label">Forma Pago</label>
                            <div class="col-8">
                                <select class="form-control" name="fpago" form="factura" v-model="factura.formaPago">
                                    <option v-for="f in formaspago" :value="f.codigo" >{{f.texto}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-4 col-form-label">Año Pago</label>
                            <div class="col-8">
                                <input type="number" min="2020" step="1" class="form-control" form="factura" name="anioPago" v-model="factura.anioPago" >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-4 col-form-label">Mes Pago</label>
                            <div class="col-8">
                                <select class="form-control" name="mesPago" form="factura" v-model="factura.mesPago">
                                    <option v-for="m in meses" :value="m.codigo" >{{m.texto}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-4 col-form-label">#Comprobante</label>
                            <div class="col-8">
                                <input type="number" min="100" step="1" class="form-control" form="factura"  v-model="factura.comprobantePago" >
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12">
                        <div class="form-group row">
                            <label class="col-4 col-form-label">Usuario</label>
                            <div class="col-8">
                                <input type="text" class="form-control" form="factura" name="usuario" v-model="user" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-4 col-form-label">Ambiente</label>
                            <div class="col-8">
                                <select class="form-control" name="ambiente" form="factura" v-model="factura.tipoAmbiente">
                                    <option v-for="a in ambientes" :value="a.codigo" >{{a.texto}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-4 col-form-label">Observaciones</label>
                            <div class="col-8">
                                <textarea class="form-control" form="factura" name="usuario" v-model="factura.observaciones" ></textarea>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-end">
                        <lista-servicios :baseurl="urlservicios" @agregarServicio="agregarDetalle"></lista-servicios>
                        <button type="button" form="factura" class="btn btn-success btn-group-sm" @click="guardarFactura">
                            <i class="icon-file-add"></i>
                            <span>Guardar</span>
                        </button>
                    </div>
                </div>
                <!-- Row end -->
            </div>
            <div class="invoice-body">
                <detalle-factura :detalles="factura.detalles" @quitarDetalle="quitarDetalle"></detalle-factura>
            </div>
        </div>
        <div  style="height: 75vh" v-else>
            <div class="d-flex align-items-center">
                <strong class="m-3">Guardando</strong>
                <b-spinner class="ml-auto"></b-spinner>
            </div>
        </div>
    </div>
</template>
<script>
    import DetalleFactura from "./detalle-factura";
    import ListaServicios from "../lista-servicios";
    import ListaContratos from "../lista-contratos";

    export default {
        name: "factura",
        components:{
            DetalleFactura,
            ListaServicios,
            ListaContratos
        },
        data(){
          return{
              factura:{
                tipoComprobante: '01',
                cliente:{},
                contrato:{},
                puntoEmision:{},
                detalles: []
              },
              secuencial: '',
              comprobante: '',
              comprobantes:[],
              ambientes: [],
              formaspago:[],
              detalles:[],
              meses,
              formatted: '',
              selected: '',
              loading: false
          }
        },
        props:[
            'urlservicios',
            'urlcontratos',
            'urlcatalogocomprobantes',
            'urlcatalogoambientes',
            'urlserie',
            'urlsecuencial',
            'urlcatalogopagos',
            'urlreconexion',
            'user'
        ],
        methods:{
            agregarDetalle(servicio){
                let s =  JSON.parse(JSON.stringify(servicio))
                let detalle = {
                    servicio:{
                        id: servicio.id,
                    },
                    id_producto: null,
                    codigo: servicio.codigo,
                    descripcion: servicio.nombre,
                    precio: servicio.precio,
                    cantidad: 1,
                    subtotal: servicio.precio,
                    esServicio: true,
                    incluyeIva: servicio.incluyeIva,
                    porcentaje: servicio.porcentaje
                }

                this.factura.detalles.push(detalle);
                //$("#factura_detallesjson").val(JSON.stringify(this.detalles));
            },
            agregarContrato(contrato){
                this.factura.contrato.id = contrato.id;
                this.factura.contrato.numero = contrato.numero;
                this.factura.cliente.id = contrato.cliente.id;
                this.factura.cliente.nombres = contrato.cliente.nombres;
                this.factura.cliente.cedula = contrato.cliente.dni.numero;
                if(contrato.mesPago) this.factura.mesPago = contrato.mesPago == 12 ? 1: contrato.mesPago +1;
                if(contrato.anioPago) this.factura.anioPago = contrato.mesPago == 12 ? contrato.anioPago +1:contrato.anioPago;
                this.agregarDetalle(contrato.plan);
                if(contrato.necesitaReconexion) this.agregarServicioReconexion();
            },
            async getComprobantes(){
                await axios.get(this.urlcatalogocomprobantes)
                    .then(r=>{
                      this.comprobantes = r.data.opciones;
                      this.getSerie(this.factura.tipoComprobante);
                    })
            },
            async getSerie(codigo){
                await axios.get(`${this.urlserie}/${codigo}`)
                    .then(r=>{
                        let ptoEmi = r.data;
                        this.factura.serie = ptoEmi.codigo+'-'+ptoEmi.codigoEstablecimiento;
                        this.factura.puntoEmision = ptoEmi;
                        if(this.factura.serie) this.getSecuencial(ptoEmi.id );
                    })

            },
            async getSecuencial(pto_id){
                await axios.get(`${this.urlsecuencial}/${pto_id}`)
                    .then(r=>{
                        this.secuencial = r.data.secuencial;
                    })
            },
            async getAmbientes(){
                await axios.get(this.urlcatalogoambientes)
                    .then(r=>{
                        this.ambientes = r.data.opciones;
                        this.factura.tipoAmbiente = '1';
                    })
            },
            async getFormasPago(){
                await axios.get(this.urlcatalogopagos)
                    .then(r=>{
                        this.formaspago = r.data.opciones;
                        this.factura.formaPago = '01';
                    })
            },
            async agregarServicioReconexion(){
                await axios.get(this.urlreconexion)
                    .then(r=>{
                        let servicio = r.data;
                        this.agregarDetalle(servicio);
                    })
            },
            async guardarFactura(){
                let factura =this.factura;
                factura.detalles = factura.detalles.map(d => {
                    d.precio = d.precio.toString();
                    d.cantidad = d.cantidad.toString();
                    d.subtotal = d.subtotal.toString();
                    return d;
                })
                factura.secuencial = this.secuencial;
                this.loading = true;
                let success = false;
                await axios.post('', factura).then(r => {
                    this.loading = false;
                    if(r.status == 200)
                       success = true;
                }).catch(e => {

                    console.log(e);
                })
                this.loading = false;
                if(success){
                    $.toast({
                        text: "Su factura ha sido guardada correctamente.", // Text that is to be shown in the toast
                        heading: '! Éxito !', // Optional heading to be shown on the toast
                        icon: 'success', // Type of toast icon
                        position: 'top-right', // bottom-left or bottom-right or bottom-center or top-left or top-right or top-center or mid-center or an object representing the left, right, top, bottom values
                    });
                    location.reload();
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
            quitarDetalle(d){
                this.factura.detalles = this.detalles.filter( v => v.id_servicio != d.id_servicio);
            }
        },
        mounted() {
            this.getComprobantes();
            this.getAmbientes();
            this.getFormasPago();
            let fecha = new Date();
            let mes = (fecha.getMonth() + 1).toString().length == 1 ? `0${fecha.getMonth() + 1}`:`${fecha.getMonth() + 1}`;
            let dia = (fecha.getDate()).toString().length == 1 ? `0${fecha.getDate()}`:`${fecha.getDate()}`;
            let anio = fecha.getFullYear();

            this.factura.fecha = `${anio}-${mes}-${dia}`;
            this.factura.mesPago = fecha.getMonth() + 1;
            this.factura.anioPago = fecha.getFullYear();
        }
    }
</script>

<style scoped>

</style>