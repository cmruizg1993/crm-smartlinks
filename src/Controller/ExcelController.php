<?php

namespace App\Controller;


use App\Entity\Cliente;
use App\Entity\Colaborador;
use App\Entity\Orden;
use App\Entity\Plan;
use App\Entity\SAN;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpClient\HttplugClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ExcelController extends AbstractController
{
    /**
     * @Route("/excel", name="excel")
     */
    public function index(): Response
    {
        $pathFile = $this->getParameter('excel_templates').'/os.xls';
        $newfile  = $this->getParameter('excel_templates').'/'.uniqid('os').'.xls';
        $reader = IOFactory::createReaderForFile($pathFile);
        $spreadsheet = $reader->load($pathFile);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getCell('A2')->setValue('HOLA MUNDO');
        $writer = IOFactory::createWriter($spreadsheet, "Xls");
        $writer->save($newfile);

        return $this->render('excel/index.html.twig', [
            'controller_name' => 'ExcelController',
        ]);
    }
    /**
     * @Route("/cargafso", name="fso",methods={"POST", "GET"})
     */
    public function cargarFSO(Request $request): Response
    {

        $form = $this->createFormBuilder([])
            ->add('archivo', FileType::class,['attr'=>['required'=>'required']])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $map = (object) [
                'sanColumn' => 'B','estadoSanColumn' => 'C','estadoContratoColumn' => 'D','fechaOrdenColumn' => 'E',
                'nombreClienteColumn' => 'H', 'emailClienteColumn' => 'I','cantonColumn' => 'J','provinciaColumn' => 'K',
                'nroOrdenColumn' => 'Q', 'tipoOrdenColumn' => 'S', 'estadoOrdenColumn' => 'T', 'fechaOrdenColumn' => 'U',
                'codigoInstaladorColumn' => 'Y', 'nombreInstaladorColumn' => 'Z', 'fechaInstalacionColumn' => 'AA',
                'nombrePlanColumn'=>'F'
            ];
            $file = $form['archivo']->getData();
            if($file){
                $pathFile = $file->getPathName();
                //dump($pathFile);
                $reader = IOFactory::createReaderForFile($pathFile);
                $spreadsheet = $reader->load($pathFile);
                $sheet = $spreadsheet->getActiveSheet();
                $counter = 3;
                $em = $this->getDoctrine()->getManager();
                $sanRepository = $em->getRepository('App:SAN');
                $planRepository = $em->getRepository('App:Plan');

                $colaboradorRepository = $em->getRepository('App:Colaborador');
                $tipo = $em->getRepository('App:TipoOrden')->findOneByCodigo('I');
                $estado = $em->getRepository('App:EstadoOrden')->findOneByCodigo('P');

                $servicio = $em->getRepository('App:Servicio')->findOneByNombre('INTERNET SATELITAL');

                $nsan = $sheet->getCell("$map->sanColumn$counter")->getValue();
                while($nsan){

                    $exist = $sanRepository->findOneByNumero($nsan);
                    if(!$exist) {

                        $san = new SAN();
                        $san->setNumero($nsan);
                        $san->setEstado($sheet->getCell("$map->estadoSanColumn$counter")->getValue());
                        $san->setEstadoContrato($sheet->getCell("$map->estadoContratoColumn$counter")->getValue());
                        $san->setFecha(new \DateTime($sheet->getCell("$map->fechaOrdenColumn$counter")->getFormattedValue()));
                        $san->setDireccion($sheet->getCell("$map->cantonColumn$counter")->getValue() . ", " . $sheet->getCell("$map->provinciaColumn$counter")->getValue());

                        $cliente = new Cliente();
                        $cliente->setNombres($sheet->getCell("$map->nombreClienteColumn$counter")->getValue());
                        $cliente->setEmail($sheet->getCell("$map->emailClienteColumn$counter")->getValue());
                        $cliente->setDireccion($sheet->getCell("$map->cantonColumn$counter")->getValue() . ", " . $sheet->getCell("$map->provinciaColumn$counter")->getValue());
                        $cliente->addSan($san);
                        $san->setCliente($cliente);

                        $orden = new Orden();
                        $orden->setTipo($tipo);
                        if ($sheet->getCell("$map->nroOrdenColumn$counter")->getValue()) {
                            $orden->setCodigo($sheet->getCell("$map->nroOrdenColumn$counter")->getValue());
                        }
                        $orden->setFecha(new \DateTime($sheet->getCell("$map->fechaOrdenColumn$counter")->getFormattedValue()));
                        $orden->setEstado($estado);

                        $san->addOrdene($orden);
                        $nombre = $sheet->getCell("$map->nombrePlanColumn$counter")->getValue();
                        $plan = $planRepository->findOneByNombre($nombre);

                        if(!$plan && $nombre){
                            $plan = new Plan();
                            $plan->setServicio($servicio);
                            $plan->setNombre($nombre);
                            $plan->setActivo(true);
                            $plan->addSan($san);
                            $em->persist($plan);
                            $em->flush();
                        }

                        $san->setPlan($plan);


                        $orden->setSan($san);

                        $tecnico = $colaboradorRepository->findOneByCodigoIP($sheet->getCell("$map->codigoInstaladorColumn$counter")->getValue());

                        if(!$tecnico){
                            if($sheet->getCell("$map->nombreInstaladorColumn$counter")->getValue()){
                                $tecnico = new Colaborador();
                                $tecnico->setNombres($sheet->getCell("$map->nombreInstaladorColumn$counter")->getValue());
                                $tecnico->setCodigoIP($sheet->getCell("$map->codigoInstaladorColumn$counter")->getValue());
                                $tecnico->addOrdene($orden);
                                $em->persist($tecnico);
                                $em->flush();
                            }
                        }
                        $orden->setTecnico($tecnico);

                        $em->persist($san);
                    }
                    $counter++;
                    $nsan = $sheet->getCell("$map->sanColumn$counter")->getValue();
                }
                $em->flush();

            }
        }

        return $this->render('excel/fso.html.twig', [
            'controller_name' => 'ExcelController',
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/sincronizar", name="sincronizar")
     */
    public function sincronizar(Request $request, HttpClientInterface $client): Response
    {
        $form = $this->createFormBuilder([])
            ->add('archivo', FileType::class,['attr'=>['required'=>'required']])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form['archivo']->getData();
            if($file){
                $pathFile = $file->getPathName();

                $reader = IOFactory::createReaderForFile($pathFile);
                $spreadsheet = $reader->load($pathFile);
                $sheet = $spreadsheet->getActiveSheet();

                $counter = 1;

                $em = $this->getDoctrine()->getManager();
                $sanRepository = $em->getRepository('App:SAN');

                $nsan = $sheet->getCell("A$counter")->getValue();
                while($counter < 20){
                    dump($nsan);
                    $exist = $sanRepository->findOneByNumero($nsan);
                    if($exist) {

                        $cedula = $sheet->getCell("D$counter")->getValue();
                        $direccion = $sheet->getCell("F$counter")->getValue();
                        $result = $this->buscar($cedula);
                        dump($cedula);

                        /*$san = new SAN();
                        $san->setNumero($nsan);
                        $cliente = new Cliente();
                        $cliente->addSan($san);
                        $san->setCliente($cliente);
                        $em->persist($san);*/
                    }
                    $counter++;
                    $nsan = $sheet->getCell("A$counter")->getValue();
                }
                //$em->flush();

            }
        }

        return $this->render('excel/sincronizar.html.twig', [
            'controller_name' => 'ExcelController',
            'form'=>$form->createView()
        ]);
    }

    public function buscar($ci)
    {

        $data = [];
        if($ci){
            $uri = 'http://certificados.ministeriodegobierno.gob.ec/gestorcertificados/antecedentes/data.php';

            //$data = json_decode($response->getContent());
            $httpClient = new HttplugClient();
            $request = $httpClient->createRequest('POST', $uri,['body' => ['tipo' => 'getDataWsRc', 'ci'=>$ci]]);
            $promise = $httpClient->sendAsyncRequest($request)
                ->then(
                    function (\Psr\Http\Message\ResponseInterface $response) {
                        $response->getBody()->getContents();
                        return $response;
                    },
                    function (\Throwable $exception) {
                        dump( 'Error: '.$exception->getMessage());

                        throw $exception;
                    }
                );
            $response = $promise->wait()->getBody()->getContents();
            dump($response);




            $httpClient->wait();

        }
    }
}
