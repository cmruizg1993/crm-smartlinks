<?php

namespace App\Controller;

use App\Entity\FormaPago;
use App\Entity\Solicitud;
use App\Entity\Usuario;
use App\Form\SolicitudType;
use App\Repository\FormaPagoRepository;
use App\Repository\SolicitudRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/solicitud")
 */
class SolicitudController extends AbstractController
{
    /**
     * @Route("/", name="solicitud_index", methods={"GET"})
     */
    public function index(SolicitudRepository $solicitudRepository): Response
    {
        $user = $this->getUser()->getColaborador();
        $esAdmin = $this->isGranted('ROLE_ADMIN');
        if($esAdmin){
            $solicitudes = $solicitudRepository->findAll();
        }else{
            $solicitudes = $solicitudRepository->findByVendedor($user);
        }
        return $this->render('solicitud/index.html.twig', [
            'solicituds' => $solicitudes,
        ]);
    }

    /**
     * @Route("/new", name="solicitud_new", methods={"GET","POST"})
     */
    public function new(Request $request, FormaPagoRepository $formaPagoRepository): Response
    {
        $solicitud = new Solicitud();
        $fpago = $solicitud->getFormaPago();
        if(!$fpago){
            $fpago = $formaPagoRepository->findOneByCodigo('EF');
            $solicitud->setFormaPago($fpago);
            $solicitud->setCuentaBancaria(null);
        }
        $form = $this->createForm(SolicitudType::class, $solicitud);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            /* @var $user Usuario */
            $user = $this->getUser();
            $colaborador = $user->getColaborador();
            if($colaborador){
                $solicitud->setVendedor($colaborador);
            }
            $solicitud->setFecha(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($solicitud);
            $entityManager->flush();

            return $this->redirectToRoute('solicitud_index');
        }

        return $this->render('solicitud/new.html.twig', [
            'solicitud' => $solicitud,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="solicitud_show", methods={"GET"})
     */
    public function show(Solicitud $solicitud): Response
    {
        return $this->render('solicitud/show.html.twig', [
            'solicitud' => $solicitud,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="solicitud_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Solicitud $solicitud): Response
    {
        $form = $this->createForm(SolicitudType::class, $solicitud);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('solicitud_index');
        }

        return $this->render('solicitud/edit.html.twig', [
            'solicitud' => $solicitud,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="solicitud_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Solicitud $solicitud): Response
    {
        if ($this->isCsrfTokenValid('delete'.$solicitud->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($solicitud);
            $entityManager->flush();
        }

        return $this->redirectToRoute('solicitud_index');
    }
}
