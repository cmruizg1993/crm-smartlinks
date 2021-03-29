<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InicioController extends AbstractController
{
    /**
     * @Route("/", name="inicio")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        if ($user){
            return $this->render('inicio/index.html.twig');
        }
        return $this->redirectToRoute('app_login');
    }
}
