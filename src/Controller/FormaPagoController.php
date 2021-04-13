<?php

namespace App\Controller;

use App\Entity\FormaPago;
use App\Form\FormaPagoType;
use App\Repository\FormaPagoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/forma/pago")
 */
class FormaPagoController extends AbstractController
{
    /**
     * @Route("/", name="forma_pago_index", methods={"GET"})
     */
    public function index(FormaPagoRepository $formaPagoRepository): Response
    {
        return $this->render('forma_pago/index.html.twig', [
            'forma_pagos' => $formaPagoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="forma_pago_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $formaPago = new FormaPago();
        $form = $this->createForm(FormaPagoType::class, $formaPago);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formaPago);
            $entityManager->flush();

            return $this->redirectToRoute('forma_pago_index');
        }

        return $this->render('forma_pago/new.html.twig', [
            'forma_pago' => $formaPago,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="forma_pago_show", methods={"GET"})
     */
    public function show(FormaPago $formaPago): Response
    {
        return $this->render('forma_pago/show.html.twig', [
            'forma_pago' => $formaPago,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="forma_pago_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, FormaPago $formaPago): Response
    {
        $form = $this->createForm(FormaPagoType::class, $formaPago);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('forma_pago_index');
        }

        return $this->render('forma_pago/edit.html.twig', [
            'forma_pago' => $formaPago,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="forma_pago_delete", methods={"DELETE"})
     */
    public function delete(Request $request, FormaPago $formaPago): Response
    {
        if ($this->isCsrfTokenValid('delete'.$formaPago->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($formaPago);
            $entityManager->flush();
        }

        return $this->redirectToRoute('forma_pago_index');
    }
}
