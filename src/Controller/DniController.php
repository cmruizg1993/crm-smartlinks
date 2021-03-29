<?php

namespace App\Controller;

use App\Entity\Dni;
use App\Form\DniType;
use App\Repository\DniRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dni")
 */
class DniController extends AbstractController
{
    /**
     * @Route("/", name="dni_index", methods={"GET"})
     */
    public function index(DniRepository $dniRepository): Response
    {
        return $this->render('dni/index.html.twig', [
            'dnis' => $dniRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="dni_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $dni = new Dni();
        $form = $this->createForm(DniType::class, $dni);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($dni);
            $entityManager->flush();

            return $this->redirectToRoute('dni_index');
        }

        return $this->render('dni/new.html.twig', [
            'dni' => $dni,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="dni_show", methods={"GET"})
     */
    public function show(Dni $dni): Response
    {
        return $this->render('dni/show.html.twig', [
            'dni' => $dni,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="dni_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Dni $dni): Response
    {
        $form = $this->createForm(DniType::class, $dni);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('dni_index');
        }

        return $this->render('dni/edit.html.twig', [
            'dni' => $dni,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="dni_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Dni $dni): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dni->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($dni);
            $entityManager->flush();
        }

        return $this->redirectToRoute('dni_index');
    }
}
