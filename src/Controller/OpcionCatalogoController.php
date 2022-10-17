<?php

namespace App\Controller;

use App\Entity\OpcionCatalogo;
use App\Form\OpcionCatalogoType;
use App\Repository\OpcionCatalogoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/opcion/catalogo")
 */
class OpcionCatalogoController extends AbstractController
{
    /**
     * @Route("/", name="opcion_catalogo_index", methods={"GET"})
     */
    public function index(OpcionCatalogoRepository $opcionCatalogoRepository): Response
    {
        return $this->render('opcion_catalogo/index.html.twig', [
            'opcion_catalogos' => $opcionCatalogoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="opcion_catalogo_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $opcionCatalogo = new OpcionCatalogo();
        $form = $this->createForm(OpcionCatalogoType::class, $opcionCatalogo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($opcionCatalogo);
            $entityManager->flush();

            return $this->redirectToRoute('opcion_catalogo_index');
        }

        return $this->render('opcion_catalogo/new.html.twig', [
            'opcion_catalogo' => $opcionCatalogo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="opcion_catalogo_show", methods={"GET"})
     */
    public function show(OpcionCatalogo $opcionCatalogo): Response
    {
        return $this->render('opcion_catalogo/show.html.twig', [
            'opcion_catalogo' => $opcionCatalogo,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="opcion_catalogo_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, OpcionCatalogo $opcionCatalogo): Response
    {
        $form = $this->createForm(OpcionCatalogoType::class, $opcionCatalogo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('opcion_catalogo_index');
        }

        return $this->render('opcion_catalogo/edit.html.twig', [
            'opcion_catalogo' => $opcionCatalogo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="opcion_catalogo_delete", methods={"DELETE"})
     */
    public function delete(Request $request, OpcionCatalogo $opcionCatalogo): Response
    {
        if ($this->isCsrfTokenValid('delete'.$opcionCatalogo->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($opcionCatalogo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('opcion_catalogo_index');
    }
}
