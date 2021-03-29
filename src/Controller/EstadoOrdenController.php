<?php

namespace App\Controller;

use App\Entity\EstadoOrden;
use App\Form\EstadoOrdenType;
use App\Repository\EstadoOrdenRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/estado/orden")
 */
class EstadoOrdenController extends AbstractController
{
    /**
     * @Route("/", name="estado_orden_index", methods={"GET"})
     */
    public function index(EstadoOrdenRepository $estadoOrdenRepository): Response
    {
        return $this->render('estado_orden/index.html.twig', [
            'estado_ordens' => $estadoOrdenRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="estado_orden_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $estadoOrden = new EstadoOrden();
        $form = $this->createForm(EstadoOrdenType::class, $estadoOrden);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($estadoOrden);
            $entityManager->flush();

            return $this->redirectToRoute('estado_orden_index');
        }

        return $this->render('estado_orden/new.html.twig', [
            'estado_orden' => $estadoOrden,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="estado_orden_show", methods={"GET"})
     */
    public function show(EstadoOrden $estadoOrden): Response
    {
        return $this->render('estado_orden/show.html.twig', [
            'estado_orden' => $estadoOrden,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="estado_orden_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, EstadoOrden $estadoOrden): Response
    {
        $form = $this->createForm(EstadoOrdenType::class, $estadoOrden);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('estado_orden_index');
        }

        return $this->render('estado_orden/edit.html.twig', [
            'estado_orden' => $estadoOrden,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="estado_orden_delete", methods={"DELETE"})
     */
    public function delete(Request $request, EstadoOrden $estadoOrden): Response
    {
        if ($this->isCsrfTokenValid('delete'.$estadoOrden->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($estadoOrden);
            $entityManager->flush();
        }

        return $this->redirectToRoute('estado_orden_index');
    }
}
