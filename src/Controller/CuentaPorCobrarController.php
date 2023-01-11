<?php

namespace App\Controller;

use App\Entity\CuentaPorCobrar;
use App\Form\CuentaPorCobrarType;
use App\Repository\CuentaPorCobrarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cuenta/por/cobrar")
 */
class CuentaPorCobrarController extends AbstractController
{
    /**
     * @Route("/", name="app_cuenta_por_cobrar_index", methods={"GET"})
     */
    public function index(CuentaPorCobrarRepository $cuentaPorCobrarRepository): Response
    {
        return $this->render('cuenta_por_cobrar/index.html.twig', [
            'cuenta_por_cobrars' => $cuentaPorCobrarRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_cuenta_por_cobrar_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CuentaPorCobrarRepository $cuentaPorCobrarRepository): Response
    {
        $cuentaPorCobrar = new CuentaPorCobrar();
        $form = $this->createForm(CuentaPorCobrarType::class, $cuentaPorCobrar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cuentaPorCobrarRepository->add($cuentaPorCobrar, true);

            return $this->redirectToRoute('app_cuenta_por_cobrar_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cuenta_por_cobrar/new.html.twig', [
            'cuenta_por_cobrar' => $cuentaPorCobrar,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_cuenta_por_cobrar_show", methods={"GET"})
     */
    public function show(CuentaPorCobrar $cuentaPorCobrar): Response
    {
        return $this->render('cuenta_por_cobrar/show.html.twig', [
            'cuenta_por_cobrar' => $cuentaPorCobrar,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_cuenta_por_cobrar_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CuentaPorCobrar $cuentaPorCobrar, CuentaPorCobrarRepository $cuentaPorCobrarRepository): Response
    {
        $form = $this->createForm(CuentaPorCobrarType::class, $cuentaPorCobrar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cuentaPorCobrarRepository->add($cuentaPorCobrar, true);

            return $this->redirectToRoute('app_cuenta_por_cobrar_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cuenta_por_cobrar/edit.html.twig', [
            'cuenta_por_cobrar' => $cuentaPorCobrar,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_cuenta_por_cobrar_delete", methods={"POST"})
     */
    public function delete(Request $request, CuentaPorCobrar $cuentaPorCobrar, CuentaPorCobrarRepository $cuentaPorCobrarRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cuentaPorCobrar->getId(), $request->request->get('_token'))) {
            $cuentaPorCobrarRepository->remove($cuentaPorCobrar, true);
        }

        return $this->redirectToRoute('app_cuenta_por_cobrar_index', [], Response::HTTP_SEE_OTHER);
    }
}
