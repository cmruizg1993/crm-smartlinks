<?php

namespace App\Controller;

use App\Entity\Cliente;
use App\Entity\Empresa;
use App\Entity\Contrato;
use App\Entity\DetalleFactura;
use App\Entity\Factura;
use App\Entity\OpcionCatalogo;
use App\Entity\Producto;
use App\Entity\PuntoEmision;
use App\Entity\Servicio;
use App\Entity\Usuario;
use App\Form\FacturaType;
use App\Repository\ClienteRepository;
use App\Repository\EmpresaRepository;
use App\Repository\ContratoRepository;
use App\Repository\FacturaRepository;
use App\Repository\OpcionCatalogoRepository;
use App\Repository\PuntoEmisionRepository;
use App\Repository\ServicioRepository;
use App\Repository\UsuarioRepository;
use App\Service\FacturacionElectronica;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use http\Client\Curl\User;
use Picqer\Barcode\BarcodeGeneratorDynamicHTML;
use Picqer\Barcode\BarcodeGeneratorHTML;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/factura")
 */
class FacturaController extends AbstractController
{
    /**
     * @Route("/index/{page}/{pageLength}", name="factura_index", methods={"GET", "POST"})
     */
    public function index(Request $request, $page = 1, $pageLength = 10, FacturaRepository $facturaRepository, SerializerInterface $serializer): Response
    {
        $form = $this->createFormBuilder([])
            ->add('desde', DateType::class,['attr'=>['required'=>'required'], 'widget' => 'single_text'])
            ->add('hasta', DateType::class,['attr'=>['required'=>'required'], 'widget' => 'single_text'])
            ->getForm();
        $form->handleRequest($request);
        $data = [];
        if($form->isSubmitted() && $form->isValid()){
            $desde = $form['desde']->getData();
            $hasta = $form['hasta']->getData();
            $facturas = $facturaRepository->getRangeOfDate($desde, $hasta);
            $data = $serializer->normalize($facturas, null, [AbstractNormalizer::ATTRIBUTES=>[
                'id', 'numero', 'nombres', 'cedula', 'serie', 'secuencial', 'strFecha', 'total', 'subtotal', 'subtotal12', 'iva', 'estadoSri', 'mensajeSri','formaPago'
            ]]);
        }
        return $this->render('factura/index.html.twig', [
            'facturas' => $data,
            'form'=>$form->createView()
        ]);
    }
    /**
     * @Route("/autorizacion/sri/{id}", name="factura_autorizacion", methods={"PUT"})
     */
    public function autorizacion
    (
        $id = '',
        FacturaRepository $facturaRepository ,
        FacturacionElectronica $facturacionElectronica,
        EntityManagerInterface $em
    ): Response
    {
        if(!$id) return new Response('Se necesita el Id de la factura',400);
        $factura = $facturaRepository->find($id);
        if(!$factura) return new Response('Factura no encontrada',400);
        $clave = $factura->getClaveAcceso();
        $testing = $factura->getTipoAmbiente() == '1';
        $result = $facturacionElectronica->autorizacion($clave, $testing);
        $respuesta = isset($result->RespuestaAutorizacionComprobante) ? $result->RespuestaAutorizacionComprobante: null;
        $autorizaciones = isset($respuesta->autorizaciones) ? $respuesta->autorizaciones: null;
        dump($result);
        $autorizacion = $autorizaciones ? $autorizaciones->autorizacion: null;
        $estado = $autorizacion && isset($autorizacion->estado)?$autorizacion->estado:null;
        $factura->setEstadoSri($estado);
        $em->flush();
        return new JsonResponse(['estado'=>$estado], 200);
    }
    /**
     * @Route("/envio/mail/{id}", name="factura_envio", methods={"PUT"})
     */
    public function enviarMailFactura
    (
        $id = '',
        FacturaRepository $facturaRepository ,
        EntityManagerInterface $em,
        MailerInterface $mailer,
        FacturacionElectronica $facturacionElectronica,
        OpcionCatalogoRepository $opcionCatalogoRepository
    ): Response
    {
        if(!$id) return new Response('Se necesita el Id de la factura',400);
        $factura = $facturaRepository->find($id);
        if(!$factura) return new Response('Factura no encontrada',400);
        $clave = $factura->getClaveAcceso();
        $clienteEmail = $factura->getCliente()->getEmail();
        $empresa = $factura->getUsuario()->getEmpresa();
        $generator = new BarcodeGeneratorPNG();
        $codigoBarras = $generator->getBarcode($clave, $generator::TYPE_CODE_128, 4, 50);
        $codigoBarras64 = base64_encode($codigoBarras);
        $fpago = $opcionCatalogoRepository->findOneByCodigoyCatalogo($factura->getFormaPago(), 'f-pagos');
        $html = $this->renderView('pdf/ride.html.twig',
            ['empresa'=> $empresa, 'factura'=>$factura, 'codigoBarras'=>$codigoBarras64, 'formaPago'=>$fpago]);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4');
        $dompdf->render();
        $output = $dompdf->output();
        $pdfName = $facturacionElectronica->obtenerPathPdf($clave);
        file_put_contents("$pdfName", $output);

        $email = (new Email())
            ->from('smartlinks@gmail.com')
            ->to($clienteEmail)
            ->subject('Facturación Electrónica Smartlinks')
            ->text("Adjuntamos los datos de tu factura.")
            ->attachFromPath($facturacionElectronica->obtenerPathXml($clave))
            ->attachFromPath($pdfName);

        $mailer->send($email);
        $factura->setEstadoSri($factura->getEstadoSri().','.'ENVIADA');
        $estado = $factura->getEstadoSri();
        $em->flush();
        return new JsonResponse(['success'=>true, 'estado'=>$estado], 200);
    }

    /**
     * @Route("/recepcion/sri/{id}", name="factura_recepcion", methods={"PUT"})
     */
    public function recepcion
    (
        $id = '',
        FacturaRepository $facturaRepository ,
        FacturacionElectronica $facturacionElectronica,
        EntityManagerInterface $em
    ): Response
    {
        if(!$id) return new Response('Se necesita el Id de la factura',400);
        $factura = $facturaRepository->find($id);
        if(!$factura) return new Response('Factura no encontrada',400);
        $clave = $factura->getClaveAcceso();
        $result = $facturacionElectronica->recepcion($clave);
        dump($result);
        $respuesta = isset($result->RespuestaRecepcionComprobante) ? $result->RespuestaRecepcionComprobante: null;
        $estado = $respuesta && isset($respuesta->estado) ? $respuesta->estado: null;
        $factura->setEstadoSri($estado);
        $em->flush();
        return new JsonResponse(['estado'=>$estado], 200);

    }

    /**
     * @Route("/anular/sistema/{id}", name="factura_anulacion", methods={"PUT"})
     */
    public function anular
    (
        $id = '',
        FacturaRepository $facturaRepository ,
        FacturacionElectronica $facturacionElectronica,
        EntityManagerInterface $em
    ): Response
    {
        if(!$id) return new Response('Se necesita el Id de la factura',400);
        $factura = $facturaRepository->find($id);
        if(!$factura) return new Response('Factura no encontrada',400);
        $estado = 'ANULADA';
        $factura->setEstadoSri($estado);
        $em->flush();
        return new JsonResponse(['estado'=>$estado], 200);

    }

    /**
     * @Route("/new", name="factura_new", methods={"GET","POST"})
     */
    public function new(
        Request $request,
        ContratoRepository $contratoRepository,
        PuntoEmisionRepository $puntoEmisionRepository,
        FacturaRepository $facturaRepository,
        ClienteRepository $clienteRepository,
        ServicioRepository $servicioRepository,
        OpcionCatalogoRepository $opcionCatalogoRepository,
        EmpresaRepository $empresaRepository,
        EntityManagerInterface $em,
        SerializerInterface $serializer,
        FacturacionElectronica $facturacionElectronica,
        UsuarioRepository $usuarioRepository
    ): Response
    {
        $method = $request->getMethod();
        if($method == Request::METHOD_POST){

            $content = json_decode($request->getContent(), true);
            $factura = new Factura();
            /* @var $factura Factura */
            $form = $this->createForm(FacturaType::class, $factura);
            //$content["fecha"]=explode("-", $content["fecha"]);
            $form->submit($content);
            $secuencial = (int)$factura->getSecuencial() . '';
            while (strlen($secuencial)<9){
                $secuencial = '0' . $secuencial;
            }
            $factura->setSecuencial($secuencial);
            //$puntoEmision =
            /* ENLAZANDO FACTURA AL CONTRATO */
            $contrato = $factura->getContrato();
            if(!$contrato || !$contrato->getId()) return new Response('contrato', 400);
            $contrato = $contratoRepository->findOneBy(['id'=>$contrato->getId()],['version'=>'DESC']);
            if(!$contrato) return new Response('contrato 2', 400);
            $contrato->addFactura($factura);
            $factura->setContrato($contrato);

            /* PUNTO DE EMISION */
            $puntoEmision = $factura->getPuntoEmision();
            if(!$puntoEmision || !$puntoEmision->getId()) return new Response('pto 2', 400);
            $puntoEmision = $puntoEmisionRepository->find($puntoEmision->getId());
            if(!$puntoEmision) return new Response('punto emision', 400);
            $tipo = $puntoEmision->getTipoComprobante()->getCodigo();
            if($tipo != Factura::FACTURA && $tipo != Factura::NOTA_VENTA)return new Response('tipo comp', 400);
            $factura->setPuntoEmision($puntoEmision);

            /* SECUENCIAL */
            $secuencial = $factura->getSecuencial();
            $exist = $facturaRepository->findOneBy(['secuencial'=>$secuencial, 'puntoEmision'=>$puntoEmision]);
            if($exist) return new Response('La factura ya existe', 400);

            /* CLIENTE */
            $cliente = $factura->getCliente();
            if(!$cliente || !$cliente->getId()) return new Response('cliente', 400);
            $cliente = $clienteRepository->find($cliente->getId());
            if(!$cliente) return new Response('cliente 2', 400);
            $cliente->addFactura($factura);
            $factura->setCliente($cliente);
            $factura->totalizar($opcionCatalogoRepository);
            $factura->setUsuario($this->getUser());
            $user = $this->getUser();
            /* @var $usuario Usuario */
            $usuario = $usuarioRepository->findOneBy(['email'=>$user->getEmail()]);
            $empresa = $usuario->getEmpresa();
            if(!$empresa) return new Response('Empresa no válida', 400);
            if($factura->getTipoComprobante() == Factura::NOTA_VENTA){
                $em->persist($factura);
                $em->flush();
                return new JsonResponse(['id'=>$factura->getId()], 200);
            }
            $factura->ruc = $empresa->getRuc();
            $factura->generarClaveAcceso();

            $xml = $this->renderView('xml/factura.pruebas.xml.twig',['factura'=>$factura, 'conf'=>$empresa]);
            $clave = $factura->getClaveAcceso();
            $fileName = $facturacionElectronica->crearArchivoXml($clave, $xml);
            $output = $facturacionElectronica->firmarArchivoXml($clave, $empresa);
            if($output == 0){
                $testing = $factura->getTipoAmbiente() == '1';
                //dump($testing);
                $result = $facturacionElectronica->recepcion($clave, $testing);
                //dump($result);
                $respuesta = isset($result->RespuestaRecepcionComprobante) ? $result->RespuestaRecepcionComprobante: null;
                $estado = null;
                if($respuesta && isset($respuesta->estado)){
                    $estado = $respuesta->estado;
                }
                if($estado!=null && $estado != Factura::ESTADO_RECIBIDA){
                    $mensajes = $respuesta->comprobantes->comprobante->mensajes;
                    $jsonMensajes = json_encode($mensajes);
                    $factura->setMensajeSri($jsonMensajes);
                }
                $factura->setEstadoSri($estado);
            }
            $em->persist($factura);
            $em->flush();
            return new JsonResponse(['id'=>$factura->getId()], 200);
        }

        $codigos = ['ambiente', 'f-pagos', 'tipo-comp'];
        $catalogos = $opcionCatalogoRepository->findByCodigoCatalogo($codigos);
        $data = $serializer->normalize($catalogos, 'json', [AbstractNormalizer::ATTRIBUTES=>
            [ 'id', 'codigo', 'nombre', 'catalogo'=>[ 'codigo', 'texto']]
        ]);
        $factura = new Factura();
        $form = $this->createForm(FacturaType::class, $factura);
        return $this->render('factura/new.html.twig', [
            'factura' => $factura,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="factura_show", methods={"GET"})
     */
    public function show(Factura $factura, OpcionCatalogoRepository $opcionCatalogoRepository): Response
    {
        $fpago = $opcionCatalogoRepository->findOneByCodigoyCatalogo($factura->getFormaPago(), 'f-pagos');
        $empresa = $factura->getUsuario()->getEmpresa();
        $generator = new BarcodeGeneratorPNG();
        $codigoBarras = base64_encode($generator->getBarcode($factura->getClaveAcceso(), $generator::TYPE_CODE_128));
        return $this->render('printer/factura.html.twig', [
            'factura' => $factura, 'empresa'=>$empresa, 'formaPago'=>$fpago, 'codigoBarras'=>$codigoBarras
        ]);
    }
    /**
     * @Route("/{id}/descargar", name="factura_download", methods={"GET"})
     */
    public function descargar
    (
        $id = '',
        FacturaRepository $facturaRepository,
        OpcionCatalogoRepository $opcionCatalogoRepository,
        FacturacionElectronica $facturacionElectronica
    ): Response
    {
        if(!$id) return new Response('Se necesita el Id de la factura',400);
        $factura = $facturaRepository->find($id);
        if(!$factura) return new Response('Factura no encontrada',400);
        $clave = $factura->getClaveAcceso();
        $clienteEmail = $factura->getCliente()->getEmail();
        $empresa = $factura->getUsuario()->getEmpresa();
        $generator = new BarcodeGeneratorPNG();
        $codigoBarras = $generator->getBarcode($clave, $generator::TYPE_CODE_128, 4, 50);
        $codigoBarras64 = base64_encode($codigoBarras);
        $fpago = $opcionCatalogoRepository->findOneByCodigoyCatalogo($factura->getFormaPago(), 'f-pagos');
        $html = $this->renderView('pdf/ride.html.twig',
            ['empresa'=> $empresa, 'factura'=>$factura, 'codigoBarras'=>$codigoBarras64, 'formaPago'=>$fpago]);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4');
        $dompdf->render();
        $output = $dompdf->output();
        $pdfName = "fac-$clave.pdf";
        $tmpFileName = (new Filesystem())->tempnam(sys_get_temp_dir(), 'ride_', '.pdf');
        file_put_contents("$tmpFileName", $output);

        return (new BinaryFileResponse($tmpFileName, Response::HTTP_OK))
            ->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $pdfName);

        // That's it! 😁
    }
    /**
     * @Route("/secuencial/{punto_emision_id}", name="factura_secuencial", methods={"GET"})
     */
    public function obtenerSecuencial($punto_emision_id = 0, FacturaRepository $facturaRepository): Response
    {
        $secuencial = $facturaRepository->obtenerSecuencial($punto_emision_id).'';
        while (strlen($secuencial)<9)$secuencial='0'.$secuencial;
        $data['secuencial']=$secuencial;
        return new JsonResponse($data);
    }

    /**
     * @Route("/{id}/edit", name="factura_edit", methods={"GET","POST"})
     */
    public function edit
    (
        Request $request,
        Factura $factura,
        ContratoRepository $contratoRepository,
        PuntoEmisionRepository $puntoEmisionRepository,
        FacturaRepository $facturaRepository,
        ClienteRepository $clienteRepository,
        ServicioRepository $servicioRepository,
        OpcionCatalogoRepository $opcionCatalogoRepository,
        EmpresaRepository $empresaRepository,
        EntityManagerInterface $em,
        SerializerInterface $serializer,
        FacturacionElectronica $facturacionElectronica,
        UsuarioRepository $usuarioRepository

    ): Response
    {
        $form = $this->createForm(FacturaType::class, $factura);
        $form->handleRequest($request);
        $estado = $factura->getEstadoSri();
        if ($request->getMethod() == Request::METHOD_POST && (!str_contains($estado, "AUTORIZADO") || str_contains($estado, "NO AUTORIZADO"))) {
            $content = json_decode($request->getContent(), true);
            $form->submit($content);
            $factura->setUsuario($this->getUser());
            $user = $this->getUser();
            /* @var $usuario Usuario */
            $usuario = $usuarioRepository->findOneBy(['email'=>$user->getEmail()]);
            if($factura->getTipoComprobante() == Factura::NOTA_VENTA){
                $em->flush();
                return new JsonResponse(['id'=>$factura->getId()], 200);
            }
            $empresa = $usuario->getEmpresa();
            if(!$empresa) return new Response('Empresa no válida', 400);
            $factura->ruc = $empresa->getRuc();
            $factura->generarClaveAcceso();

            $xml = $this->renderView('xml/factura.pruebas.xml.twig',['factura'=>$factura, 'conf'=>$empresa]);
            $clave = $factura->getClaveAcceso();
            $facturacionElectronica->crearArchivoXml($clave, $xml);
            $output = $facturacionElectronica->firmarArchivoXml($clave, $empresa);
            $factura->totalizar($opcionCatalogoRepository);
            if($output == 0){
                $testing = $factura->getTipoAmbiente() == '1';
                //dump($testing);
                $result = $facturacionElectronica->recepcion($clave, $testing);
                //dump($result);
                $respuesta = isset($result->RespuestaRecepcionComprobante) ? $result->RespuestaRecepcionComprobante: null;
                $estado = null;
                if($respuesta && isset($respuesta->estado)){
                    $estado = $respuesta->estado;
                }
                if($estado!=null && $estado != Factura::ESTADO_RECIBIDA){
                    $mensajes = $respuesta->comprobantes->comprobante->mensajes;
                    $jsonMensajes = json_encode($mensajes);
                    $factura->setMensajeSri($jsonMensajes);
                }
                $factura->setEstadoSri($estado);

            }

            $em->persist($factura);
            $em->flush();
        }

        $data = [];
        $data['factura'] = $serializer->normalize($factura, null, [AbstractNormalizer::ATTRIBUTES=>[
            'id','claveAcceso', 'serial', 'tipoAmbiente','mesPago', 'anioPago','formaPago','comprobantePago',
            'serie', 'secuencial', 'fecha', 'total', 'subtotal', 'subtotal12', 'iva', 'subtotal0', 'estadoSri','tipoComprobante', 'mensajeSri'
            ]
        ]);
        $data['cliente'] = $serializer->normalize($factura->getCliente(), null, [AbstractNormalizer::ATTRIBUTES=>['id', 'nombres', 'dni'=>['numero']]]);

        $data['contrato'] = $serializer->normalize($factura->getContrato(), null, [AbstractNormalizer::ATTRIBUTES=>['id', 'numero']]);
        $data['puntoEmision'] = $serializer->normalize($factura->getPuntoEmision(), null, [AbstractNormalizer::ATTRIBUTES=>['id', 'codigo', 'establecimiento'=>['codigo']]]);
        $data['detalles'] = $serializer->normalize($factura->getDetalles(), null, [AbstractNormalizer::ATTRIBUTES=>[
            'id','descripcion', 'precio', 'cantidad', 'subtotal', 'descuento', 'idServicio', 'esServicio', 'servicio'=>['id', 'codigo', 'precio', 'codigoPorcentaje', 'incluyeIva']
        ]]);
        $setPorcentaje = function ($detalle) use ($em){
            $codigo = $detalle['servicio']['codigoPorcentaje'];
            $impuesto = $em->getRepository(OpcionCatalogo::class)->findOneByCodigoyCatalogo($codigo, 'iva');
            $detalle['servicio']['porcentaje'] = $impuesto->getValorNumerico();
            return$detalle;
        };
        $data['detalles'] = array_map($setPorcentaje, $data['detalles']);

        return $this->render('factura/edit.html.twig', [
            'factura' => $data,
            'form' => $form->createView(),
        ]);
    }

    public function generarFactura
    (
        Factura $facturaOriginal,
        Factura $factura,
        ContratoRepository $contratoRepository,
        PuntoEmisionRepository $puntoEmisionRepository,
        FacturaRepository $facturaRepository,
        ClienteRepository $clienteRepository,
        ServicioRepository $servicioRepository,
        OpcionCatalogoRepository $opcionCatalogoRepository,
        UsuarioRepository $usuarioRepository,
        FacturacionElectronica $facturacionElectronica,
        EntityManagerInterface $em

    ){
        $dets = $facturaOriginal->getDetalles();
        foreach ($dets as $det){
            $facturaOriginal->removeDetalle($det);
        }
        $em->flush();
        //return;
        /* ENLAZANDO FACTURA AL CONTRATO */
        $contrato = $factura->getContrato();
        if(!$contrato || !$contrato->getId()) return new Response('contrato', 400);
        $contrato = $contratoRepository->findOneBy(['id'=>$contrato->getId()],['version'=>'DESC']);
        if(!$contrato) return new Response('contrato 2', 400);
        $contrato->addFactura($facturaOriginal);
        $facturaOriginal->setContrato($contrato);

        /* PUNTO DE EMISION */
        $puntoEmision = $factura->getPuntoEmision();
        if(!$puntoEmision || !$puntoEmision->getId()) return new Response('pto 2', 400);
        $puntoEmision = $puntoEmisionRepository->find($puntoEmision->getId());
        if(!$puntoEmision) return new Response('punto emision', 400);
        $tipo = $puntoEmision->getTipoComprobante()->getCodigo();
        if($tipo != Factura::FACTURA && $tipo != Factura::NOTA_VENTA)return new Response('tipo comp', 400);
        $facturaOriginal->setPuntoEmision($puntoEmision);

        /* SECUENCIAL */
        $secuencial = $factura->getSecuencial();
        $exist = $facturaRepository->findOneBy(['secuencial'=>$secuencial, 'puntoEmision'=>$puntoEmision]);
        if($exist) return new Response('La factura ya existe', 400);
        $facturaOriginal->setSecuencial($secuencial);

        /* CLIENTE */
        $cliente = $factura->getCliente();
        if(!$cliente || !$cliente->getId()) return new Response('cliente', 400);
        $cliente = $clienteRepository->find($cliente->getId());
        if(!$cliente) return new Response('cliente 2', 400);
        $cliente->addFactura($facturaOriginal);
        $facturaOriginal->setCliente($cliente);
        /* AGREGANDO DETALLES Y CALCULANDO TOTALES */
        $total= 0;
        $subtotal = 0;
        $subtotal12 = 0;
        $subtotal0 = 0;
        $subtotalNOI = 0;
        $iva = 0;
        $detalles = $factura->getDetalles();
        /* @var $detalle DetalleFactura */
        foreach ($detalles as $detalle){
            if($detalle->getEsServicio()){
                $servicio = $detalle->getServicio();
                if(!$servicio || !$servicio->getId()) return new Response('servicio', 400);
                /* @var $servicio Servicio */
                $servicio = $servicioRepository->find($servicio->getId());
                if(!$servicio) return new Response('servicio', 400);
                $detalle->setServicio( $servicio );

                /* @var $impuesto OpcionCatalogo */
                $impuesto = $opcionCatalogoRepository->findOneByCodigoyCatalogo($servicio->getCodigoPorcentaje(), 'iva');
                $totalDetalle = ($detalle->getCantidad() * $detalle->getPrecio());
                $porcentaje = $impuesto->getValorNumerico()/100;
                $ivaDetalle = null;
                if($servicio->getIncluyeIva()){
                    $subtotalDetalle = $totalDetalle/(1 + $porcentaje);
                    $ivaDetalle = $totalDetalle - $subtotalDetalle;
                }else{
                    $subtotalDetalle = $totalDetalle;
                    $totalDetalle = $subtotalDetalle*(1 + $porcentaje);
                    $ivaDetalle = $totalDetalle - $subtotalDetalle;
                }
                if($ivaDetalle > 0){
                    $subtotal12 += $subtotalDetalle;

                }else{
                    $subtotal0 += $subtotalDetalle;
                }
                $subtotal += $subtotalDetalle;

                $detalle->setSubtotal($subtotalDetalle);
                $total += $totalDetalle;
                $iva += $ivaDetalle;
            }else{
                // por completar

            }
            $detalle->setFactura($facturaOriginal);
            //$factura->addDetalle($detalle);
        }
        $facturaOriginal->setTotal($total);
        $facturaOriginal->setSubtotal($subtotal);
        $facturaOriginal->setIva($iva);
        //$factura->setBaseIva($baseIva);
        $facturaOriginal->setSubtotal0($subtotal0);
        $facturaOriginal->setSubtotal12($subtotal12);
        $facturaOriginal->setUsuario($this->getUser());
        $user = $this->getUser();
        /* @var $usuario Usuario */
        $usuario = $usuarioRepository->findOneBy(['email'=>$user->getEmail()]);
        $empresa = $usuario->getEmpresa();
        if(!$empresa) return new Response('Empresa no válida', 400);
        $facturaOriginal->ruc = $empresa->getRuc();
        $facturaOriginal->generarClaveAcceso();

        $xml = $this->renderView('xml/factura.pruebas.xml.twig',['factura'=>$factura, 'conf'=>$empresa]);
        $clave = $facturaOriginal->getClaveAcceso();
        $facturacionElectronica->crearArchivoXml($clave, $xml);
        $output = $facturacionElectronica->firmarArchivoXml($clave, $empresa);
        if($output == 0){
            $result = $facturacionElectronica->recepcion($clave);
            $respuesta = isset($result->RespuestaRecepcionComprobante) ? $result->RespuestaRecepcionComprobante: null;
            $estado = $respuesta && isset($respuesta->estado) ? $respuesta->estado: null;
            $facturaOriginal->setEstadoSri($estado);
        }
        $em->flush();

    }
}
