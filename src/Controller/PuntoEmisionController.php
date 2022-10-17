<?php

namespace App\Controller;

use App\Entity\PuntoEmision;
use App\Form\PuntoEmisionType;
use App\Repository\PuntoEmisionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/punto/emision")
 */
class PuntoEmisionController extends AbstractController
{
    /**
     * @Route("/", name="punto_emision_index", methods={"GET"})
     */
    public function index(PuntoEmisionRepository $puntoEmisionRepository): Response
    {
        return $this->render('punto_emision/index.html.twig', [
            'punto_emisions' => $puntoEmisionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="punto_emision_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $puntoEmision = new PuntoEmision();
        $form = $this->createForm(PuntoEmisionType::class, $puntoEmision);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($puntoEmision);
            $entityManager->flush();

            return $this->redirectToRoute('punto_emision_index');
        }

        return $this->render('punto_emision/new.html.twig', [
            'punto_emision' => $puntoEmision,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="punto_emision_show", methods={"GET"})
     */
    public function show(PuntoEmision $puntoEmision): Response
    {
        return $this->render('punto_emision/show.html.twig', [
            'punto_emision' => $puntoEmision,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="punto_emision_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PuntoEmision $puntoEmision): Response
    {
        $form = $this->createForm(PuntoEmisionType::class, $puntoEmision);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('punto_emision_index');
        }

        return $this->render('punto_emision/edit.html.twig', [
            'punto_emision' => $puntoEmision,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="punto_emision_delete", methods={"DELETE"})
     */
    public function delete(Request $request, PuntoEmision $puntoEmision): Response
    {
        if ($this->isCsrfTokenValid('delete'.$puntoEmision->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($puntoEmision);
            $entityManager->flush();
        }

        return $this->redirectToRoute('punto_emision_index');
    }
}
