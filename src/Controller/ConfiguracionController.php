<?php

namespace App\Controller;

use App\Entity\Configuracion;
use App\Form\ConfiguracionType;
use App\Repository\ConfiguracionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/configuracion")
 */
class ConfiguracionController extends AbstractController
{
    /**
     * @Route("/", name="app_configuracion_index", methods={"GET"})
     */
    public function index(ConfiguracionRepository $configuracionRepository): Response
    {
        return $this->render('configuracion/index.html.twig', [
            'configuracions' => $configuracionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_configuracion_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $configuracion = new Configuracion();
        $form = $this->createForm(ConfiguracionType::class, $configuracion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($configuracion);
            $entityManager->flush();

            return $this->redirectToRoute('app_configuracion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('configuracion/new.html.twig', [
            'configuracion' => $configuracion,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_configuracion_show", methods={"GET"})
     */
    public function show(Configuracion $configuracion): Response
    {
        return $this->render('configuracion/show.html.twig', [
            'configuracion' => $configuracion,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_configuracion_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Configuracion $configuracion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ConfiguracionType::class, $configuracion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_configuracion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('configuracion/edit.html.twig', [
            'configuracion' => $configuracion,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_configuracion_delete", methods={"POST"})
     */
    public function delete(Request $request, Configuracion $configuracion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$configuracion->getId(), $request->request->get('_token'))) {
            $entityManager->remove($configuracion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_configuracion_index', [], Response::HTTP_SEE_OTHER);
    }
}
