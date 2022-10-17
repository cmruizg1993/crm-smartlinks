<?php

namespace App\Controller;

use App\Entity\Catalogo;
use App\Form\CatalogoType;
use App\Repository\CatalogoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/catalogo")
 */
class CatalogoController extends AbstractController
{
    /**
     * @Route("/", name="catalogo_index", methods={"GET"})
     */
    public function index(CatalogoRepository $catalogoRepository): Response
    {
        return $this->render('catalogo/index.html.twig', [
            'catalogos' => $catalogoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="catalogo_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $catalogo = new Catalogo();
        $form = $this->createForm(CatalogoType::class, $catalogo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($catalogo);
            $entityManager->flush();

            return $this->redirectToRoute('catalogo_index');
        }

        return $this->render('catalogo/new.html.twig', [
            'catalogo' => $catalogo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="catalogo_show", methods={"GET"})
     */
    public function show(Catalogo $catalogo): Response
    {
        return $this->render('catalogo/show.html.twig', [
            'catalogo' => $catalogo,
        ]);
    }
    /**
     * @Route("/buscar/{codigo}", name="catalogo_get", methods={"GET"})
     */
    public function buscarCatalogo(Catalogo $catalogo): Response
    {
        /* @var $serializer Serializer */
        $serializer = $this->get('serializer');
        $data = $serializer->normalize($catalogo, 'json', [AbstractNormalizer::ATTRIBUTES=>
            [
                'id',
                'codigo',
                'nombre',
                'opciones'=>[
                    'codigo',
                    'texto'
                ]
            ]
        ]);
        return new JsonResponse($data);
    }

    /**
     * @Route("/{id}/edit", name="catalogo_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Catalogo $catalogo): Response
    {
        $form = $this->createForm(CatalogoType::class, $catalogo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('catalogo_index');
        }

        return $this->render('catalogo/edit.html.twig', [
            'catalogo' => $catalogo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="catalogo_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Catalogo $catalogo): Response
    {
        if ($this->isCsrfTokenValid('delete'.$catalogo->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($catalogo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('catalogo_index');
    }
}
