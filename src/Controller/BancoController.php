<?php

namespace App\Controller;

use App\Entity\Banco;
use App\Form\BancoType;
use App\Repository\BancoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/banco")
 */
class BancoController extends AbstractController
{
    /**
     * @Route("/", name="banco_index", methods={"GET"})
     */
    public function index(BancoRepository $bancoRepository): Response
    {
        return $this->render('banco/index.html.twig', [
            'bancos' => $bancoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="banco_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $banco = new Banco();
        $form = $this->createForm(BancoType::class, $banco);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($banco);
            $entityManager->flush();

            return $this->redirectToRoute('banco_index');
        }

        return $this->render('banco/new.html.twig', [
            'banco' => $banco,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="banco_show", methods={"GET"})
     */
    public function show(Banco $banco): Response
    {
        return $this->render('banco/show.html.twig', [
            'banco' => $banco,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="banco_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Banco $banco): Response
    {
        $form = $this->createForm(BancoType::class, $banco);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('banco_index');
        }

        return $this->render('banco/edit.html.twig', [
            'banco' => $banco,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="banco_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Banco $banco): Response
    {
        if ($this->isCsrfTokenValid('delete'.$banco->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($banco);
            $entityManager->flush();
        }

        return $this->redirectToRoute('banco_index');
    }
}
