<?php

namespace App\Controller;

use App\Entity\Secuencial;
use App\Form\SecuencialType;
use App\Repository\SecuencialRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/secuencial")
 */
class SecuencialController extends AbstractController
{
    /**
     * @Route("/", name="app_secuencial_index", methods={"GET"})
     */
    public function index(SecuencialRepository $secuencialRepository): Response
    {
        return $this->render('secuencial/index.html.twig', [
            'secuencials' => $secuencialRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_secuencial_new", methods={"GET", "POST"})
     */
    public function new(Request $request, SecuencialRepository $secuencialRepository): Response
    {
        $secuencial = new Secuencial();
        $form = $this->createForm(SecuencialType::class, $secuencial);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $secuencialRepository->add($secuencial, true);

            return $this->redirectToRoute('app_secuencial_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('secuencial/new.html.twig', [
            'secuencial' => $secuencial,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_secuencial_show", methods={"GET"})
     */
    public function show(Secuencial $secuencial): Response
    {
        return $this->render('secuencial/show.html.twig', [
            'secuencial' => $secuencial,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_secuencial_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Secuencial $secuencial, SecuencialRepository $secuencialRepository): Response
    {
        $form = $this->createForm(SecuencialType::class, $secuencial);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $secuencialRepository->add($secuencial, true);

            return $this->redirectToRoute('app_secuencial_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('secuencial/edit.html.twig', [
            'secuencial' => $secuencial,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_secuencial_delete", methods={"POST"})
     */
    public function delete(Request $request, Secuencial $secuencial, SecuencialRepository $secuencialRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$secuencial->getId(), $request->request->get('_token'))) {
            $secuencialRepository->remove($secuencial, true);
        }

        return $this->redirectToRoute('app_secuencial_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/facturacion", name="secuencial_facturacion", methods={"GET"})
     */
    public function secuencialFacturacion(SecuencialRepository $secuencialRepository): Response
    {


        $secuenciales = $secuencialRepository->findBy(['activo'=>true, 'puntoEmision'=>$puntoEmision]);

        if(!$secuenciales ) return new Response("No se encontró un secuencial activo para el punto de emisión. Comuníquese con el administrador", 400);

        if(count($secuenciales) > 1 ) return new Response("Existe más de un secuencial activo para el punto de emisión. Comuníquese con el administrador", 400);

        $secuencialObj = $secuenciales[0];

        $secuencial = $secuencialObj->getActual();
        if(!$secuencial) $secuencial = $secuencialObj->getInicio();

        while (strlen($secuencial)<9)$secuencial='0'.$secuencial;
        $data['secuencial']=$secuencial;
        return new JsonResponse($data);
    }
}
