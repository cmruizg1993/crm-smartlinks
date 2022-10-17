<?php

namespace App\Controller;

use App\Entity\Establecimiento;
use App\Form\EstablecimientoType;
use App\Repository\EstablecimientoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/establecimiento")
 */
class EstablecimientoController extends AbstractController
{
    /**
     * @Route("/", name="establecimiento_index", methods={"GET"})
     */
    public function index(EstablecimientoRepository $establecimientoRepository): Response
    {
        return $this->render('establecimiento/index.html.twig', [
            'establecimientos' => $establecimientoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="establecimiento_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $establecimiento = new Establecimiento();
        $form = $this->createForm(EstablecimientoType::class, $establecimiento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($establecimiento);
            $entityManager->flush();

            return $this->redirectToRoute('establecimiento_index');
        }

        return $this->render('establecimiento/new.html.twig', [
            'establecimiento' => $establecimiento,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="establecimiento_show", methods={"GET"})
     */
    public function show(Establecimiento $establecimiento): Response
    {
        return $this->render('establecimiento/show.html.twig', [
            'establecimiento' => $establecimiento,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="establecimiento_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Establecimiento $establecimiento): Response
    {
        $form = $this->createForm(EstablecimientoType::class, $establecimiento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('establecimiento_index');
        }

        return $this->render('establecimiento/edit.html.twig', [
            'establecimiento' => $establecimiento,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="establecimiento_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Establecimiento $establecimiento): Response
    {
        if ($this->isCsrfTokenValid('delete'.$establecimiento->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($establecimiento);
            $entityManager->flush();
        }

        return $this->redirectToRoute('establecimiento_index');
    }
}
