<?php

namespace App\Controller;

use App\Entity\Cliente;
use App\Entity\Contrato;
use App\Entity\EquipoInstalacion;
use App\Form\ContratoType;
use App\Repository\ContratoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * @Route("contrato")
 */
class ContratoController extends AbstractController
{
    /**
     * @Route("/", name="contrato_index", methods={"GET"})
     */
    public function index(ContratoRepository $ContratoRepository): Response
    {
        return $this->render('Contrato/index.html.twig', [
            'contratos' => $ContratoRepository->findAllRegisters(),
        ]);
    }

    /**
     * @Route("/new", name="contrato_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $Contrato = new Contrato();
        $Contrato->setFecha(new \DateTime());
        $form = $this->createForm(ContratoType::class, $Contrato);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $clientRepository = $entityManager->getRepository('App:Cliente');

            $dni = $Contrato->getCliente()->getDni()->getNumero();
            /**
             * @var $oldClient Cliente|null
             */
            $oldClient = $clientRepository->findOneByNumeroDni($dni);
            if($oldClient){
                $Contrato->setCliente($oldClient);
            }
            $equipos = json_decode($form['equiposjson']->getData());
            foreach ($equipos as $e){
                $equipoInstalacion = new EquipoInstalacion();
                $equipo = $entityManager->getRepository('App:Equipo')->find($e->id);
                $equipoInstalacion->setEquipo($equipo);
                if($e->esSeriado)
                    $equipoInstalacion->setSerie($e->serial);
                $equipoInstalacion->setCantidad($e->cantidad);
                $equipoInstalacion->setContrato($Contrato);
                $Contrato->addEquipo($equipoInstalacion);
            }
            $entityManager->persist($Contrato);
            $entityManager->flush();

            return $this->redirectToRoute('contrato_index');
        }

        return $this->render('Contrato/new.html.twig', [
            'contrato' => $Contrato,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="contrato_show", methods={"GET"})
     */
    public function show(Contrato $Contrato): Response
    {
        return $this->render('Contrato/show.html.twig', [
            'contrato' => $Contrato,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="contrato_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Contrato $Contrato): Response
    {
        dump($Contrato);
        $form = $this->createForm(ContratoType::class, $Contrato);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('contrato_index');
        }

        return $this->render('Contrato/edit.html.twig', [
            'contrato' => $Contrato,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="contrato_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Contrato $Contrato): Response
    {
        if ($this->isCsrfTokenValid('delete'.$Contrato->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($Contrato);
            $entityManager->flush();
        }

        return $this->redirectToRoute('contrato_index');
    }

    /**
     * @Route("/buscarContrato", name="buscar_Contrato", methods={"POST"})
     */
    public function buscarContrato(Request $request): Response
    {
        $param = $request->request->get('param');
        $html = '<tr><td colspan="4">No se encontraron datos</td></tr>';
        if($param){
            $em =$this->getDoctrine()->getManager();
            $Contratos = $em->getRepository("App:Contrato")->findByParam($param);
            //dump($parroquias);
            /* @var $serializer Serializer */
            $serializer = $this->get('serializer');
            $data = $serializer->normalize($Contratos, null, [AbstractNormalizer::ATTRIBUTES=>
                [
                    'id',
                    'numero',
                    'cliente'=>[
                        'id',
                        'nombres',
                        'dni'=>[
                            'numero'
                        ]
                    ],
                    'parroquia'=>[
                        'nombre'
                    ],
                    'plan'=>[
                        'id',
                        'codigo',
                        'nombre',
                        'costo'
                    ]
                ]
            ]);
            $html = $this->renderView('Contrato/Contratos.html.twig',['Contratos'=>$data]);
        }
        return new Response($html);
    }
}
