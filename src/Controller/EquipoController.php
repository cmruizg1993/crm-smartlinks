<?php

namespace App\Controller;

use App\Entity\Equipo;
use App\Form\EquipoType;
use App\Repository\EquipoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * @Route("/equipo")
 */
class EquipoController extends AbstractController
{
    /**
     * @Route("/", name="equipo_index", methods={"GET"})
     */
    public function index(EquipoRepository $equipoRepository): Response
    {
        return $this->render('equipo/index.html.twig', [
            'equipos' => $equipoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="equipo_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $equipo = new Equipo();
        $form = $this->createForm(EquipoType::class, $equipo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($equipo);
            $entityManager->flush();

            return $this->redirectToRoute('equipo_index');
        }

        return $this->render('equipo/new.html.twig', [
            'equipo' => $equipo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="equipo_show", methods={"GET"})
     */
    public function show(Equipo $equipo): Response
    {
        return $this->render('equipo/show.html.twig', [
            'equipo' => $equipo,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="equipo_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Equipo $equipo): Response
    {
        $form = $this->createForm(EquipoType::class, $equipo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('equipo_index');
        }

        return $this->render('equipo/edit.html.twig', [
            'equipo' => $equipo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="equipo_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Equipo $equipo): Response
    {
        if ($this->isCsrfTokenValid('delete'.$equipo->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($equipo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('equipo_index');
    }

    /**
     * @Route("/buscarEquipo", name="buscar_equipo", methods={"POST"})
     */
    public function buscarEquipo(Request $request): Response
    {
        $param = $request->request->get('param');
        $response = '<tr><td colspan="4">No se encontraron datos</td></tr>';
        if($param){
            $em =$this->getDoctrine()->getManager();
            $equipos = $em->getRepository(Equipo::class)->findByParam($param);
            ////dump($parroquias);
            /* @var $serializer Serializer */
            $serializer = $this->get('serializer');
            $data = $serializer->normalize($equipos, null, [AbstractNormalizer::ATTRIBUTES=>
                [
                    'id',
                    'nombre',
                    'codigo',
                    'esSeriado'
                ]
            ]);
            $response = $this->renderView('equipo/equipos.html.twig',['equipos'=>$data]);
        }
        return new Response($response);
    }
}
