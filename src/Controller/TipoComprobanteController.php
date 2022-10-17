<?php

namespace App\Controller;

use App\Entity\TipoComprobante;
use App\Form\TipoComprobanteType;
use App\Repository\TipoComprobanteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tipo/comprobante")
 */
class TipoComprobanteController extends AbstractController
{
    /**
     * @Route("/", name="tipo_comprobante_index", methods={"GET"})
     */
    public function index(TipoComprobanteRepository $tipoComprobanteRepository): Response
    {
        return $this->render('tipo_comprobante/index.html.twig', [
            'tipo_comprobantes' => $tipoComprobanteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="tipo_comprobante_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $tipoComprobante = new TipoComprobante();
        $form = $this->createForm(TipoComprobanteType::class, $tipoComprobante);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tipoComprobante);
            $entityManager->flush();

            return $this->redirectToRoute('tipo_comprobante_index');
        }

        return $this->render('tipo_comprobante/new.html.twig', [
            'tipo_comprobante' => $tipoComprobante,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tipo_comprobante_show", methods={"GET"})
     */
    public function show(TipoComprobante $tipoComprobante): Response
    {
        return $this->render('tipo_comprobante/show.html.twig', [
            'tipo_comprobante' => $tipoComprobante,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tipo_comprobante_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TipoComprobante $tipoComprobante): Response
    {
        $form = $this->createForm(TipoComprobanteType::class, $tipoComprobante);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tipo_comprobante_index');
        }

        return $this->render('tipo_comprobante/edit.html.twig', [
            'tipo_comprobante' => $tipoComprobante,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tipo_comprobante_delete", methods={"DELETE"})
     */
    public function delete(Request $request, TipoComprobante $tipoComprobante): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tipoComprobante->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tipoComprobante);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tipo_comprobante_index');
    }
}
