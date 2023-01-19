<?php

namespace App\Controller;

use RouterOS\Query;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RouterController extends AbstractController
{
    /**
     * @Route("/router", name="app_router")
     */
    public function index(): Response
    {
        $config = new \RouterOS\Config([
            'host' => '186.101.103.154',
            'user' => 'cristianruiz',
            'pass' => 'Cristiansmartlinks2022*',
            'port' => 8245,
        ]);
        $client = new \RouterOS\Client($config);

        $query = (new Query('/ip/address/print'));
        $data = $client->query($query)->read();
        $response = new JsonResponse($data);
        return $response;
        /*
        return $this->render('router/index.html.twig', [
            'controller_name' => 'RouterController',
        ]);
        */
    }
}
