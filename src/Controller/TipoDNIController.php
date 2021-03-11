<?php

namespace App\Controller;

use App\Entity\TipoDNI;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TipoDNIController extends AbstractController
{
    /**
     * @Route("/tipo-dni", name="tipo_dni")
     */
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $tipos = $em->getRepository('App:TipoDNI')->findAll();
        if (count($tipos)==0){
            $array_tipos = [
                ['cod'=>'CI','nombre'=>'CED
                ULA'],
                ['cod'=>'RN','nombre'=>'RUC PERSONA NATURAL'],
                ['cod'=>'RJ','nombre'=>'RUC PERSONA JURIDICA'],
                ['cod'=>'RP','nombre'=>'RUC EMPRESA PRIVADA'],
                ['cod'=>'PP','nombre'=>'PASAPORTE'],
            ];
            foreach ($array_tipos as $t){
                $dni = new TipoDNI();
                $dni->setCodigo($t['cod']);
                $dni->setNombre($t['nombre']);
                $em->persist($dni);
            }
            $em->flush();
        }
        return $this->render('tipo_dni/index.html.twig', [
            'controller_name' => 'TipoDNIController',
        ]);
    }
}
