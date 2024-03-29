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
            $roles = (array) $user->getRoles();

            $esTecnico = array_search('ROLE_TECNICO', $roles) !== false;
            if($esTecnico){
                return $this->redirectToRoute('orden_index');
            }
            //return $this->redirectToRoute('perfil_usuario');

            return $this->redirectToRoute('factura_index');
        }
        return $this->redirectToRoute('app_login');
    }
}
