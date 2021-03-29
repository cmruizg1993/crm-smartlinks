<?php

namespace App\Controller;

use App\Entity\Seriado;
use App\Form\SeriadoType;
use App\Repository\SeriadoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/seriado")
 */
class SeriadoController extends AbstractController
{
    /**
     * @Route("/", name="seriado_index", methods={"GET"})
     */
    public function index(SeriadoRepository $seriadoRepository): Response
    {
        return $this->render('seriado/index.html.twig', [
            'seriados' => $seriadoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="seriado_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $seriado = new Seriado();
        $form = $this->createForm(SeriadoType::class, $seriado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($seriado);
            $entityManager->flush();

            return $this->redirectToRoute('seriado_index');
        }

        return $this->render('seriado/new.html.twig', [
            'seriado' => $seriado,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="seriado_show", methods={"GET"})
     */
    public function show(Seriado $seriado): Response
    {
        return $this->render('seriado/show.html.twig', [
            'seriado' => $seriado,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="seriado_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Seriado $seriado): Response
    {
        $form = $this->createForm(SeriadoType::class, $seriado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('seriado_index');
        }

        return $this->render('seriado/edit.html.twig', [
            'seriado' => $seriado,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="seriado_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Seriado $seriado): Response
    {
        if ($this->isCsrfTokenValid('delete'.$seriado->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($seriado);
            $entityManager->flush();
        }

        return $this->redirectToRoute('seriado_index');
    }
}
