<?php

namespace App\Controller;

use App\Entity\SAN;
use App\Form\SANType;
use App\Repository\SANRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("s/a/n")
 */
class SANController extends AbstractController
{
    /**
     * @Route("/", name="s_a_n_index", methods={"GET"})
     */
    public function index(SANRepository $sANRepository): Response
    {
        return $this->render('san/index.html.twig', [
            's_a_ns' => $sANRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="s_a_n_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $sAN = new SAN();
        $sAN->setFecha(new \DateTime());
        $form = $this->createForm(SANType::class, $sAN);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sAN);
            $entityManager->flush();

            return $this->redirectToRoute('s_a_n_index');
        }

        return $this->render('san/new.html.twig', [
            's_a_n' => $sAN,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="s_a_n_show", methods={"GET"})
     */
    public function show(SAN $sAN): Response
    {
        return $this->render('san/show.html.twig', [
            's_a_n' => $sAN,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="s_a_n_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SAN $sAN): Response
    {
        $form = $this->createForm(SANType::class, $sAN);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('s_a_n_index');
        }

        return $this->render('san/edit.html.twig', [
            's_a_n' => $sAN,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="s_a_n_delete", methods={"DELETE"})
     */
    public function delete(Request $request, SAN $sAN): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sAN->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sAN);
            $entityManager->flush();
        }

        return $this->redirectToRoute('s_a_n_index');
    }

    /**
     * @Route("/buscarsan", name="buscar_san", methods={"POST"})
     */
    public function buscarParroquia(Request $request): Response
    {
        $param = $request->request->get('param');
        $html = '<tr><td colspan="4">No se encontraron datos</td></tr>';
        if($param){
            $em =$this->getDoctrine()->getManager();
            $sans = $em->getRepository("App:SAN")->findByParam($param);
            //dump($parroquias);
            /* @var $serializer Serializer */
            $serializer = $this->get('serializer');
            $data = $serializer->normalize($sans, null, [AbstractNormalizer::ATTRIBUTES=>
                [
                    'id',
                    'numero',
                    'cliente'=>[
                        'id',
                        'nombre',                        
                    ],
                    'parroquia'=>[
                        'nombre'
                    ],
                    'solicitud'=>[
                        'id'
                    ]
                ]
            ]);
            $html = $this->renderView('san/sans.html.twig',['parroquias'=>$data]);
        }
        dump($html);
        return new Response($html);
    }
}
