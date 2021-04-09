<?php

namespace App\Controller;


use App\Entity\Cliente;
use App\Entity\Colaborador;
use App\Entity\Equipo;
use App\Entity\Orden;
use App\Entity\Plan;
use App\Entity\SAN;
use App\Entity\Seriado;
use App\Entity\TipoOrden;
use App\Form\ItemOsType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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
     * @Route("/ordenrd", name="ordenrd")
     */
    public function ordenrd(Request $request): Response
    {
        $form = $this->createFormBuilder([])
            ->add('archivo', FileType::class,['attr'=>['required'=>'required']])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $map = (object) [
                'sanColumn' => 'A', 'codigoInstaladorColumn' => 'B','fechaColumn' => 'C',
                'radioColumn'=>'D','modemColumn'=>'E','tipoOrdenColumn' => 'F'
            ];
            $file = $form['archivo']->getData();

            $errores = [];

            if($file){
                $pathFile = $file->getPathName();
                //dump($pathFile);
                $reader = IOFactory::createReaderForFile($pathFile);
                $spreadsheet = $reader->load($pathFile);
                $sheet = $spreadsheet->getActiveSheet();
                $counter = 2;

                $em = $this->getDoctrine()->getManager();

                $sanRepository = $em->getRepository('App:SAN');

                $colaboradorRepository = $em->getRepository('App:Colaborador');

                $seriadosRepository = $em->getRepository('App:Seriado');

                $tipoReparacion = $em->getRepository('App:TipoOrden')->findOneByCodigo('X');

                $tipoDesinstalacion = $em->getRepository('App:TipoOrden')->findOneByCodigo('D');

                $estado = $em->getRepository('App:EstadoOrden')->findOneByCodigo('C');

                $nsan = $sheet->getCell("$map->sanColumn$counter")->getValue();
                /* @var $modem Equipo */
                $modem = $em->getRepository('App:Equipo')->findOneBySku("1505216-0473");
                /* @var $radio Equipo */
                $radio = $em->getRepository('App:Equipo')->findOneBySku("1506688-1002");

                while($nsan){
                    try{
                        $san = $sanRepository->findOneByNumero("HEC2000$nsan");
                        if($san) {
                            $orden = new Orden();

                            $tipo = $sheet->getCell("$map->tipoOrdenColumn$counter")->getValue();
                            if($tipo==TipoOrden::DESINSTALACION){
                                $orden->setTipo($tipoDesinstalacion);
                            }elseif($tipo==TipoOrden::REPARACION){
                                $orden->setTipo($tipoReparacion);
                            }else{
                                $errores[] = "CODIGO DE INSTALACION: $tipo NO VÁLIDO EN LA SAN $nsan";
                            }

                            $orden->setSan($san);

                            $orden->setEstado($estado);

                            $strFecha = $sheet->getCell("$map->fechaOrdenColumn$counter")->getFormattedValue();

                            $fecha = new \DateTime($strFecha);

                            $orden->setFecha($fecha);
                            $codigoTecnico = $sheet->getCell("$map->codigoInstaladorColumn$counter")->getValue();
                            $tecnico = $colaboradorRepository->findOneByCodigoIP($codigoTecnico);

                            if(!$tecnico){
                                $errores[] = "NO SE ENCONTRO AL TECNICO CON CODIGO IP: $codigoTecnico";
                            }
                            $orden->setTecnico($tecnico);

                            $serieRadio = $sheet->getCell("$map->radioColumn$counter")->getValue();
                            if($serieRadio){
                                $itemRadio = $seriadosRepository->findOneBySerie($serieRadio);
                                if(!$itemRadio){
                                    $itemRadio = new Seriado();
                                    $itemRadio->setEquipo($radio);
                                    $itemRadio->setSerie($serieRadio);
                                }
                                $orden->addEquipo($itemRadio);
                            }
                            $serieModem = "C700".$sheet->getCell("$map->modemColumn$counter")->getValue();
                            if($serieRadio){
                                $itemRadio = $seriadosRepository->findOneBySerie($serieRadio);
                                if(!$itemRadio){
                                    $itemRadio = new Seriado();
                                    $itemRadio->setEquipo($radio);
                                    $itemRadio->setSerie($serieRadio);
                                }
                                $orden->addEquipo($itemRadio);
                            }


                        }else{
                            $errores[] = "NO SE ENCONTRO LA SAN CON NUMERO $nsan";
                        }
                    }catch (\Exception $e){
                        $errores[] = "Error: ".$e->getMessage();
                    }
                    $counter++;
                    $nsan = $sheet->getCell("$map->sanColumn$counter")->getValue();
                }
                $em->flush();

            }
        }

        return $this->render('excel/ordenrd.html.twig', [
            'form'=>$form->createView()
        ]);


    }

    /**
     * @Route("/crearos", name="crearos")
     */
    public function nuevaos(Request $request): Response
    {

        $form = $this->createFormBuilder([])
            ->add('items', CollectionType::class,['entry_type'=>ItemOsType::class, 'allow_add'=>true, 'prototype'=>true])
            ->add('fuente')
            ->add('destino')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /* INICIO LECTOR Y ESCRITOR DE LA PLANTILA DE ORDENES DE SALIDA */
            $pathFile1 = $this->getParameter('excel_templates').'/os.xls';
            $newfile1  = $this->getParameter('movs_directory')."/os/".uniqid('os').'.xls';
            $reader1 = IOFactory::createReaderForFile($pathFile1);
            $spreadsheet1 = $reader1->load($pathFile1);
            $sheet1 = $spreadsheet1->getActiveSheet();
            $cells = $sheet1->rangeToArray('A1:X3');

            /* FIN LECTOR Y ESCRITOR DE LA PLANTILA DE ORDENES DE SALIDA */

        }

        return $this->render('excel/os.html.twig', [
            'controller_name' => 'ExcelController',
            'form'=>$form->createView()
        ]);


    }

    /**
     * @Route("/ordensalida", name="ordensalida")
     */
    public function ordenSalida(Request $request): Response
    {
        $form = $this->createFormBuilder([])
            ->add('archivo', FileType::class,['attr'=>['required'=>'required']])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form['archivo']->getData();
            if($file){
                $fecha = new \DateTime();
                //$strfecha = $fecha->format("Y-m-d");
                /* INICIO LECTOR Y ESCRITOR DE LA PLANTILA DE ORDENES DE SALIDA */
                $pathFile1 = $this->getParameter('excel_templates').'/os.xls';
                $newfile1  = $this->getParameter('movs_directory')."/os/".uniqid('os').'.xls';
                $reader1 = IOFactory::createReaderForFile($pathFile1);
                $spreadsheet1 = $reader1->load($pathFile1);
                $sheet1 = $spreadsheet1->getActiveSheet();
                $cells = $sheet1->rangeToArray('A1:X3');

                /* FIN LECTOR Y ESCRITOR DE LA PLANTILA DE ORDENES DE SALIDA */
                /* INICIO LECTOR Y ESCRITOR DE LA PLANTILA DE MOVIMIENTOS LPN */

                $pathFile2 = $this->getParameter('excel_templates').'/lpn.xls';
                $newfile2  = $this->getParameter('movs_directory')."/lpn/".uniqid('lpn').'.xls';
                $reader2 = IOFactory::createReaderForFile($pathFile2);
                $spreadsheet2 = $reader2->load($pathFile2);
                $sheet2 = $spreadsheet2->getActiveSheet();

                /* FIN LECTOR Y ESCRITOR DE LA PLANTILA DE MOVIMIENTOS  */


                /* INICIO DE LECTURA DE ARCHIVO CARGADO */
                $pathFile = $file->getPathName();
                $reader = IOFactory::createReaderForFile($pathFile);
                $spreadsheet = $reader->load($pathFile);
                $sheet = $spreadsheet->getActiveSheet();

                $counter  = 1;
                $counter2 = 1;

                //$em = $this->getDoctrine()->getManager();

                //$movRepository = $em->getRepository('App:Movimiento');

                $serial     = (string) $sheet->getCell("A$counter")->getFormattedValue();
                $auxFuente  = "";
                $auxDestino = "";

                while($serial){

                    $ubicaInvas     = $sheet->getCell("B$counter")->getValue();
                    $sitioInvas     = $sheet->getCell("C$counter")->getValue();
                    $idInstall      = $sheet->getCell("D$counter")->getValue();
                    $sitioIdInstall = $sheet->getCell("E$counter")->getValue();

                    $lpnindex = $counter+1;

                    $sheet2->getCell("A$lpnindex")->setValue($serial);
                    $sheet2->getCell("C$lpnindex")->setValue($idInstall);
                    $sheet2->getCell("D$lpnindex")->setValue($sitioIdInstall);

                    if ( $sitioInvas != $sitioIdInstall){
                        if($auxFuente != $sitioInvas || $auxDestino != $sitioIdInstall){
                            $sheet1->fromArray($cells,null, "A$counter2");
                            $fieldsRow = $counter2 + 1;
                            $ordenId = $sitioIdInstall."-".($fecha)->getTimestamp();
                            $sheet1->getCell("A$fieldsRow")->setValue($ordenId);
                            $sheet1->getCell("B$fieldsRow")->setValue($sitioInvas);
                            $sheet1->getCell("F$fieldsRow")->setValue($fecha->format("Y-m-d"));
                            $sheet1->getCell("M$fieldsRow")->setValue($sitioIdInstall);
                            $counter2+=3;
                        }
                        $sku = "";

                        if($serial[0]=="C"){
                            $sku = "1505216-0473";
                        }
                        if($serial[0]=="7"){
                            $sku = "1506688-1002";
                        }
                        $sheet1->getCell("A$counter2")->setValue($sku);
                        $sheet1->getCell("B$counter2")->setValue(1);
                        $sheet1->getCell("M$counter2")->setValue(1);
                        $sheet1->getCell("I$counter2")->setValue($serial);
                        $counter2++;
                    }

                    $auxFuente  = $sitioInvas;
                    $auxDestino = $sitioIdInstall;
                    $counter++;
                    $serial = (string)$sheet->getCell("A$counter")->getValue();

                }
                /* FIN DE LECTURA DE ARCHIVO CARGADO */

                $writer1 = IOFactory::createWriter($spreadsheet1, "Xls");
                $writer1->save($newfile1);
                $writer2 = IOFactory::createWriter($spreadsheet2, "Xls");
                $writer2->save($newfile2);
            }
        }

        return $this->render('excel/invas.html.twig', [
            'controller_name' => 'ExcelController',
            'form'=>$form->createView()
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
                    $counter++;
                    $nsan = $sheet->getCell("A$counter")->getValue();
                }
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
