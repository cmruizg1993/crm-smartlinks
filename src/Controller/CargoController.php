<?php

namespace App\Controller;

use App\Entity\Cargo;
use App\Form\CargoType;
use App\Repository\CargoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cargo")
 */
class CargoController extends AbstractController
{
    /**
     * @Route("/", name="cargo_index", methods={"GET"})
     */
    public function index(CargoRepository $cargoRepository): Response
    {
        return $this->render('cargo/index.html.twig', [
            'cargos' => $cargoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="cargo_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $cargo = new Cargo();
        $form = $this->createForm(CargoType::class, $cargo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cargo);
            $entityManager->flush();

            return $this->redirectToRoute('cargo_index');
        }

        return $this->render('cargo/new.html.twig', [
            'cargo' => $cargo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cargo_show", methods={"GET"})
     */
    public function show(Cargo $cargo): Response
    {
        return $this->render('cargo/show.html.twig', [
            'cargo' => $cargo,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="cargo_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Cargo $cargo): Response
    {
        $form = $this->createForm(CargoType::class, $cargo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cargo_index');
        }

        return $this->render('cargo/edit.html.twig', [
            'cargo' => $cargo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cargo_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Cargo $cargo): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cargo->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cargo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cargo_index');
    }
}
