<?php

namespace App\Controller;

use App\Entity\Factura;
use App\Form\FacturaType;
use App\Repository\FacturaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/factura")
 */
class FacturaController extends AbstractController
{
    /**
     * @Route("/", name="factura_index", methods={"GET"})
     */
    public function index(FacturaRepository $facturaRepository): Response
    {
        return $this->render('factura/index.html.twig', [
            'facturas' => $facturaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="factura_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $factura = new Factura();
        $form = $this->createForm(FacturaType::class, $factura);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($factura);
            $entityManager->flush();

            return $this->redirectToRoute('factura_index');
        }
        $factura->setFecha(new \DateTime());
        $form = $this->createForm(FacturaType::class, $factura);
        return $this->render('factura/new.html.twig', [
            'factura' => $factura,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="factura_show", methods={"GET"})
     */
    public function show(Factura $factura): Response
    {
        return $this->render('factura/show.html.twig', [
            'factura' => $factura,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="factura_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Factura $factura): Response
    {
        $form = $this->createForm(FacturaType::class, $factura);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('factura_index');
        }

        return $this->render('factura/edit.html.twig', [
            'factura' => $factura,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="factura_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Factura $factura): Response
    {
        if ($this->isCsrfTokenValid('delete'.$factura->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($factura);
            $entityManager->flush();
        }

        return $this->redirectToRoute('factura_index');
    }
}
