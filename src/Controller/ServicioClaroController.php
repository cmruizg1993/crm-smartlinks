<?php

namespace App\Controller;

use App\Entity\ServicioClaro;
use App\Form\ServicioClaroType;
use App\Repository\ServicioClaroRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/servicio/claro")
 */
class ServicioClaroController extends AbstractController
{
    /**
     * @Route("/", name="servicio_claro_index", methods={"GET"})
     */
    public function index(ServicioClaroRepository $servicioClaroRepository): Response
    {
        return $this->render('servicio_claro/index.html.twig', [
            'servicio_claros' => $servicioClaroRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="servicio_claro_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $servicioClaro = new ServicioClaro();
        $form = $this->createForm(ServicioClaroType::class, $servicioClaro);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($servicioClaro);
            $entityManager->flush();

            return $this->redirectToRoute('servicio_claro_index');
        }

        return $this->render('servicio_claro/new.html.twig', [
            'servicio_claro' => $servicioClaro,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="servicio_claro_show", methods={"GET"})
     */
    public function show(ServicioClaro $servicioClaro): Response
    {
        return $this->render('servicio_claro/show.html.twig', [
            'servicio_claro' => $servicioClaro,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="servicio_claro_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ServicioClaro $servicioClaro): Response
    {
        $form = $this->createForm(ServicioClaroType::class, $servicioClaro);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('servicio_claro_index');
        }

        return $this->render('servicio_claro/edit.html.twig', [
            'servicio_claro' => $servicioClaro,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="servicio_claro_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ServicioClaro $servicioClaro): Response
    {
        if ($this->isCsrfTokenValid('delete'.$servicioClaro->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($servicioClaro);
            $entityManager->flush();
        }

        return $this->redirectToRoute('servicio_claro_index');
    }
}
