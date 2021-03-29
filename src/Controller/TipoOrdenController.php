<?php

namespace App\Controller;

use App\Entity\TipoOrden;
use App\Form\TipoOrdenType;
use App\Repository\TipoOrdenRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tipo/orden")
 */
class TipoOrdenController extends AbstractController
{
    /**
     * @Route("/", name="tipo_orden_index", methods={"GET"})
     */
    public function index(TipoOrdenRepository $tipoOrdenRepository): Response
    {
        return $this->render('tipo_orden/index.html.twig', [
            'tipo_ordens' => $tipoOrdenRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="tipo_orden_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $tipoOrden = new TipoOrden();
        $form = $this->createForm(TipoOrdenType::class, $tipoOrden);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tipoOrden);
            $entityManager->flush();

            return $this->redirectToRoute('tipo_orden_index');
        }

        return $this->render('tipo_orden/new.html.twig', [
            'tipo_orden' => $tipoOrden,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tipo_orden_show", methods={"GET"})
     */
    public function show(TipoOrden $tipoOrden): Response
    {
        return $this->render('tipo_orden/show.html.twig', [
            'tipo_orden' => $tipoOrden,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tipo_orden_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TipoOrden $tipoOrden): Response
    {
        $form = $this->createForm(TipoOrdenType::class, $tipoOrden);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tipo_orden_index');
        }

        return $this->render('tipo_orden/edit.html.twig', [
            'tipo_orden' => $tipoOrden,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tipo_orden_delete", methods={"DELETE"})
     */
    public function delete(Request $request, TipoOrden $tipoOrden): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tipoOrden->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tipoOrden);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tipo_orden_index');
    }
}
