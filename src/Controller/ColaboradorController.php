<?php

namespace App\Controller;

use App\Entity\Cargo;
use App\Entity\Cliente;
use App\Entity\Colaborador;
use App\Form\ColaboradorType;
use App\Form\VendedorType;
use App\Repository\ClienteRepository;
use App\Repository\ColaboradorRepository;
use App\Service\WhatsappApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

/**
 * @Route("/colaborador")
 */
class ColaboradorController extends AbstractController
{
    private $verifyEmailHelper;
    private $mailer;

    public function __construct(VerifyEmailHelperInterface $helper, MailerInterface $mailer)
    {
        $this->verifyEmailHelper = $helper;
        $this->mailer = $mailer;
    }

    /**
     * @Route("/", name="colaborador_index", methods={"GET"})
     */
    public function index(ColaboradorRepository $colaboradorRepository, WhatsappApi $wtp): Response
    {


        return $this->render('colaborador/index.html.twig', [
            'colaboradors' => $colaboradorRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="colaborador_new", methods={"GET","POST"})
     */
    public function new(Request $request,  UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $colaborador = new Colaborador();
        $form = $this->createForm(ColaboradorType::class, $colaborador);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            $cargo = $colaborador->getCargo()->getCodigo();
            if($cargo == Cargo::OPERADOR){
                $colaborador->getUsuario()->setRoles(['ROLE_ADMIN']);
            }
            $plain = $form['usuario']['plainPassword']->getData();
            $pass = $passwordEncoder->encodePassword($colaborador->getUsuario(),$plain);
            $colaborador->getUsuario()->setPassword($pass);
            $entityManager->persist($colaborador);
            $entityManager->flush();

            return $this->redirectToRoute('colaborador_index');
        }
        $provincias = $entityManager->getRepository('App:Provincia')->findAll();
        return $this->render('colaborador/new.html.twig', [
            'colaborador' => $colaborador,
            'form' => $form->createView(),
            'provincias'=>$provincias
        ]);
    }

    /**
     * @Route("/{id}", name="colaborador_show", methods={"GET"})
     */
    public function show(Colaborador $colaborador): Response
    {
        return $this->render('colaborador/show.html.twig', [
            'colaborador' => $colaborador,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="colaborador_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Colaborador $colaborador, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(ColaboradorType::class, $colaborador);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            //$plain = $form['usuario']['plainPassword']->getData();
            //$pass = $passwordEncoder->encodePassword($colaborador->getUsuario(),$plain);
            //$colaborador->getUsuario()->setPassword($pass);
            $entityManager->flush();

            return $this->redirectToRoute('colaborador_index');
        }
        $provincias = $entityManager->getRepository('App:Provincia')->findAll();
        return $this->render('colaborador/edit.html.twig', [
            'colaborador' => $colaborador,
            'form' => $form->createView(),
            'provincias'=>$provincias
        ]);
    }

    /**
     * @Route("/{id}", name="colaborador_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Colaborador $colaborador): Response
    {
        if ($this->isCsrfTokenValid('delete'.$colaborador->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($colaborador);
            $entityManager->flush();
        }

        return $this->redirectToRoute('colaborador_index');
    }

    /**
     * @Route("/registro/vendedor", name="vendedor_new", methods={"GET","POST"})
     */
    public function newVendedor(Request $request,  UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $colaborador = new Colaborador();
        $form = $this->createForm(VendedorType::class, $colaborador);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            $plain = $form['usuario']['plainPassword']->getData();
            $pass = $passwordEncoder->encodePassword($colaborador->getUsuario(),$plain);
            $colaborador->getUsuario()->setPassword($pass);
            $cargo = $em->getRepository('App:Cargo')->findOneByCodigo('VN');
            if($cargo){
                $colaborador->setCargo($cargo);
                $em->persist($colaborador);
                $em->flush();
            }
            return $this->redirectToRoute('app_login');
        }

        return $this->render('colaborador/vendedor.html.twig', [
            'colaborador' => $colaborador,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/buscar", name="buscar_colaborador", methods={"POST"})
     */
    public function buscar(Request $request, HttpClientInterface $client, ColaboradorRepository $colaboradorRepository): Response
    {
        $ci = $request->request->get('dni');

        $data = [];

        /* @var $colaborador Colaborador */
        $colaborador = $colaboradorRepository->findOneByCedula($ci);

        if($colaborador){
            $data[0]["name"] = $colaborador->getNombres();
            $data[0]["residence"] = '';//$cliente->getResidencia();
            $data[0]["streets"] = $colaborador->getDireccion();
            /*
            $data[0]["nationality"] = $cliente->getNacionalidad();

            $data[0]["fingerprint"] = $cliente->getFingerprint();
            $data[0]["civilstate"] = $cliente->getEstadoCivil();
            $data[0]["dob"] = $cliente->getFechaNacimiento()->format('d/m/Y');
            $data[0]["email"] = $cliente->getEmail();
            $data[0]["dni_type"] = $cliente->getDni()->getTipo()->getId();
            $data[0]["phone"] = $cliente->getTelefono();
            $data[0]["fix_phone"] = $cliente->getTelefonoFijo();
            $data[0]["exp_date"] = $cliente->getDni()->getFechaExp() ? $cliente->getDni()->getFechaExp()->format('d/m/Y'):null;
            */

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
}
