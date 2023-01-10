<?php

namespace App\Controller;

use App\Entity\Cliente;
use App\Entity\Contrato;
use App\Entity\Equipo;
use App\Entity\EquipoInstalacion;
use App\Entity\EstadoContrato;
use App\Entity\OpcionCatalogo;
use App\Form\ContratoType;
use App\Repository\ClienteRepository;
use App\Repository\ContratoRepository;
use App\Repository\OpcionCatalogoRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("contrato")
 */
class ContratoController extends AbstractController
{
    /**
     * @Route("/", name="contrato_index", methods={"GET"})
     */
    public function index(ContratoRepository $ContratoRepository, SerializerInterface $serializer): Response
    {
        $contratos = $ContratoRepository->findAllRegisters();
//        $campos = ['id', 'numero', 'fecha', 'direccion'];
        $data = $serializer->normalize($contratos, null, [AbstractNormalizer::ATTRIBUTES=>[
            'id','nombres', 'numero', 'cedula','vlan','nodo', 'pppoe','fecha', 'direccion','estadoActual'=>['codigo','texto','cssClass']
        ]]);
        return $this->render('Contrato/index.html.twig', [
            'contratos' => $data,
            //'campos' => $campos
        ]);
    }

    /**
     * @Route("/new", name="contrato_new", methods={"GET","POST"})
     */
    public function new(Request $request, OpcionCatalogoRepository $opcionCatalogoRepository): Response
    {
        $Contrato = new Contrato();
        $Contrato->setFecha(new \DateTime());
        $form = $this->createForm(ContratoType::class, $Contrato);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $clientRepository = $entityManager->getRepository(Cliente::class);

            $dni = $Contrato->getCliente()->getDni()->getNumero();
            /**
             * @var $oldClient Cliente|null
             */
            $oldClient = $clientRepository->findOneByNumeroDni($dni);
            if($oldClient){
                $Contrato->setCliente($oldClient);
            }
            $equipos = json_decode($form['equiposjson']->getData());
            if($equipos && count($equipos) > 0){
                foreach ($equipos as $e){
                    $equipoInstalacion = new EquipoInstalacion();
                    $equipo = $entityManager->getRepository(Equipo::class)->find($e->id);
                    $equipoInstalacion->setEquipo($equipo);
                    if($e->esSeriado)
                        $equipoInstalacion->setSerie($e->serial);
                    $equipoInstalacion->setCantidad($e->cantidad);
                    $equipoInstalacion->setContrato($Contrato);
                    $Contrato->addEquipo($equipoInstalacion);
                }
            }

            //$estado = new EstadoContrato();
            //$estado->setFecha(new \DateTime());
            //$estado->setObservaciones('eestado inicial');
            //$estadoOpcion = $opcionCatalogoRepository->findOneByCodigoyCatalogo(EstadoContrato::ACTIVO, 'est-cont');
            //$estado->setEstado($estadoOpcion);
            //$Contrato->setEstado($estado);
            //$Contrato->addEstado($estado);
            //$estado->setContrato($Contrato);
            $entityManager->persist($Contrato);
            $entityManager->flush();

            return $this->redirectToRoute('contrato_index');
        }

        return $this->render('Contrato/new.html.twig', [
            'contrato' => $Contrato,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="contrato_show", methods={"GET"})
     */
    public function show(Contrato $Contrato): Response
    {
        return $this->render('Contrato/show.html.twig', [
            'contrato' => $Contrato,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="contrato_edit", methods={"GET","POST"})
     */
    public function edit(
        Request $request,
        Contrato $Contrato,
        SerializerInterface $serializer,
        ClienteRepository $clienteRepository,
        EntityManagerInterface $em): Response
    {
        $clienteOriginal = $Contrato->getCliente();
        dump($clienteOriginal);
        $form = $this->createForm(ContratoType::class, $Contrato);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $Contrato->setVersion($Contrato->getVersion()+1);
            $cliente = $Contrato->getCliente();
            $dni = $cliente->getDni()->getNumero();
            $dniOriginal = $clienteOriginal->getDni()->getNumero();
            dump($dni);
            dump($dniOriginal);
            if($dni != $dniOriginal){
                $oldClient = $clienteRepository->findOneByNumeroDni($dni);
                dump('DNI MODIFICADO');
                if($oldClient){
                    dump('CLIENTE EXISTENTE');
                    $Contrato->setCliente($oldClient);
                }
                $cliente->setId(null);
            }

            dump($cliente);
            $Contrato->setFechaActualizacion(new \DateTime());
            $Contrato->getActualizadoPor($this->getUser());
            $em->flush();
            //return $this->redirectToRoute('contrato_index');
        }

        return $this->render('Contrato/edit.html.twig', [
            'contrato' => $Contrato,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="contrato_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Contrato $Contrato): Response
    {
        if ($this->isCsrfTokenValid('delete'.$Contrato->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($Contrato);
            $entityManager->flush();
        }

        return $this->redirectToRoute('contrato_index');
    }

    /**
     * @Route("/buscarContrato", name="buscar_Contrato", methods={"POST"})
     */
    public function buscarContrato
    (
        Request $request,
        ContratoRepository $contratoRepository,
        OpcionCatalogoRepository $opcionCatalogoRepository
    ): Response
    {
        $content = json_decode($request->getContent());
        $param = $content->param;
        //$html = '<tr><td colspan="4">No se encontraron datos</td></tr>';
        $data = [];
        if($param){

            $Contratos = $contratoRepository->findByParam($param);
            ////dump($parroquias);
            /* @var $serializer Serializer */
            $serializer = $this->get('serializer');
            $data['contratos'] = $serializer->normalize($Contratos, null, [AbstractNormalizer::ATTRIBUTES=>
                [
                    'id',
                    'numero',
                    'cliente'=>[
                        'id',
                        'nombres',
                        'dni'=>[
                            'numero'
                        ]
                    ],
                    'plan'=>[
                        'id',
                        'codigo',
                        'nombre',
                        'precio',
                        'incluyeIva',
                        'codigoPorcentaje'
                    ],
                    'mesPago',
                    'anioPago',
                    'estadoActual'=>[
                        'estado'=>[
                            'codigo',
                            'texto'
                        ]
                    ],
                    'necesitaReconexion'
                ]
            ]);
            $cache = [];
            $counter = 0;
            $setPorcentaje = function ($contrato) use ($opcionCatalogoRepository, &$cache, &$counter){
                $codigo = $contrato['plan']['codigoPorcentaje'];
                $impuesto = null;
                if(isset($cache["$codigo"])){
                    $impuesto = $cache["$codigo"];
                }else{
                    $impuesto = $opcionCatalogoRepository->findOneByCodigoyCatalogo($codigo, 'iva');
                    $cache["$codigo"] = $impuesto;
                    ////dump("query");
                    $counter ++;
                }
                $contrato['plan']['porcentaje'] = $impuesto->getValorNumerico();
                return$contrato;
            };
            $data["contratos"] = array_map($setPorcentaje, $data["contratos"]);
            dump($counter);
            //$html = $this->renderView('Contrato/Contratos.html.twig',['Contratos'=>$data]);
        }
        return new JsonResponse($data);
    }


    /**
     * @Route("/generar/cortes", name="contrato_corte", methods={"GET"})
     */
    public function generarCortes(
        ContratoRepository $contratoRepository,
        OpcionCatalogoRepository $opcionCatalogoRepository,
        EntityManagerInterface $em
    ): Response
    {
        error_reporting(E_ALL & ~E_NOTICE);
        $fecha = new \DateTime();
        $anio = (int)$fecha->format('Y');
        $mes = (int)$fecha->format('m');
        $contratoRepository->generarCorte($anio, $mes);
        return $this->redirectToRoute('contrato_index');
    }
    /**
     * @Route("/generar/activaciones", name="contrato_activacion", methods={"GET"})
     */
    public function generarActivacion(
        ContratoRepository $contratoRepository
    ): Response
    {
        error_reporting(E_ALL & ~E_NOTICE);
        $fecha = new \DateTime();
        $anio = (int)$fecha->format('Y');
        $mes = (int)$fecha->format('m');
        $contratoRepository->generarActivacion($anio, $mes);
        return $this->redirectToRoute('contrato_index');
    }
    /**
     * @Route("/marcar/inpagos", name="contrato_inpagos", methods={"GET"})
     */
    public function marcarInpagos(
        ContratoRepository $contratoRepository,
        OpcionCatalogoRepository $opcionCatalogoRepository,
        EntityManagerInterface $em
    ): Response
    {
        $contratos = $contratoRepository->findBy(['estado'=>EstadoContrato::ACTIVO]);
        /* @var $contratos Contrato*/
        foreach ($contratos as $contrato){
            $contrato->setEstado(EstadoContrato::INPAGO);
            $opcion = $opcionCatalogoRepository
                ->findOneByCodigoyCatalogo(EstadoContrato::INPAGO, EstadoContrato::NOMBRE_CATALOGO);
            $estado = new EstadoContrato();
            $estado->setEstado($opcion);
            $estado->setFecha(new \DateTime());
            $estado->setContrato($contrato);
            $contrato->addEstado($estado);
        }
        $em->flush();
        return $this->redirectToRoute('contrato_index');
    }
    /*
    public function generarActivacion(Contrato $contrato, EntityManagerInterface $em, OpcionCatalogoRepository $opcionCatalogoRepository): Response
    {
        $contrato->setEstado(EstadoContrato::ACTIVO);
        $opcion = $opcionCatalogoRepository->findOneByCodigoyCatalogo(EstadoContrato::ACTIVO, EstadoContrato::NOMBRE_CATALOGO);
        //dump($opcion);
        $estado = new EstadoContrato();
        $estado->setEstado($opcion);
        $estado->setFecha(new \DateTime());
        $estado->setContrato($contrato);
        $contrato->addEstado($estado);
        $em->flush();
        return $this->redirectToRoute('contrato_index');
    }
    */
}
