<?php

namespace App\Controller;

use App\Entity\Cliente;
use App\Entity\Dni;
use App\Entity\Contrato;
use App\Form\ClienteType;
use App\Repository\ClienteRepository;
use App\Repository\DniRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @Route("/cliente")
 */
class ClienteController extends AbstractController
{
    /**
     * @Route("/", name="cliente_index", methods={"GET"})
     */
    public function index(ClienteRepository $clienteRepository, SerializerInterface $serializer): Response
    {
        $clientes =$clienteRepository->getAll();
        $fields = ['id', 'nombres', 'email', 'direccion', 'telefono', 'telefonoFijo', 'cedula'];
        $data = $serializer->normalize($clientes, null, [AbstractNormalizer::ATTRIBUTES=>[
            'id', 'nombres', 'email', 'direccion', 'telefono', 'telefonoFijo', 'dni'=>['numero']
        ]]);
        return $this->render('cliente/index.html.twig', [
            'clientes' => $data,
            'campos' => $fields
        ]);
    }

    /**
     * @Route("/new", name="cliente_new", methods={"GET","POST"})
     */
    public function new(Request $request, FileUploader $uploader): Response
    {
        $cliente = new Cliente();
        $form = $this->createForm(ClienteType::class, $cliente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cliente);
            $entityManager->flush();
            /** @var UploadedFile $foto1 */
            /*
            $foto1 = $form['dni']['foto_frontal']->getData();
            if($foto1){
                $fn = $uploader->upload($foto1,$cliente->getDni()->getId());
                if($fn){
                    $cliente->getDni()->setFotoFrontal($fn);
                }
            }
            $foto2 = $form['dni']['foto_posterior']->getData();
            if($foto2){
                $fn = $uploader->upload($foto2,$cliente->getDni()->getId());
                if($fn){
                    $cliente->getDni()->setFotoPosterior($fn);
                }
            }
            $otro = $form['otro']->getData();
            if($otro){
                $fn = $uploader->upload($otro,$cliente->getDni()->getId());
                if($fn){
                    $cliente->setOtro($fn);
                }
            }
            */
            $entityManager->flush();
            return $this->redirectToRoute('cliente_index');
        }

        return $this->render('cliente/new.html.twig', [
            'cliente' => $cliente,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cliente_show", methods={"GET"})
     */
    public function show(Cliente $cliente): Response
    {
        return $this->render('cliente/show.html.twig', [
            'cliente' => $cliente,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="cliente_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Cliente $cliente, FileUploader $uploader): Response
    {
        $form = $this->createForm(ClienteType::class, $cliente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $foto1 */
            /*
            $foto1 = $form['dni']['foto_frontal']->getData();
            if($foto1){
                $fn = $uploader->upload($foto1,$cliente->getDni()->getId());
                if($fn){
                    $cliente->getDni()->setFotoFrontal($fn);
                }
            }
            $foto2 = $form['dni']['foto_posterior']->getData();
            if($foto2){
                $fn = $uploader->upload($foto2,$cliente->getDni()->getId());
                if($fn){
                    $cliente->getDni()->setFotoPosterior($fn);
                }
            }
            $otro = $form['otro']->getData();
            if($otro){
                $fn = $uploader->upload($otro,$cliente->getDni()->getId());
                if($fn){
                    $cliente->setOtro($fn);
                }
            }
            */
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('cliente_index');
        }

        return $this->render('cliente/edit.html.twig', [
            'cliente' => $cliente,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="cliente_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Cliente $cliente): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cliente->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cliente);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cliente_index');
        //
    }
    /**
     * @Route("/buscar", name="buscar_cliente", methods={"POST"})
     */
    public function buscar(Request $request, HttpClientInterface $client, ClienteRepository $clienteRepository): Response
    {
        $ci = $request->request->get('dni');

        $data = [];

        /* @var $cliente Cliente */
        $cliente = $clienteRepository->findOneByNumeroDni($ci);

        if($cliente){
            $data[0] = $cliente->getData();
        }else{
            if($ci){
                $uri = 'http://certificados.ministeriodegobierno.gob.ec/gestorcertificados/antecedentes/data.php';
                $response = $client->request('POST',$uri,
                    ['body' => ['tipo' => 'getDataWsRc', 'ci'=>$ci]]
                );
                $data = json_decode($response->getContent());
            }
        }

        return new JsonResponse($data);
    }

    /**
     * @Route("/sincronizar", name="sincronizar_cliente_Contrato", methods={"POST"})
     */
    public function sincronizar(Request $request, HttpClientInterface $client): Response
    {
        $ci = $request->request->get('cedula');
        $nContrato = $request->request->get('Contrato');

        if($ci && $nContrato){
            $ci = strlen($ci) == 9 ? "0".$ci:$ci;

            if(strlen($ci)==10){
                $uri = 'http://certificados.ministeriodegobierno.gob.ec/gestorcertificados/antecedentes/data.php';
                $response = $client->request('POST',$uri,
                    ['body' => ['tipo' => 'getDataWsRc', 'ci'=>$ci]]
                );

                $data = json_decode($response->getContent())[0];

                if(!$data->error){
                    $em = $this->getDoctrine()->getManager();
                    $tipodni = $em->getRepository(TipoDNI::class)->findOneByCodigo('CI');

                    /* @var $Contrato Contrato */
                    $Contrato = $em->getRepository(Contrato::class)->findOneByNumero($nContrato);
                    if($Contrato){
                        $dni = new Dni();
                        $dni->setNumero($ci);
                        $dni->setTipo($tipodni);
                        $cliente = $Contrato->getCliente();
                        $cliente->setNombres($data->name);
                        $cliente->setGenero($data->genre);
                        $fecha = \DateTime::createFromFormat('d/m/Y',$data->dob);
                        $cliente->setFechaNacimiento($fecha);
                        $cliente->setNacionalidad($data->nationality);
                        $cliente->setResidencia($data->residence);
                        $cliente->setDireccion($data->streets);
                        $cliente->setFingerprint($data->fingerprint);
                        $cliente->setEstadoCivil($data->civilstate);
                        $cliente->setDni($dni);
                        $em->flush();
                    }else{
                        $data = [
                            "error"=>true,
                            "mensaje"=>"No se ha encontrado la Contrato $nContrato"
                        ];
                    }
                }else{
                    $data = [
                        "error"=>true,
                        "mensaje"=>$data->error
                    ];
                }
            }else{
                $data = [
                    "error"=>true,
                    "mensaje"=>"tipo de documento desconocido"
                ];
            }
        }else{
            $data = [
                "error"=>true,
                "mensaje"=>"No se recibio la Contrato o cedula"
            ];
        }
        return new JsonResponse($data);
    }
}
