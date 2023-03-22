<?php

namespace App\Controller;

use App\Entity\Contrato;
use App\Entity\EstadoContrato;
use App\Entity\Factura;
use App\Entity\OpcionCatalogo;
use App\Repository\ContratoRepository;
use App\Repository\OpcionCatalogoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/facturacion/bancos")
 */
class FacturacionBancosController extends AbstractController
{
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

            $valor = $precio;
            $valor += 2;
            $valor += $proporcional;
            $valor = round($valor, 2);
            $valor *= 100;

            $tipoIdCliente = $contrato->getCliente()->getDni()->getTipo();
            $tipoId = $tiposBasic["$tipoIdCliente"];
            $numeroId = $contrato->getCliente()->getDni()->getNumero();
            $nombreCliente = $contrato->getCliente()->getNombres();
            $fila = [$tipo, $contrapartida, $moneda, $valor, $fCobro, $blanco1, $blanco2, $referencia, $tipoId, $numeroId, $nombreCliente, "\n"];
            $filaStr = implode("\t", $fila);
            $fs->appendToFile($fileName, $filaStr);
        }
        $dia = (int)$fecha->format('d');
        if($dia >= 1 && $dia <= 10){
            foreach ($activos as $contrato) {
                $contrapartida = (string)$contrato->getNumero();

                $valor = $contrato->getPlan()->getPrecio();
                $valor *= 100;
                $valor = round($valor, 0);

                $tipoIdCliente = $contrato->getCliente()->getDni()->getTipo();
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

    private function addToReport(string $fileName, Contrato $contrato, string $data){

    }
}
