<template>
    <div>
        <div class="invoice" v-if="loading == false">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" ref="factura_tab" id="factura-tab" data-bs-toggle="tab" data-bs-target="#factura-tab-pane" type="button" role="tab" aria-controls="factura-tab-pane" aria-selected="true">Factura</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="cuentas-tab" data-bs-toggle="tab" data-bs-target="#cuentas-tab-pane" type="button" role="tab" aria-controls="cuentas-tab-pane" aria-selected="false">Valores Pendientes</button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="factura-tab-pane" role="tabpanel" aria-labelledby="factura-tab" tabindex="0">
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
                                        <select :disabled='isDisabled' class="form-control" name="tipoComprobante" form="factura" v-model="factura.tipoComprobante" @change="getSerie(factura.tipoComprobante)">
                                            <option v-for="c in comprobantes" :value="c.codigo" >{{c.texto}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-4 col-form-label">Serie</label>
                                    <div class="col-8">
                                        <input type="text" class="form-control" v-model="factura.serial" readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-4 col-form-label">Secuencial</label>
                                    <div class="col-8">
                                        <input :disabled='isDisabled' type="text" class="form-control"  v-model="factura.secuencial" >
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
                                <div class="form-group row justify-content-end">
                                    <label class="col-form-label col-4">
                                        <span class="float-right">#Contrato</span>
                                    </label>
                                    <div class="col-8">
                                        <input type="text" name="contrato" v-model="numero" class='form-control' style='color: #ff0000; font-weight: 700; font-size: 2rem !important; line-height: 3rem; height: 3rem !important'  readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12">
                                <div class="form-group row">
                                    <label class="col-4 col-form-label">Forma Pago</label>
                                    <div class="col-8">
                                        <select :disabled='isDisabled' class="form-control" name="fpago" form="factura" v-model="factura.formaPago">
                                            <option v-for="f in formaspago" :value="f.codigo" >{{f.texto}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-4 col-form-label">Año Pago</label>
                                    <div class="col-8">
                                        <input :disabled='isDisabled' type="number" min="2020" step="1" class="form-control" form="factura" name="anioPago" v-model="factura.anioPago" >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-4 col-form-label">Mes Pago</label>
                                    <div class="col-8">
                                        <select :disabled='isDisabled' class="form-control" name="mesPago" form="factura" v-model="factura.mesPago">
                                            <option v-for="m in meses" :value="m.codigo" >{{m.texto}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-4 col-form-label">#Comp</label>
                                    <div class="col-8">
                                        <input :disabled='isDisabled' type="number" min="100" step="1" class="form-control" form="factura"  v-model="factura.comprobantePago" >
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
                                <div class="form-group row">
                                    <label class="col-4 col-form-label">Ambiente</label>
                                    <div class="col-8">
                                        <select :disabled='isDisabled' class="form-control" name="ambiente" form="factura" v-model="factura.tipoAmbiente">
                                            <option v-for="a in ambientes" :value="a.codigo" >{{a.texto}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-4 col-form-label">Observaciones</label>
                                    <div class="col-8">
                                        <textarea :disabled='isDisabled' class="form-control" form="factura" name="usuario" v-model="factura.observaciones" ></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" v-if="factura.mensajeSri">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                                <div class="form-group row">
                                    <label class="col-1 col-form-label">Mensaje SRI</label>
                                    <div class="col-11">
                                        <textarea :readonly="true" class="form-control" form="factura" name="usuario" v-model="factura.mensajeSri" ></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-end">
                                <lista-servicios :disabled='isDisabled' :baseurl="urlservicios" @agregarServicio="agregarServicio"></lista-servicios>
                                <button :disabled='isDisabled' type="button" form="factura" class="btn btn-success btn-group-sm" @click="guardarFactura">
                                    <i class="mdi mdi-content-save-all"></i>
                                    <span>Guardar</span>
                                </button>
                                <button :disabled='!isDisabled' class="btn btn-group-sm btn-info" @click="inicializar">
                                    <i class="mdi mdi-plus-circle"></i>
                                    <span>Nuevo</span>
                                </button>
                                <button :disabled='factura.id == null' class="btn btn-group-sm btn-dark" @click="imprimir">
                                    <i class="mdi mdi-printer"></i>
                                    <span>Imprimir</span>
                                </button>
                                <button :disabled='factura.id == null' class="btn btn-group-sm btn-dark" @click="descargar">
                                    <i class="mdi mdi-download"></i>
                                    <span>Descargar</span>
                                </button>
                            </div>
                        </div>

                        <!-- Row end -->
                    </div>
                    <div class="invoice-body">
                        <detalle-factura ref="detalles" :detalles="factura.detalles" :disabled="isDisabled"></detalle-factura>
                    </div>
                </div>
                <div class="tab-pane fade" id="cuentas-tab-pane" role="tabpanel" aria-labelledby="cuentas-tab" tabindex="0">
                    <h5 v-if="deudas.length == 0">No existen valores pendientes</h5>
                    <cuenta_cuotas @agregarCuenta="agregarCuenta" v-for="deuda in deudas" v-bind:key="JSON.stringify(deuda)" :cuentadata="JSON.stringify(deuda)">
                    </cuenta_cuotas>
                </div>
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
    import DetalleFactura from "./detalle-factura";
    import FacturaPagos from "./factura_pagos";
    import ListaServicios from "../lista-servicios";
    import ListaContratos from "../lista-contratos";
    import CuentasXCobrar from "../cuentasxcobrar/cuenta_cuotas";
    export default {
        name: "factura",
        components:{
            DetalleFactura,
            FacturaPagos,
            ListaServicios,
            ListaContratos,
            CuentasXCobrar
        },
        data(){
          return{
              factura:{
                fecha: '',
                secuencial: null,
                tipoComprobante: '01',
                detalles: []
              },
              secuencial: '',
              comprobante: '',
              comprobantes:[],
              ambientes: [],
              formaspago:[],
              deudas: [],
              meses,
              cedula: null,
              nombres: null,
              numero: null,
              formatted: '',
              selected: '',
              loading: false,
              loadingText: 'Guardando',
              isDisabled: true,
              hayValoresPendientes: false
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
            'user',
            'datafactura'
        ],
        methods:{
            agregarDetalle(item){
                this.$refs.detalles.agregarDetalle(item);
            },
            agregarServicio(item) {
                item.esServicio = true;
                item.cantidad = 1;
                item.subtotal = item.precioSinImp;
                this.agregarDetalle(item)
            },
            agregarCuenta(cuenta){
                console.log(cuenta)
                const descripcion = cuenta.detalles.map(d => d.descripcion).join(', ');
                cuenta.cuotas.forEach(cuota => {
                    if(!cuota.pagada){
                        cuota.agregada = true;
                        const cod = `CXC${cuenta.id}-${cuota.id}`;
                        const nombre = `${descripcion}. Cuota #${cuota.numero} de ${cuenta.plazo}.`;
                        let item = {
                            id: cuota.id,
                            producto: null,
                            servicio: null,
                            codigo: cod,
                            nombre: nombre,
                            precioSinImp: cuota.valorSinImp,
                            precio: cuota.valor,
                            cantidad: 1,
                            subtotal: cuota.valorSinImp,
                            esCuota: true,
                            incluyeIva: true,
                            porcentaje: 12,//modificar
                            editable: false,

                        };
                        this.agregarDetalle(item)
                        //this.agregarDetalle(detalle);
                    }
                });
                this.$refs.factura_tab.click();
            },
            agregarContrato(contrato){
                console.log(contrato);
                this.factura.contrato = contrato.id;
                this.numero = contrato.numero;
                this.factura.cliente = contrato.cliente.id;
                this.nombres = contrato.cliente.nombres;
                this.cedula = contrato.cliente.dni.numero;
                this.deudas = contrato.cliente.deudas;
                this.deudas.forEach(d => {
                    const cuotasVencidas = d.cuotas.filter(c => !c.pagada);
                    if(cuotasVencidas.length > 0){
                        this.hayValoresPendientes = true;
                    }
                })
                if(contrato.mesPago) this.factura.mesPago = contrato.mesPago == 12 ? 1: contrato.mesPago +1;
                if(contrato.anioPago) this.factura.anioPago = contrato.mesPago == 12 ? contrato.anioPago +1:contrato.anioPago;
                contrato.plan.nombre += '- Mes de: '+meses[this.factura.mesPago-1].texto + ' año ' +this.factura.anioPago;
                this.$refs.detalles.inicializar();

                let descuento = contrato.cliente.esDiscapacitado || contrato.cliente.esTerceraEdad ? 50:0;
                contrato.plan.descuento = Number(contrato.plan.precioSinImp)*(descuento/100).toFixed(3);
                contrato.plan.precioSinImp  = (Number(contrato.plan.precioSinImp) - contrato.plan.descuento).toFixed(3);
                contrato.plan.precio = contrato.plan.precioSinImp*(1 + (contrato.plan.porcentaje/100)).toFixed(2);
                contrato.plan.cantidad = 1;
                contrato.plan.subtotal = contrato.plan.precioSinImp;
                contrato.plan.esServicio = true;
                this.agregarDetalle(contrato.plan);
                if(contrato.necesitaReconexion) this.agregarServicioReconexion();
            },
            async getComprobantes(){
                await axios.get(this.urlcatalogocomprobantes)
                    .then(r=>{
                      this.comprobantes = r.data.opciones;
                    })
            },
            async getSerie(codigo){
                await axios.get(`${this.urlserie}/${codigo}`)
                    .then(r=>{
                        let ptoEmi = r.data;
                        this.factura.serial = ptoEmi.codigo+'-'+ptoEmi.codigoEstablecimiento;
                        this.factura.puntoEmision = ptoEmi.id;
                        if(this.factura.serial) this.getSecuencial(ptoEmi.id );
                    })

            },
            async getSecuencial(pto_id){
                await axios.get(`${this.urlsecuencial}/${pto_id}`)
                    .then(r=>{
                        this.factura.secuencial = r.data.secuencial;
                    })
            },
            async getAmbientes(){
                await axios.get(this.urlcatalogoambientes)
                    .then(r=>{
                        this.ambientes = r.data.opciones;
                        this.factura.tipoAmbiente = '2';
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
                        servicio.esServicio = true;
                        this.agregarDetalle(servicio);
                    })
            },
            async guardarFactura(){
                let continuar = true;
                if(this.hayValoresPendientes){
                    continuar = confirm('Existen valores pendientes de pago. ¿Está seguro de continuar?');
                }
                if(!continuar) return ;
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
                        text: "Su factura ha sido guardada correctamente.", // Text that is to be shown in the toast
                        heading: '! Éxito !', // Optional heading to be shown on the toast
                        icon: 'success', // Type of toast icon
                        position: 'top-right', // bottom-left or bottom-right or bottom-center or top-left or top-right or top-center or mid-center or an object representing the left, right, top, bottom values
                    });
                    //location.reload();
                    //this.inicializar();
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
                //ctx.selectedFormatted= ctx.activeFormatted
                //ctx.selectedYMD = ctx.activeYMD ;
                // The date formatted in the locale, or the `label-no-date-selected` string
                this.formatted = ctx.selectedFormatted
                // The following will be an empty string until a valid date is entered
                this.selected = ctx.selectedYMD

                //console.log(ctx)
            },
            /*
            refresh(data){
                this.factura.detalles = data;
            },

             */
            async inicializar() {

                this.factura = {
                    tipoComprobante: '01',
                    formaPago: '01',
                    tipoAmbiente: '2',
                    secuencial: '',
                    cliente: {},
                    contrato: {},
                    puntoEmision: {},
                    detalles: []
                };
                if(this.$refs.detalles)this.$refs.detalles.inicializar();
                this.numero = null;
                this.nombres = null;
                this.cedula = null;
                //this.secuencial = null;
                this.isDisabled = false;
                await this.getAmbientes();
                await this.getFormasPago();
                await this.getComprobantes();
                if(!this.datafactura){
                    this.getSerie(this.factura.tipoComprobante);
                    let fecha = new Date();
                    let mes = (fecha.getMonth() + 1).toString().length == 1 ? `0${fecha.getMonth() + 1}` : `${fecha.getMonth() + 1}`;
                    let dia = (fecha.getDate()).toString().length == 1 ? `0${fecha.getDate()}` : `${fecha.getDate()}`;
                    let anio = fecha.getFullYear();

                    this.factura.fecha = `${anio}-${mes}-${dia}`;
                    this.factura.mesPago = fecha.getMonth() + 1;
                    this.factura.anioPago = fecha.getFullYear();
                }
            },
            imprimir(){
                let link = '/factura/'+this.factura.id;
                window.open(link,'Imprimir', 'width=500, height=600');
                return false;
            },
            descargar(){
                let link = '/factura/'+this.factura.id+'/descargar';
                window.open(link,'_blank');
                return false;
            }

        },
        async beforeMount() {
            if(this.datafactura){
                console.log(JSON.parse(this.datafactura))
                this.loadingText = 'Cargando';
                this.loading = true;

                await this.inicializar();
                let data = JSON.parse(this.datafactura);
                this.factura = data.factura;
                this.factura.puntoEmision = data.puntoEmision.id;
                this.factura.serial = data.factura.serie;
                this.factura.detalles = JSON.parse(JSON.stringify(data.detalles)).map(d => {
                    console.log(d)
                    d.codigo = d.codigo;
                    d.descuento = Number(d.descuento).toFixed(2);
                    d.porcentaje = d.esServicio ? d.servicio.porcentaje:12;
                    d.servicio = d.esServicio ? d.servicio.id: null;
                    d.cuota = !d.esServicio ? d.cuota.id:null
                    d.porcentaje = Number(d.porcentaje).toFixed(2);
                    return d;
                });
                this.factura.secuencial = data.factura.secuencial;
                this.factura.cliente = data.cliente?.id;
                this.factura.contrato = data.contrato?.id;
                this.cedula = data.cliente?.dni?.numero;
                this.nombres = data.cliente?.nombres;
                this.numero = data.contrato?.numero;
                this.loading = false;
            }
        },
        watch:{
            fecha(value){
                this.factura.fecha = value;
            }
        }
    }
</script>

<style scoped>

</style>