<?php

namespace App\Controller;

use App\Entity\Contrato;
use App\Entity\EstadoContrato;
use App\Entity\Factura;
use App\Entity\OpcionCatalogo;
use App\Entity\Servicio;
use App\Entity\TipoComprobante;
use App\Entity\Usuario;
use App\Repository\ContratoRepository;
use App\Repository\FacturaRepository;
use App\Repository\OpcionCatalogoRepository;
use App\Repository\ServicioRepository;
use App\Repository\TipoComprobanteRepository;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/facturacion/bancos")
 */
class FacturacionBancosController extends AbstractController
{
    private $meses =
    [
        "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
    ];
    /**
     * @Route("/reporte/activos", name="reporte_cobros")
     */
    public function reporteActivos
    (
        ContratoRepository $contratoRepository,
        OpcionCatalogoRepository $opcionCatalogoRepository
    ): Response
    {
        $fs = new Filesystem();
        $fileName = "../reporte1.txt";
        $fs->remove($fileName);
        $estadoActivo = $opcionCatalogoRepository->findOneByCodigoyCatalogo(EstadoContrato::ACTIVO, 'est-cont');
        $activos = $contratoRepository->findByEstado($estadoActivo);
        $estadoCortado = $opcionCatalogoRepository->findOneByCodigoyCatalogo(EstadoContrato::CORTADO, 'est-cont');
        $cortados = $contratoRepository->findByEstado($estadoCortado);
        $fecha = new \DateTime();

        $tipos = $opcionCatalogoRepository->findByCodigoCatalogo('tipo-doc');
        $tiposBasic = [];
        $mapper = function (OpcionCatalogo $tipo) use (&$tiposBasic){
            if($tipo->getCodigo() == "04"){
                $tiposBasic["04"] = "R";
            }
            if($tipo->getCodigo() == "05"){
                $tiposBasic["05"] = "C";
            }
            if($tipo->getCodigo() == "06"){
                $tiposBasic["06"] = "P";
            }
            return;
        };
        array_map($mapper, $tipos);

        $fCobro = "REC";
        $blanco1 = "";
        $blanco2 = "";
        $tipo = "CO";
        $moneda = "USD";
        $anio = (int)$fecha->format('Y');
        $mes = (int)$fecha->format('m');
        $referencia = "INTERNET - $mes/$anio";

        foreach ($cortados as $contrato) {
            if($contrato->isEsCortesia()) continue;
            $contrapartida = (string)$contrato->getNumero();
            $precio = $contrato->getPlan()->getPrecio();

            $anioPago = $contrato->getAnioPago();
            $mesPago = $contrato->getMesPago();
            $proporcional = 0;
            $mesesDeuda = 0;

            if($anio > $anioPago){
                $diff = $anio - $anioPago;
                $mesesDeuda = 12 - $mesPago;
                $mesesDeuda += $diff > 1 ? ($diff - 1)*12 : $mesesDeuda;
                $mesesDeuda += $mes;
            }elseif ($anio == $anioPago ){
                $mesesDeuda = $mes - $mesPago;
            }

            if($mesesDeuda > 1){
                $proporcional += ($precio/30)*10;
            }

            $cliente = $contrato->getCliente();

            if($cliente->getEsDiscapacitado() || $cliente->getEsTerceraEdad()){
                $precio /= 2;
            }

            $valor = $precio;
            $valor += 2;
            $valor += $proporcional;
            $valor = round($valor, 2);
            $valor *= 100;

            $tipoIdCliente = $cliente->getDni()->getTipo();
            if($tipoIdCliente == '07' || $tipoIdCliente == '08') continue;
            $tipoId = $tiposBasic["$tipoIdCliente"];
            $numeroId = $cliente->getDni()->getNumero();
            $nombreCliente = $cliente->getNombres();
            $fila = [$tipo, $contrapartida, $moneda, $valor, $fCobro, $blanco1, $blanco2, $referencia, $tipoId, $numeroId, $nombreCliente, "\n"];
            $filaStr = implode("\t", $fila);
            $fs->appendToFile($fileName, $filaStr);
        }
        $dia = (int)$fecha->format('d');
        if($dia >= 1 && $dia <= 10){
            foreach ($activos as $contrato) {
                $contrapartida = (string)$contrato->getNumero();

                $precio = $contrato->getPlan()->getPrecio();
                if($cliente->getEsDiscapacitado() || $cliente->getEsTerceraEdad()){
                    $precio /= 2;
                }
                $valor = $precio;
                $valor *= 100;
                $valor = round($valor, 0);

                $tipoIdCliente = $contrato->getCliente()->getDni()->getTipo();
                if($tipoIdCliente == '07' || $tipoIdCliente == '08') continue;

                $tipoId = $tiposBasic["$tipoIdCliente"];
                $numeroId = $contrato->getCliente()->getDni()->getNumero();
                $nombreCliente = $contrato->getCliente()->getNombres();
                $fila = [$tipo, $contrapartida, $moneda, $valor, $fCobro, $blanco1, $blanco2, $referencia, $tipoId, $numeroId, $nombreCliente, "\n"];
                $filaStr = implode("\t", $fila);
                $fs->appendToFile($fileName, $filaStr);
            }
        }


        return  $this->file($fileName);
        /*
        return $this->render('facturacion_bancos/index.html.twig', [
            'controller_name' => 'FacturacionBancosController',
        ]);
        */
    }

    public function facturarPago
    (
        Request $request,
        Contrato $contrato,
        Servicio $reconexion,
        $rowData
        /*OpcionCatalogo $impuesto*/
    ){
        $codigoFactura = '01';
        /**
         * @var $puntoEmisionResponse Response
         */
        $puntoEmisionResponse = $this->forward(
            'App\Controller\PuntoEmisionController::getPuntoByCodigoComprobante',
            ['codigo'=> $codigoFactura]
        );
        $code = $puntoEmisionResponse->getStatusCode();
        //return $puntoEmisionResponse;
        if($code != 200) return $puntoEmisionResponse;

        $puntoEmisionData = json_decode($puntoEmisionResponse->getContent(), true);


        $puntoEmision = $puntoEmisionData["puntoEmision"];
        $secuencial = $puntoEmisionData["secuencial"];

        $fecha = new \DateTime();

        $anio = (int) $fecha->format('Y');
        $mes = (int) $fecha->format('m');

        $mesPagado = $this->meses[$mes - 1];

        // que hay si una persona no contrata

        $cliente = $contrato->getCliente();
        $plan = $contrato->getPlan();
        $descripcionDetalle1 = $plan->getNombre(). " - Mes de ".$mesPagado. " Año ".$anio;

        $campos = $this->getFileMapper();

        $data = [];
        $data["tipoComprobante"] = "01";
        $data["formaPago"] = "20"; // revisar
        $data["tipoAmbiente"] = "2"; // producción
        $data["facturaPlan"] = true;
        $data["secuencial"] = $secuencial;
        $data["cliente"] = $cliente->getId();
        $data["contrato"] = $contrato->getId();
        $data["puntoEmision"] = $puntoEmision["id"];
        $data["fecha"] = $fecha->format('Y-m-d');

        $data["anioPago"] = $anio;
        $data["mesPago"] = $mes;
        $data["esFacturacionAutomatica"] = true;

        $data["serial"] = $puntoEmision["serie"];
        $data["comprobantePago"] = $rowData["$campos->NumeroDocumento"];
        $data["observaciones"] = "";
        $data["detalles"] = [];
        
        $plan = $contrato->getPlan();
        $descuento = 0;
        if($cliente->getEsDiscapacitado() || $cliente->getEsTerceraEdad()){
            $descuento = 50;
        }


        //$porcentaje =
        $planDescuento = $plan->getPrecioSinImp()*($descuento/100);
        $precioSinImp  = ($plan->getPrecioSinImp() - $planDescuento);
        $tieneIva = true;//$impuesto->getCodigo() == $plan->getCodigoPorcentaje();
        $porcentaje = 12;//$tieneIva ? $impuesto->getValorNumerico() : 0;
        $precio = round ( $precioSinImp*(1 + ($porcentaje/100)), 2 );
        
        $detalle = [];
        $detalle["producto"] = null;
        $detalle["servicio"] = $plan->getId();
        $detalle["cuota"] = null;
        $detalle["codigo"] = $plan->getCodigo();
        $detalle["descripcion"] = $descripcionDetalle1;
        $detalle["precioSinImp"] = $precioSinImp;
        $detalle["precio"] = $precio;
        $detalle["subtotal"] = $precioSinImp;
        $detalle["esServicio"] = true;
        $detalle["incluyeIva"] = true;
        $detalle["porcentaje"] = "12.00";
        $detalle["descuento"] = $planDescuento;
        $detalle["cantidad"] = 1;

        $data["detalles"][] = $detalle;

        if($contrato->getEstadoActual()->getCodigo() == EstadoContrato::CORTADO){
            $detalle1 = [];
            $detalle1["producto"] = null;
            $detalle1["servicio"] = $reconexion->getId();
            $detalle1["cuota"] = null;
            $detalle1["codigo"] = $reconexion->getCodigo();
            $detalle1["descripcion"] = $reconexion->getNombre();
            $detalle1["precioSinImp"] = $reconexion->getPrecioSinImp();
            $detalle1["precio"] = $reconexion->getPrecio();
            $detalle1["subtotal"] = $reconexion->getPrecioSinImp();
            $detalle1["esServicio"] = true;
            $detalle1["incluyeIva"] = true;
            $detalle1["porcentaje"] = "12.00";
            $detalle2["codigoPorcentaje"] = 2;
            $detalle1["descuento"] = null;
            $detalle1["cantidad"] = 1;

            $data["detalles"][] = $detalle1;
            /*
            $detalle2 = [];
            $detalle2["producto"] = null;
            $detalle2["servicio"] = $plan->getId();
            $detalle2["cuota"] = null;
            $detalle2["codigo"] = $plan->getCodigo();
            $detalle2["descripcion"] = $plan->getNombre() ." - Proporcional";
            $detalle2["precioSinImp"] = ($precioSinImp/30)*10;
            $detalle2["precio"] = ($precio/30)*10;
            $detalle2["subtotal"] = ($precioSinImp/30)*10;
            $detalle2["esServicio"] = true;
            $detalle2["incluyeIva"] = true;
            $detalle2["porcentaje"] = "12.00";
            $detalle2["codigoPorcentaje"] = 2;
            $detalle2["descuento"] = null;
            $detalle2["cantidad"] = 1;

            $data["detalles"][] = $detalle2;
            */
        }

        //dump($data);
        $request->setMethod("POST");
        $request->request->set("data", json_encode($data));
        $result = $this->forward('App\Controller\FacturaController::new', $data);
        return $result;

    }

    /**
     * @Route("/subir/archivo", name="subir_archivo")
     */
    public function cargarArchivo(
        Request $request,
        ContratoRepository $contratoRepository,
        ServicioRepository $servicioRepository,
        OpcionCatalogoRepository $opcionCatalogoRepository,
        FacturaRepository $facturaRepository
    ): Response
    {
        $form = $this->createFormBuilder([])
            ->add('archivo', FileType::class, ['attr' => ['required' => 'required']])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $mapper = $this->getFileMapper();

            $file = $form['archivo']->getData();

            $errores = [];

            if ($file) {

                $pathFile = $file->getPathName();
                $rows = $this->getFileData($pathFile);
                $mapper = $this->getFileMapper();
                $reconexion = $servicioRepository->obtenerServicioByCod(Servicio::CODIGO_RECONEXION);
                $resumen = [];
                $impuestos = $opcionCatalogoRepository->findByCodigoCatalogo('iva');
                foreach ($rows as $row) {
                    $ContraPartida = $row["$mapper->ContraPartida"];
                    $contrato = $contratoRepository->findByNumero($ContraPartida);
                    if(!$contrato) continue;

                    $result = $this->facturarPago($request, $contrato, $reconexion, $row);
                    $code = $result->getStatusCode();
                    $comprobante =  $row["$mapper->NumeroDocumento"];

                    $item = [];
                    $item["status"] = $result->getStatusCode();
                    $item["comprobante"] = $comprobante;
                    $item["contrato"] = $ContraPartida;

                    if($code == 200){
                        $item["mensaje"] = "Factura generada con éxito.";
                    }
                    elseif($code == 400){
                        $item["mensaje"] = $result->getContent() ;
                    }
                    else{
                        $item["mensaje"] = "Hubo problemas para generar la factura.";
                    }
                    $resumen[] = $item;
                }

            }
        }
        return $this->render('facturacion_bancos/index.html.twig', [
            'controller_name' => 'FacturacionBancosController',
            'form'=>$form->createView(),
            'resumen' => isset($resumen) ? $resumen : []
        ]);
    }

    private function getFileData(string $pathFile){
        $initRow = 5;
        $data = file_get_contents($pathFile);
        $dataArray = explode("\n", $data);

        $rows = array_splice($dataArray, $initRow );

        $filter = function(string $row){
            $columns = explode("\t", $row);
            return $columns !== false && count($columns) >40;
        };
        $mapper = function(string $row) {
            $columns = explode("\t", $row);
            return $columns;
        };
        $rows = array_filter($rows, $filter);
        $rows = array_map($mapper, $rows);
        return $rows;
    }

    private function getFileMapper(){
        $map = (object)[
            'ReferenciaSobre' => '0',
            'Estado_Sobre' => '1',
            'Fecha_Inicio_Proceso' => '2',
            'Fecha_Vencimiento_Proceso' => '3',
            'ContraPartida' => '4',
            'Referencia' => '5',
            'Moneda' => '6',
            'NombreContrapartida' => '7',
            'Valor' => '8',
            'TipoPago' => '9',
            'TipoId_Cliente' => '10',
            'NumeroId_Cliente' => '11',
            'Estado_Proceso' => '12',
            'Id_Contrato' => '13',
            'Id_Sobre' => '14',
            'Id_Item' => '15',
            'PaisBancoCuenta' => '16',
            'Eliminado' => '17',
            'Canal' => '18',
            'Medio' => '19',
            'NumeroDocumento' => '20',
            'Horario' => '21',
            'Mensaje' => '22',
            'Oficina' => '23',
            'FechaProcesoDate' => '24',
            'FechaProceso' => '25',
            'HoraProceso' => '26',
            'ValorProcc' => '27',
            'Estado_Impresion' => '28',
            'FormaPago' => '29',
            'Tabla' => '30',
            'Pais' => '31',
            'Banco' => '32',
            'Referencia_Adicional' => '33',
            'Secuencial_Cobro' => '34',
            'Numero_Comprobante' => '35',
            'NumeroCuenta' => '36',
            'No_Documento' => '37',
            'Tipo_Cuenta' => '38',
            'Numero_Cuenta' => '39',
            'CondicionProceso' => '40',
            'NumeroTransaccion' => '41',
        ];
        return $map;
    }

}
