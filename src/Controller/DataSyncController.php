<?php

namespace App\Controller;

use App\Repository\FacturaRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DataSyncController
 * @package App\Controller
 * @IsGranted("ROLE_SUPER_ADMIN")
 */
class DataSyncController extends AbstractController
{
    /**
     * @Route("/data/sync/tipo-comp", name="app_data_sync")
     */
    public function tipoComp
    (
        FacturaRepository $facturaRepository
    ): Response
    {
        $result = $facturaRepository->importarCodigoComprobante();
        dump($result);
        return $this->render('data_sync/index.html.twig', [
            'controller_name' => 'DataSyncController',
        ]);
    }
}
