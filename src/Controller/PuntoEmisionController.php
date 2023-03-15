<?php

namespace App\Controller;

use App\Entity\PuntoEmision;
use App\Entity\TipoComprobante;
use App\Entity\Usuario;
use App\Form\PuntoEmisionType;
use App\Repository\PuntoEmisionRepository;
use App\Repository\SecuencialRepository;
use App\Repository\TipoComprobanteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/punto/emision")
 */
class PuntoEmisionController extends AbstractController
{
    /**
     * @Route("/", name="punto_emision_index", methods={"GET"})
     */
    public function index(PuntoEmisionRepository $puntoEmisionRepository): Response
    {
        return $this->render('punto_emision/index.html.twig', [
            'punto_emisions' => $puntoEmisionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="punto_emision_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $puntoEmision = new PuntoEmision();
        $form = $this->createForm(PuntoEmisionType::class, $puntoEmision);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($puntoEmision);
            $entityManager->flush();

            return $this->redirectToRoute('punto_emision_index');
        }

        return $this->render('punto_emision/new.html.twig', [
            'punto_emision' => $puntoEmision,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="punto_emision_show", methods={"GET"})
     */
    public function show(PuntoEmision $puntoEmision): Response
    {
        return $this->render('punto_emision/show.html.twig', [
            'punto_emision' => $puntoEmision,
        ]);
    }
    /**
     * @Route("/get/{codigo}", name="punto_emision_get", methods={"GET"})
     */
    public function getPuntoByCodigoComprobante
    (
        $codigo = '',
        EntityManagerInterface $em,
        SecuencialRepository $secuencialRepository,
        TipoComprobanteRepository $tipoComprobanteRepository,
        SerializerInterface $serializer
    ): Response
    {
        /**
         * @var $user Usuario
         */
        $user = $this->getUser();

        $colaborador = $user->getColaborador();

        if(!$colaborador) return new Response("Sin datos de facturador. Comuníquese con el administrador", 400);

        $punto = $colaborador->getPuntoEmision();

        if(!$punto) return new Response("Sin datos de facturador. Comuníquese con el administrador", 400);

        $tipoComprobante = $tipoComprobanteRepository->findOneBy(['codigo'=>$codigo]);

        if(!$tipoComprobante) return new Response("Sin datos de comprobante. Comuníquese con el administrador", 400);

        $secuenciales = $secuencialRepository->findBy(['puntoEmision'=>$punto, 'tipoComprobante'=>$tipoComprobante, 'activo'=>true]);

        if(!$secuenciales) return new Response("Sin datos de secuencial. Comuníquese con el administrador", 400);

        if(count($secuenciales)>1) return new Response("Hay más de un secuencial activo. Comuníquese con el administrador", 400);
        $data = [];

        $data["puntoEmision"] = $serializer->normalize($punto,null, [AbstractNormalizer::ATTRIBUTES=>[
            'id',
            'codigo',
            'serie',
            'codigoEstablecimiento'
        ]]);
        $secuencial = $secuenciales[0];
        $numero = $secuencial->getActual();
        if(!$numero) $numero = $secuencial->getInicio();
        $data["secuencial"] = $numero;

        return new JsonResponse($data);
    }

    /**
     * @Route("/{id}/edit", name="punto_emision_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PuntoEmision $puntoEmision): Response
    {
        $form = $this->createForm(PuntoEmisionType::class, $puntoEmision);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('punto_emision_index');
        }

        return $this->render('punto_emision/edit.html.twig', [
            'punto_emision' => $puntoEmision,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="punto_emision_delete", methods={"DELETE"})
     */
    public function delete(Request $request, PuntoEmision $puntoEmision): Response
    {
        if ($this->isCsrfTokenValid('delete'.$puntoEmision->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($puntoEmision);
            $entityManager->flush();
        }

        return $this->redirectToRoute('punto_emision_index');
    }
}
