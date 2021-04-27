<?php

namespace App\Controller;

use App\Entity\CuentaBancaria;
use App\Form\CuentaBancariaType;
use App\Repository\CuentaBancariaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cuenta/bancaria")
 */
class CuentaBancariaController extends AbstractController
{
    /**
     * @Route("/", name="cuenta_bancaria_index", methods={"GET"})
     */
    public function index(CuentaBancariaRepository $cuentaBancariaRepository): Response
    {
        return $this->render('cuenta_bancaria/index.html.twig', [
            'cuenta_bancarias' => $cuentaBancariaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="cuenta_bancaria_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $cuentaBancarium = new CuentaBancaria();
        $form = $this->createForm(CuentaBancariaType::class, $cuentaBancarium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cuentaBancarium);
            $entityManager->flush();

            return $this->redirectToRoute('cuenta_bancaria_index');
        }

        return $this->render('cuenta_bancaria/new.html.twig', [
            'cuenta_bancarium' => $cuentaBancarium,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cuenta_bancaria_show", methods={"GET"})
     */
    public function show(CuentaBancaria $cuentaBancarium): Response
    {
        return $this->render('cuenta_bancaria/show.html.twig', [
            'cuenta_bancarium' => $cuentaBancarium,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="cuenta_bancaria_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CuentaBancaria $cuentaBancarium): Response
    {
        $form = $this->createForm(CuentaBancariaType::class, $cuentaBancarium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cuenta_bancaria_index');
        }

        return $this->render('cuenta_bancaria/edit.html.twig', [
            'cuenta_bancarium' => $cuentaBancarium,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cuenta_bancaria_delete", methods={"DELETE"})
     */
    public function delete(Request $request, CuentaBancaria $cuentaBancarium): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cuentaBancarium->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cuentaBancarium);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cuenta_bancaria_index');
    }
}
