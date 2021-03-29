<?php

namespace App\Controller;

use App\Entity\NoSeriado;
use App\Form\NoSeriadoType;
use App\Repository\NoSeriadoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/no/seriado")
 */
class NoSeriadoController extends AbstractController
{
    /**
     * @Route("/", name="no_seriado_index", methods={"GET"})
     */
    public function index(NoSeriadoRepository $noSeriadoRepository): Response
    {
        return $this->render('no_seriado/index.html.twig', [
            'no_seriados' => $noSeriadoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="no_seriado_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $noSeriado = new NoSeriado();
        $form = $this->createForm(NoSeriadoType::class, $noSeriado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($noSeriado);
            $entityManager->flush();

            return $this->redirectToRoute('no_seriado_index');
        }

        return $this->render('no_seriado/new.html.twig', [
            'no_seriado' => $noSeriado,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="no_seriado_show", methods={"GET"})
     */
    public function show(NoSeriado $noSeriado): Response
    {
        return $this->render('no_seriado/show.html.twig', [
            'no_seriado' => $noSeriado,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="no_seriado_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, NoSeriado $noSeriado): Response
    {
        $form = $this->createForm(NoSeriadoType::class, $noSeriado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('no_seriado_index');
        }

        return $this->render('no_seriado/edit.html.twig', [
            'no_seriado' => $noSeriado,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="no_seriado_delete", methods={"DELETE"})
     */
    public function delete(Request $request, NoSeriado $noSeriado): Response
    {
        if ($this->isCsrfTokenValid('delete'.$noSeriado->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($noSeriado);
            $entityManager->flush();
        }

        return $this->redirectToRoute('no_seriado_index');
    }
}
