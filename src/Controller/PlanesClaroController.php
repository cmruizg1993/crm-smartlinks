<?php

namespace App\Controller;

use App\Entity\PlanesClaro;
use App\Form\PlanesClaroType;
use App\Repository\PlanesClaroRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/planes/claro")
 */
class PlanesClaroController extends AbstractController
{
    /**
     * @Route("/", name="planes_claro_index", methods={"GET"})
     */
    public function index(PlanesClaroRepository $planesClaroRepository): Response
    {
        return $this->render('planes_claro/index.html.twig', [
            'planes_claros' => $planesClaroRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="planes_claro_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $planesClaro = new PlanesClaro();
        $form = $this->createForm(PlanesClaroType::class, $planesClaro);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($planesClaro);
            $entityManager->flush();

            return $this->redirectToRoute('planes_claro_index');
        }

        return $this->render('planes_claro/new.html.twig', [
            'planes_claro' => $planesClaro,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="planes_claro_show", methods={"GET"})
     */
    public function show(PlanesClaro $planesClaro): Response
    {
        return $this->render('planes_claro/show.html.twig', [
            'planes_claro' => $planesClaro,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="planes_claro_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PlanesClaro $planesClaro): Response
    {
        $form = $this->createForm(PlanesClaroType::class, $planesClaro);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('planes_claro_index');
        }

        return $this->render('planes_claro/edit.html.twig', [
            'planes_claro' => $planesClaro,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="planes_claro_delete", methods={"DELETE"})
     */
    public function delete(Request $request, PlanesClaro $planesClaro): Response
    {
        if ($this->isCsrfTokenValid('delete'.$planesClaro->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($planesClaro);
            $entityManager->flush();
        }

        return $this->redirectToRoute('planes_claro_index');
    }
}
