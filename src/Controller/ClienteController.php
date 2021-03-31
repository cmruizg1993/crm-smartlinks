<?php

namespace App\Controller;

use App\Entity\Cliente;
use App\Entity\Dni;
use App\Entity\SAN;
use App\Form\ClienteType;
use App\Repository\ClienteRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @Route("/cliente")
 */
class ClienteController extends AbstractController
{
    /**
     * @Route("/", name="cliente_index", methods={"GET"})
     */
    public function index(ClienteRepository $clienteRepository): Response
    {
        return $this->render('cliente/index.html.twig', [
            'clientes' => $clienteRepository->findAll(),
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
     * @Route("/{id}/edit", name="cliente_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Cliente $cliente, FileUploader $uploader): Response
    {
        $form = $this->createForm(ClienteType::class, $cliente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $foto1 */
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
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('cliente_index');
        }

        return $this->render('cliente/edit.html.twig', [
            'cliente' => $cliente,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cliente_delete", methods={"DELETE"})
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
    public function buscar(Request $request, HttpClientInterface $client): Response
    {
        $ci = $request->request->get('dni');
        $data = [];
        if($ci){
            $uri = 'http://certificados.ministeriodegobierno.gob.ec/gestorcertificados/antecedentes/data.php';
            $response = $client->request('POST',$uri,
                ['body' => ['tipo' => 'getDataWsRc', 'ci'=>$ci]]
            );
            $data = json_decode($response->getContent());
        }
        return new JsonResponse($data);
    }

    /**
     * @Route("/sincronizar", name="sincronizar_cliente_san", methods={"POST"})
     */
    public function sincronizar(Request $request, HttpClientInterface $client): Response
    {
        $ci = $request->request->get('cedula');
        $nsan = $request->request->get('san');

        if($ci && $nsan){
            $ci = strlen($ci) == 9 ? "0".$ci:$ci;

            if(strlen($ci)==10){
                $uri = 'http://certificados.ministeriodegobierno.gob.ec/gestorcertificados/antecedentes/data.php';
                $response = $client->request('POST',$uri,
                    ['body' => ['tipo' => 'getDataWsRc', 'ci'=>$ci]]
                );

                $data = json_decode($response->getContent())[0];

                if(!$data->error){
                    $em = $this->getDoctrine()->getManager();
                    $tipodni = $em->getRepository('App:TipoDNI')->findOneByCodigo('CI');

                    /* @var $san SAN */
                    $san = $em->getRepository('App:SAN')->findOneByNumero($nsan);
                    if($san){
                        $dni = new Dni();
                        $dni->setNumero($ci);
                        $dni->setTipo($tipodni);
                        $cliente = $san->getCliente();
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
                            "mensaje"=>"No se ha encontrado la SAN $nsan"
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
                "mensaje"=>"No se recibio la san o cedula"
            ];
        }
        return new JsonResponse($data);
    }
}
