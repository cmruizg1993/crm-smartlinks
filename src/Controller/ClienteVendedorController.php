<?php

namespace App\Controller;

use App\Entity\Cliente;
use App\Entity\Usuario;
use App\Form\ClienteType;
use App\Repository\ClienteRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @Route("/clientev")
 */
class ClienteVendedorController extends AbstractController
{
    /**
     * @Route("/", name="clientev_index", methods={"GET"})
     */
    public function index(ClienteRepository $clienteRepository): Response
    {
        /* @var $user Usuario */
        $user = $this->getUser();
        $vendedor = $user->getColaborador();

        $clientes = $clienteRepository->findByVendedor($vendedor);

        return $this->render('cliente/index.html.twig', [
            'clientes' => $clientes,
        ]);
    }

}
