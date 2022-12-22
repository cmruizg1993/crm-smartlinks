<?php

namespace App\Controller;

use App\Entity\Canton;
use App\Entity\Parroquia;
use App\Entity\Provincia;
use App\Repository\ParroquiaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class ProvinciasController extends AbstractController
{
    /**
     * @Route("/provincias", name="provincias")
     */
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $provincias = $em->getRepository(Provincia::class)->findAll();
        if(count($provincias)==0){
            $fileProvincias = __DIR__.'/provincias.json';
            $strJsonFileContents = file_get_contents($fileProvincias);
            $arrayProvincias = json_decode($strJsonFileContents, true);

            foreach ($arrayProvincias as $key => $p){
                $provincia = new Provincia();
                $provincia->setNombre($p['provincia']);
                $arrayCantones = $p['cantones'];
                foreach ($arrayCantones as $c){
                    $canton=new Canton();
                    $canton->setNombre($c['canton']);
                    $canton->setProvincia($provincia);
                    $provincia->addCantone($canton);
                    $arrayParroquias = $c['parroquias'];
                    foreach ($arrayParroquias as $pa){
                        $parroquia = new Parroquia();
                        $parroquia->setNombre($pa);
                        $parroquia->setCanton($canton);
                        $canton->addParroquia($parroquia);
                    }
                }
                $em->persist($provincia);
            }
            $em->flush();
        }
        return $this->render('provincias/index.html.twig', [
            'controller_name' => $fileProvincias,
        ]);
    }
    /**
     * @Route("/canton/{id}", name="canton")
     */
    public function getCantones(Request $request, Provincia $provincia): Response
    {
        $serializer = $this->get('serializer');
        $cantones = $provincia->getCantones();
        $data = $serializer->normalize($cantones, null,
            [AbstractNormalizer::ATTRIBUTES =>
                [
                    'id',
                    'nombre'
                ]
            ]);
        return new Response($serializer->serialize($data, "json"));
    }
    /**
     * @Route("/parroquia/{id}", name="parroquia")
     */
    public function getParroquias(Request $request, Canton $canton): Response
    {
        $param = $request->request->get('param');
        $serializer = $this->get('serializer');
        $parroquias = $canton->getParroquias();
        $data = $serializer->normalize($parroquias, null,
            [AbstractNormalizer::ATTRIBUTES =>
                [
                    'id',
                    'nombre'
                ]
            ]);
        return new Response($serializer->serialize($data, "json"));

    }
    /**
     * @Route("/parroquia/get/{nombre}", name="parroquia")
     */
    public function obtenerParroquia($nombre, ParroquiaRepository $parroquiaRepository, SerializerInterface $serializer): Response
    {
        $parroquia = $parroquiaRepository->findOneByName($nombre);
        $data = $serializer->normalize($parroquia, null,
            [AbstractNormalizer::ATTRIBUTES =>
                [
                    'id',
                    'nombre'
                ]
            ]);
        return new JsonResponse($data);

    }
    /**
     * @Route("/buscarparroquia", name="buscar_parroquia", methods={"POST"})
     */
    public function buscarParroquia(Request $request): Response
    {
        $param = $request->request->get('param');
        $html = '<tr><td colspan="4">No se encontraron datos</td></tr>';
        if($param){
            $em =$this->getDoctrine()->getManager();
            $parroquias = $em->getRepository(Parroquia::class)->findByParam($param);
            ////dump($parroquias);
            /* @var $serializer Serializer */
            $serializer = $this->get('serializer');
            $data = $serializer->normalize($parroquias, null, [AbstractNormalizer::ATTRIBUTES=>
                [
                    'id',
                    'nombre',
                    'canton'=>[
                        'id',
                        'nombre',
                        'provincia'=>[
                            'id',
                            'nombre'
                        ]
                    ]
                ]
            ]);
            $html = $this->renderView('provincias/parroquias.html.twig',['parroquias'=>$data]);
        }
        //dump($html);
        return new Response($html);
    }
}
