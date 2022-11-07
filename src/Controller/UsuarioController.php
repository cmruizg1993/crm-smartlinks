<?php

namespace App\Controller;

use App\Entity\Cliente;
use App\Entity\Usuario;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsuarioController extends AbstractController
{
    /**
     * @Route("/usuario", name="perfil_usuario")
     */
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('usuario/index.html.twig', [
            'controller_name' => 'UsuarioController',
        ]);
    }
    /**
     * @Route("/usuario/list", name="usuario_list")
     */
    public function list(UsuarioRepository $usuarioRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('usuario/list.html.twig', [
            'usuarios' => $usuarioRepository->findAll(),
        ]);
    }

    /**
     * @Route("/usuario/edit/{id}", name="user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Usuario $usuario): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $roles = [
          'Vendedor'=>'ROLE_VENDEDOR',
          'Administrador'=>'ROLE_ADMIN'
        ];
        $userRoles = $usuario->getRoles();


        $form = $this->createFormBuilder([])
            ->add('roles', ChoiceType::class,
                [
                    'expanded'=>true,
                    'multiple'=>true,
                    'choices' => $roles,
                    'data' => $userRoles,

                ]
            )
            ->add('id', HiddenType::class,
            [
                'attr'=>[
                    'value'=>$usuario->getId()
                ]
            ])
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $hasAccess = in_array('ROLE_SUPER_ADMIN', $usuario->getRoles());
            if(!$hasAccess){
                $usuario->setRoles($form['roles']->getData());
                $this->getDoctrine()->getManager()->flush();
            }
            return $this->redirectToRoute('usuario_list');
        }
        return $this->render('usuario/roles.html.twig', [
            'form' => $form->createView(),
            'usuario'=>$usuario
        ]);
        //
    }
}
