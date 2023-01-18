<?php

namespace App\Controller;

use App\Entity\CuentaPorCobrar;
use App\Entity\CuotaCuenta;
use App\Form\CuentaPorCobrarType;
use App\Repository\ClienteRepository;
use App\Repository\CuentaPorCobrarRepository;
use App\Repository\OpcionCatalogoRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/cuenta/por/cobrar")
 */
class CuentaPorCobrarController extends AbstractController
{
    /**
     * @Route("/", name="app_cuenta_por_cobrar_index", methods={"GET"})
     */
    public function index(CuentaPorCobrarRepository $cuentaPorCobrarRepository, SerializerInterface $serializer): Response
    {
        $cuentas = $cuentaPorCobrarRepository->findAllRegisters();

        $data = $serializer->normalize($cuentas, null, [AbstractNormalizer::ATTRIBUTES=>[
            'id','total', 'abono', 'cliente'=>['nombres', 'dni'=>['numero']],'observaciones','fecha', 'plazo'
        ]]);

        return $this->render('cuenta_por_cobrar/index.html.twig', [
            'cuentas' => $data
        ]);
    }

    /**
     * @Route("/new", name="app_cuenta_por_cobrar_new", methods={"GET", "POST"})
     */
    public function new(
        Request $request,
        CuentaPorCobrarRepository $cuentaPorCobrarRepository,
        ClienteRepository $clienteRepository,
        OpcionCatalogoRepository $opcionCatalogoRepository,
        UsuarioRepository $usuarioRepository,
        EntityManagerInterface $em
): Response
    {
        $method = $request->getMethod();
        if($method == Request::METHOD_POST){

            $content = json_decode($request->getContent(), true);
            $cuenta = new CuentaPorCobrar();
            $form = $this->createForm(CuentaPorCobrarType::class, $cuenta);
            $form->submit($content);

            /* CLIENTE */
            $cliente = $cuenta->getCliente();
            if(!$cliente || !$cliente->getId()) return new Response('cliente', 400);
            $cliente = $clienteRepository->find($cliente->getId());
            if(!$cliente) return new Response('cliente 2', 400);
            $cliente->addDeuda($cuenta);
            $cuenta->setCliente($cliente);
            $cuenta->totalizar($opcionCatalogoRepository);
            $cuenta->setUsuario($this->getUser());
            $user = $this->getUser();
            /* @var $usuario Usuario */
            $usuario = $usuarioRepository->findOneBy(['email'=>$user->getEmail()]);
            $empresa = $usuario->getEmpresa();
            if(!$empresa) return new Response('Empresa no válida', 400);
            $plazo = $cuenta->getPlazo();
            if(!$plazo || $plazo < 0) return new Response('Plazo no válido', 400);
            if($plazo == 0){
                $cuota = new CuotaCuenta();
                $cuota->setFechaVencimiento($cuota->getFechaVencimiento());
                $cuota->setValor($cuenta->getTotal());
                $cuota->setNumero(1);
                $cuenta->addCuota($cuota);
            }else{
                $valorCuota = round($cuenta->getTotal()/$plazo, 2);
                for($i = 1 ; $i <= $plazo; $i++){
                    $cuota = new CuotaCuenta();
                    /* @var $fecha \DateTime */
                    $fecha = clone $cuenta->getFecha();
                    $fecha->modify("+$i month");
                    $cuota->setFechaVencimiento($fecha);
                    $cuota->setValor($valorCuota);
                    $cuota->setNumero($i);
                    $cuenta->addCuota($cuota);
                }
            }
            $em->persist($cuenta);
            $em->flush();
            return new JsonResponse(['id'=>$cuenta->getId()], 200);
        }

        $cuentaPorCobrar = new CuentaPorCobrar();
        $form = $this->createForm(CuentaPorCobrarType::class, $cuentaPorCobrar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cuentaPorCobrarRepository->add($cuentaPorCobrar, true);

            return $this->redirectToRoute('app_cuenta_por_cobrar_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cuenta_por_cobrar/new.html.twig', [
            'cuenta_por_cobrar' => $cuentaPorCobrar,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_cuenta_por_cobrar_show", methods={"GET"})
     */
    public function show(CuentaPorCobrar $cuentaPorCobrar): Response
    {
        return $this->render('cuenta_por_cobrar/show.html.twig', [
            'cuenta_por_cobrar' => $cuentaPorCobrar,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_cuenta_por_cobrar_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CuentaPorCobrar $cuentaPorCobrar, CuentaPorCobrarRepository $cuentaPorCobrarRepository): Response
    {
        $form = $this->createForm(CuentaPorCobrarType::class, $cuentaPorCobrar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cuentaPorCobrarRepository->add($cuentaPorCobrar, true);

            return $this->redirectToRoute('app_cuenta_por_cobrar_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cuenta_por_cobrar/edit.html.twig', [
            'cuenta_por_cobrar' => $cuentaPorCobrar,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_cuenta_por_cobrar_delete", methods={"POST"})
     */
    public function delete(Request $request, CuentaPorCobrar $cuentaPorCobrar, CuentaPorCobrarRepository $cuentaPorCobrarRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cuentaPorCobrar->getId(), $request->request->get('_token'))) {
            $cuentaPorCobrarRepository->remove($cuentaPorCobrar, true);
        }

        return $this->redirectToRoute('app_cuenta_por_cobrar_index', [], Response::HTTP_SEE_OTHER);
    }
}
