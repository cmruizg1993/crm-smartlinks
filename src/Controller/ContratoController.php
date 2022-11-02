<?php

namespace App\Controller;

use App\Entity\Cliente;
use App\Entity\Contrato;
use App\Entity\Equipo;
use App\Entity\EquipoInstalacion;
use App\Entity\EstadoContrato;
use App\Entity\OpcionCatalogo;
use App\Form\ContratoType;
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
    public function index(ContratoRepository $ContratoRepository): Response
    {
        return $this->render('Contrato/index.html.twig', [
            'contratos' => $ContratoRepository->findAllRegisters(),
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

            $estado = new EstadoContrato();
            $estado->setFecha(new \DateTime());
            $estado->setObservaciones('eestado inicial');
            $estadoOpcion = $opcionCatalogoRepository->findOneByCodigoyCatalogo(EstadoContrato::ACTIVO, 'est-cont');
            $estado->setEstado($estadoOpcion);
            //$Contrato->setEstado($estado);
            $Contrato->addEstado($estado);
            $estado->setContrato($Contrato);
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
        EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ContratoType::class, $Contrato);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $new = clone $Contrato;
            $new->setVersion($Contrato->getVersion()+1);
            unset($Contrato);
            $new->setId(null);
            $em->persist($new);
            $em->flush();
            return $this->redirectToRoute('contrato_index');
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
    public function buscarContrato(Request $request): Response
    {
        $content = json_decode($request->getContent());
        $param = $content->param;
        //$html = '<tr><td colspan="4">No se encontraron datos</td></tr>';
        $data = [];
        if($param){
            $em =$this->getDoctrine()->getManager();
            $Contratos = $em->getRepository(Contrato::class)->findByParam($param);
            //dump($parroquias);
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

            $setPorcentaje = function ($contrato) use ($em){
                $codigo = $contrato['plan']['codigoPorcentaje'];
                $impuesto = $em->getRepository(OpcionCatalogo::class)->findOneByCodigoyCatalogo($codigo, 'iva');
                $contrato['plan']['porcentaje'] = $impuesto->getValorNumerico();
                return$contrato;
            };
            $data["contratos"] = array_map($setPorcentaje, $data["contratos"]);
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
        $contratos = $contratoRepository->findBy(['estado'=>EstadoContrato::INPAGO]);
        /* @var $contratos Contrato*/
        foreach ($contratos as $contrato){
            $contrato->setEstado(EstadoContrato::CORTADO);
            $opcion = $opcionCatalogoRepository
                ->findOneByCodigoyCatalogo(EstadoContrato::CORTADO, EstadoContrato::NOMBRE_CATALOGO);
            $estado = new EstadoContrato();
            $estado->setEstado($opcion);
            $estado->setFecha(new \DateTime());
            $estado->setContrato($contrato);
            $contrato->addEstado($estado);
        }
        $em->flush();
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
    /**
     * @Route("/generar/activacion/{id}", name="contrato_activar", methods={"GET"})
     */
    public function generarActivacion(Contrato $contrato, EntityManagerInterface $em, OpcionCatalogoRepository $opcionCatalogoRepository): Response
    {
        $contrato->setEstado(EstadoContrato::ACTIVO);
        $opcion = $opcionCatalogoRepository->findOneByCodigoyCatalogo(EstadoContrato::ACTIVO, EstadoContrato::NOMBRE_CATALOGO);
        dump($opcion);
        $estado = new EstadoContrato();
        $estado->setEstado($opcion);
        $estado->setFecha(new \DateTime());
        $estado->setContrato($contrato);
        $contrato->addEstado($estado);
        $em->flush();
        return $this->redirectToRoute('contrato_index');
    }
}
