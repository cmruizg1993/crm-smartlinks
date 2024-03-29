<?php

namespace App\Controller;


use App\Entity\Cliente;
use App\Entity\Colaborador;
use App\Entity\Dni;
use App\Entity\Equipo;
use App\Entity\Orden;
use App\Entity\Plan;
use App\Entity\Contrato;
use App\Entity\Seriado;
use App\Entity\TipoOrden;
use App\Form\ItemOsType;
use App\Repository\ClienteRepository;
use App\Repository\ContratoRepository;
use App\Repository\ParroquiaRepository;
use App\Repository\ProvinciaRepository;
use App\Repository\ServicioRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpClient\HttplugClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
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
                'ContratoColumn' => 'A', 'codigoInstaladorColumn' => 'B','fechaColumn' => 'C',
                'radioColumn'=>'D','modemColumn'=>'E','tipoOrdenColumn' => 'F'
            ];
            $file = $form['archivo']->getData();

            $errores = [];

            if($file){
                $pathFile = $file->getPathName();
                ////dump($pathFile);
                $reader = IOFactory::createReaderForFile($pathFile);
                $spreadsheet = $reader->load($pathFile);
                $sheet = $spreadsheet->getActiveSheet();
                $counter = 2;

                $em = $this->getDoctrine()->getManager();

                $ContratoRepository = $em->getRepository(Contrato::class);

                $colaboradorRepository = $em->getRepository(Colaborador::class);

                $seriadosRepository = $em->getRepository(Seriado::class);

                $tipoReparacion = $em->getRepository(TipoOrden::class)->findOneByCodigo('X');

                $tipoDesinstalacion = $em->getRepository(TipoOrden::class)->findOneByCodigo('D');

                $estado = $em->getRepository(EstadoOrden::class)->findOneByCodigo('C');

                $nContrato = $sheet->getCell("$map->ContratoColumn$counter")->getValue();
                /* @var $modem Equipo */
                $modem = $em->getRepository(Equipo::class)->findOneBySku("1505216-0473");
                /* @var $radio Equipo */
                $radio = $em->getRepository(Equipo::class)->findOneBySku("1506688-1002");

                while($nContrato){
                    try{
                        $Contrato = $ContratoRepository->findOneByNumero("HEC2000$nContrato");
                        if($Contrato) {
                            $orden = new Orden();

                            $tipo = $sheet->getCell("$map->tipoOrdenColumn$counter")->getValue();
                            if($tipo==TipoOrden::DESINSTALACION){
                                $orden->setTipo($tipoDesinstalacion);
                            }elseif($tipo==TipoOrden::REPARACION){
                                $orden->setTipo($tipoReparacion);
                            }else{
                                $errores[] = "CODIGO DE INSTALACION: $tipo NO VÁLIDO EN LA Contrato $nContrato";
                            }

                            $orden->setContrato($Contrato);

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
                            $errores[] = "NO SE ENCONTRO LA Contrato CON NUMERO $nContrato";
                        }
                    }catch (\Exception $e){
                        $errores[] = "Error: ".$e->getMessage();
                    }
                    $counter++;
                    $nContrato = $sheet->getCell("$map->ContratoColumn$counter")->getValue();
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

                //$movRepository = $em->getRepository(Movimiento::class);

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
                'ContratoColumn' => 'B','estadoContratoColumn' => 'C','estadoContratoColumn' => 'D','fechaOrdenColumn' => 'E',
                'nombreClienteColumn' => 'H', 'emailClienteColumn' => 'I','cantonColumn' => 'J','provinciaColumn' => 'K',
                'nroOrdenColumn' => 'Q', 'tipoOrdenColumn' => 'S', 'estadoOrdenColumn' => 'T', 'fechaOrdenColumn' => 'U',
                'codigoInstaladorColumn' => 'Y', 'nombreInstaladorColumn' => 'Z', 'fechaInstalacionColumn' => 'AA',
                'nombrePlanColumn'=>'F'
            ];
            $file = $form['archivo']->getData();
            if($file){
                $pathFile = $file->getPathName();
                ////dump($pathFile);
                $reader = IOFactory::createReaderForFile($pathFile);
                $spreadsheet = $reader->load($pathFile);
                $sheet = $spreadsheet->getActiveSheet();
                $counter = 3;
                $em = $this->getDoctrine()->getManager();
                $ContratoRepository = $em->getRepository(Contrato::class);
                $planRepository = $em->getRepository(Plan::class);

                $colaboradorRepository = $em->getRepository(Colaborador::class);
                $tipo = $em->getRepository(TipoOrden::class)->findOneByCodigo('I');
                $estado = $em->getRepository(EstadoOrden::class)->findOneByCodigo('P');

                $servicio = $em->getRepository(Servicio::class)->findOneByNombre('INTERNET SATELITAL');

                $nContrato = $sheet->getCell("$map->ContratoColumn$counter")->getValue();
                while($nContrato){

                    $exist = $ContratoRepository->findOneByNumero($nContrato);
                    if(!$exist) {

                        $Contrato = new Contrato();
                        $Contrato->setNumero($nContrato);
                        $Contrato->setEstado($sheet->getCell("$map->estadoContratoColumn$counter")->getValue());
                        $Contrato->setEstadoContrato($sheet->getCell("$map->estadoContratoColumn$counter")->getValue());
                        $Contrato->setFecha(new \DateTime($sheet->getCell("$map->fechaOrdenColumn$counter")->getFormattedValue()));
                        $Contrato->setDireccion($sheet->getCell("$map->cantonColumn$counter")->getValue() . ", " . $sheet->getCell("$map->provinciaColumn$counter")->getValue());

                        $cliente = new Cliente();
                        $cliente->setNombres($sheet->getCell("$map->nombreClienteColumn$counter")->getValue());
                        $cliente->setEmail($sheet->getCell("$map->emailClienteColumn$counter")->getValue());
                        $cliente->setDireccion($sheet->getCell("$map->cantonColumn$counter")->getValue() . ", " . $sheet->getCell("$map->provinciaColumn$counter")->getValue());
                        $cliente->addContrato($Contrato);
                        $Contrato->setCliente($cliente);

                        $orden = new Orden();
                        $orden->setTipo($tipo);
                        if ($sheet->getCell("$map->nroOrdenColumn$counter")->getValue()) {
                            $orden->setCodigo($sheet->getCell("$map->nroOrdenColumn$counter")->getValue());
                        }
                        $orden->setFecha(new \DateTime($sheet->getCell("$map->fechaOrdenColumn$counter")->getFormattedValue()));
                        $orden->setEstado($estado);
                        $Contrato->addOrdene($orden);
                        $nombre = $sheet->getCell("$map->nombrePlanColumn$counter")->getValue();
                        $plan = $planRepository->findOneByNombre($nombre);

                        if(!$plan && $nombre){
                            $plan = new Plan();
                            $plan->setServicio($servicio);
                            $plan->setNombre($nombre);
                            $plan->setActivo(true);
                            $plan->addContrato($Contrato);
                            $em->persist($plan);
                            $em->flush();
                        }

                        $Contrato->setPlan($plan);


                        $orden->setContrato($Contrato);

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

                        $em->persist($Contrato);
                    }
                    $counter++;
                    $nContrato = $sheet->getCell("$map->ContratoColumn$counter")->getValue();
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
                $ContratoRepository = $em->getRepository(Contrato::class);

                $nContrato = $sheet->getCell("A$counter")->getValue();
                while($counter < 20){
                    $counter++;
                    $nContrato = $sheet->getCell("A$counter")->getValue();
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
                        //dump( 'Error: '.$exception->getMessage());

                        throw $exception;
                    }
                );
            $response = $promise->wait()->getBody()->getContents();
            //dump($response);




            $httpClient->wait();

        }
    }

    /**
     * @Route("/carga/masiva/clientes", name="carga_masiva_clientes")
     */
    public function cargaClientes(
        Request $request,
        ParroquiaRepository $parroquiaRepository,
        EntityManagerInterface $em
    ): Response
    {
        set_time_limit(450);
        $starttime = microtime(true);
        /* do stuff here */

        //return new Response(null, 200);
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
                $mapping = [
                    'cedula' => 'B',
                    'nombre' => 'D',
                    'razon' => 'C',
                    'direccion'=> 'E',
                    'telefono1' => 'G',
                    'telefono2'=>'F',
                    'email'=>'H',
                    'provincia'=>'R',
                    'referencia'=>'U'
                ];
                //$nContrato = $sheet->getCell("A$counter")->getValue();
                $nrows = $sheet->getHighestRow();
                $cache = [];
                $clientes = [];
                while ($counter<$nrows){
                    $counter++;
                    $cliente = new Cliente();
                    $dni = new Dni();
                    $numeroDni = $sheet->getCell($mapping['cedula']."$counter")->getValue();
                    $nombre = $sheet->getCell($mapping['nombre']."$counter")->getValue();
                    $razon = $sheet->getCell($mapping['razon']."$counter")->getValue();
                    $direccion = $sheet->getCell($mapping['direccion']."$counter")->getValue();
                    $telefono1 = $sheet->getCell($mapping['telefono1']."$counter")->getValue();
                    $telefono2 = $sheet->getCell($mapping['telefono2']."$counter")->getValue();
                    $email = $sheet->getCell($mapping['email']."$counter")->getValue();
                    $parroquia = $sheet->getCell($mapping['provincia'].$counter)->getValue();
                    $referencia = $sheet->getCell($mapping['referencia'].$counter)->getValue();

                    if(!$razon) {
                        continue;
                    }
                    $cliente->setNombres($razon);
                    $cliente->setNombreComercial($nombre);
                    $cliente->setDireccion($direccion);
                    $cliente->setTelefono($telefono1);
                    $cliente->setTelefonoFijo($telefono2);
                    $cliente->setEmail($email);
                    $cliente->setReferenciaDireccion($referencia);

                    if(isset($cache["$parroquia"])){
                        $cliente->setParroquia($cache["$parroquia"]);
                    }else{
                        $cache["$parroquia"] = $parroquiaRepository->findOneByName($parroquia);
                        $cliente->setParroquia($cache["$parroquia"]);
                    }
                    $tipodni = strlen($numeroDni) == 10 ? Dni::CEDULA: Dni::RUC;
                    if($tipodni === '9999999999999') $tipodni = Dni::CONSUMIDOR;
                    $dni->setTipo($tipodni);
                    $dni->setNumero($numeroDni);
                    $cliente->setDni($dni);
                    if(!isset($clientes["$numeroDni"])){
                        $clientes["$numeroDni"] = $numeroDni;
                        $em->persist($cliente);
                    }
                }
                $em->flush();
                $endtime = microtime(true);
                $timediff = $endtime - $starttime;
                //dump($timediff);
            }
        }

        return $this->render('excel/carga_clientes.html.twig', [
            'controller_name' => 'ExcelController',
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/carga/masiva/contratos", name="carga_masiva_contratos")
     */
    public function cargaContratos(
        Request $request,
        ClienteRepository $clienteRepository,
        ServicioRepository $servicioRepository,
        ContratoRepository $contratoRepository,
        EntityManagerInterface $em,
        SerializerInterface $serializer
    ): Response
    {
        set_time_limit(450);
        $starttime = microtime(true);
        /* do stuff here */

        //return new Response(null, 200);
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



                $response = new StreamedResponse();
                //$response->headers->set('Content-Type', 'application/json');
                $response->setCallback(function () use ($sheet, $em, $clienteRepository, $servicioRepository, $serializer){
                    $counter = 1;
                    $mapping = [
                        'contrato'=>'A',
                        'cedula' => 'B',
                        'ppp' => 'C',
                        'nodo' => 'D',
                        'plan'=> 'E',
                        'vlan' => 'F',
                        'fecha' => 'G'
                    ];
                    $nrows = $sheet->getHighestRow();
                    $cache = [];
                    $contratos = [];
                    echo '[';
                    while ($counter<$nrows){
                        $counter++;
                        $contrato = new Contrato();
                        $cliente = new Cliente();
                        $dni = new Dni();
                        $numeroContrato = $sheet->getCell($mapping['contrato']."$counter")->getValue();
                        $numeroDni = $sheet->getCell($mapping['cedula']."$counter")->getValue();
                        $pppoe = $sheet->getCell($mapping['ppp']."$counter")->getValue();
                        $nodo = $sheet->getCell($mapping['nodo']."$counter")->getValue();
                        $plan = $sheet->getCell($mapping['plan']."$counter")->getValue();
                        $vlan = $sheet->getCell($mapping['vlan']."$counter")->getValue();
                        $fecha = $sheet->getCell($mapping['fecha']."$counter")->getValue();
                        $contrato->setNumero($numeroContrato);
                        $contrato->setPppoe($pppoe);
                        $contrato->setNodo($nodo);
                        $contrato->setVlan($vlan);
                        $contrato->setFecha(new \DateTime($fecha));
                        $cliente = $clienteRepository->findOneByNumeroDni($numeroDni);
                        $contrato->setCliente($cliente);

                        if(isset($cache["$plan"])){
                            $contrato->setPlan($cache["$plan"]);
                        }else{
                            $cache["$plan"] = $servicioRepository->obtenerServicioByCod($plan);
                            $contrato->setPlan($cache["$plan"]);
                        }
                        $data = $serializer->normalize($contrato, null, [AbstractNormalizer::ATTRIBUTES=>[
                            'numero', 'cliente'=>['nombres'], 'plan'=>['codigo']
                        ]]);
                        echo json_encode($data);
                        echo $counter == $nrows ? '':',';
                        flush();
                        if($cliente){
                            $direccion = $cliente->getDireccion();
                            $contrato->setDireccion($direccion);
                            $em->persist($contrato);
                        }
                        //
                    }
                    echo ']';
                    $em->flush();
                });

                //$response->send();
                return $response;
                //$em->flush();
                $endtime = microtime(true);
                $timediff = $endtime - $starttime;
                //dump($timediff);

            }
        }

        return $this->render('excel/carga_clientes.html.twig', [
            'controller_name' => 'ExcelController',
            'form'=>$form->createView()
        ]);
    }
}
