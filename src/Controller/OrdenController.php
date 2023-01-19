<?php

namespace App\Controller;

use App\Entity\Evento;
use App\Entity\Orden;
use App\Form\OrdenType;
use App\Repository\OrdenRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/orden")
 */
class OrdenController extends AbstractController
{
    /**
     * @Route("/", name="orden_index", methods={"GET"})
     */
    public function index(Request $request , OrdenRepository $ordenRepository, SerializerInterface $serializer): Response
    {
        $page = $request->get('page') ? $request->get('page')-1: 0;
        $offset = $page*25;
        $ordenes = $ordenRepository->findAll();

        $data = $serializer->normalize($ordenes, null, [AbstractNormalizer::ATTRIBUTES=>
            [
                'id',
                'tecnico'=> [ 'nombres' ],
                'tipo'=> [ 'nombre' ],
                'Contrato'=> [
                    'numero',
                    'cliente'=>[
                        'nombres',
                        'dni'=>['numero']
                    ]
                ],
                'codigo',
                'estado'=>[
                    'nombre'
                ],
                'fecha'

            ]
        ]);
        return $this->render('orden/index.html.twig', [
            'ordenes' => $data,
        ]);
    }

    /**
     * @Route("/new", name="orden_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $orden = new Orden();
        $orden->setFecha(new \DateTime());
        $form = $this->createForm(OrdenType::class, $orden);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($orden);
            $entityManager->flush();

            return $this->redirectToRoute('orden_index');
        }

        return $this->render('orden/new.html.twig', [
            'orden' => $orden,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="orden_show", methods={"GET"})
     */
    public function show(Orden $orden): Response
    {
        return $this->render('orden/show.html.twig', [
            'orden' => $orden,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="orden_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Orden $orden): Response
    {
        $old = clone $orden;
        $old->setId(null);
        $form = $this->createForm(OrdenType::class, $orden);
        $form->handleRequest($request);

        /* @var $serializer Serializer */
        $serializer = $this->get('serializer');
        $mensaje = '';
        if ($form->isSubmitted()) {
            if($form->isValid()){
                $motivo = $request->request->get('motivo');
                if($old && $motivo){
                    $this->generarEventos($old, $orden, $motivo);
                    return $this->redirectToRoute('orden_index');
                }
                $mensaje = 'Sus cambios no se han guardado';
            }
        }
        $old->setId($orden->getId());
        return $this->render('orden/edit.html.twig', [
            'orden' => $old,
            'form' => $form->createView(),
            'mensaje' => $mensaje
        ]);
    }

    /**
     * @Route("/{id}", name="orden_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Orden $orden): Response
    {
        if ($this->isCsrfTokenValid('delete'.$orden->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($orden);
            $entityManager->flush();
        }

        return $this->redirectToRoute('orden_index');
    }
    private function generarEventos(Orden $old, Orden $new, $motivo){
        $em =  $this->getDoctrine()->getManager();
        $evento = new Evento();
        $evento->setFecha(new \DateTime());
        $evento->setObservaciones($motivo);
        $user = $this->getUser();
        $evento->setUsuario($user);
        $acciones = "";
        if($old->getCodigo() != $new->getCodigo()){
            $a = $old->getCodigo();
            $b = $new->getCodigo();
            $acciones.="Se cambió el Nro de Orden: $a, a el Nro: $b |";
        }
        if($old->getTipo() != $new->getTipo()){
            $a = $old->getTipo();
            $b = $new->getTipo();
            $acciones.="Se cambió el Tipo de Orden: $a, a el Tipo: $b |";
        }
        if($old->getEstado() != $new->getEstado()){
            $a = $old->getEstado();
            $b = $new->getEstado();
            $acciones.="Se cambió el Estado de Orden: $a, a el Estado: $b |";
        }
        $a = $old->getFecha()->format('Y-m-d');
        $b = $new->getFecha()->format('Y-m-d');
        if($a != $b){
            $acciones.="Se cambió la fecha de Orden: $a, a la fecha: $b |";
        }
        if($old->getObservaciones() != $new->getObservaciones()){
            $a = $old->getObservaciones();
            $b = $new->getObservaciones();
            $acciones.="Se cambió las observaciones de Orden: $a, a: $b |";
        }
        if($old->getContrato()!=$new->getContrato()){
            $a = $old->getContrato()->getNumero();
            $b = $new->getContrato()->getNumero();
            $acciones.="Se cambió la Contrato de la Orden: $a, a: $b |";
        }
        if($old->getTecnico()!=$new->getTecnico()){
            $a = $old->getTecnico();
            $b = $new->getTecnico();
            $acciones.="Se cambió el técnico asignado a la Orden: $a, a: $b |";
        }
        $evento->setAcciones($acciones);
        $new->addEvento($evento);
        $em->flush();
    }
}
