<?php

namespace App\Controller;

use App\Entity\OpcionCatalogo;
use App\Entity\Servicio;
use App\Form\ServicioType;
use App\Repository\OpcionCatalogoRepository;
use App\Repository\ServicioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Query\Expression\ExpressionBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/servicio")
 */
class ServicioController extends AbstractController
{
    /**
     * @Route("/", name="servicio_index", methods={"GET"})
     */
    public function index(ServicioRepository $servicioRepository): Response
    {
        return $this->render('servicio/index.html.twig', [
            'servicios' => $servicioRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="servicio_new", methods={"GET","POST"})
     */
    public function new(Request $request, OpcionCatalogoRepository $opcionCatalogoRepository): Response
    {
        $servicio = new Servicio();
        $form = $this->createForm(ServicioType::class, $servicio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $codigo = $servicio->getCodigoPorcentaje();
            $imp = $opcionCatalogoRepository->findOneByCodigoyCatalogo($codigo, 'iva');
            $porcentaje = $imp->getValorNumerico();
            $servicio->setPrecioSinImp($porcentaje);
            $entityManager->persist($servicio);
            $entityManager->flush();

            return $this->redirectToRoute('servicio_index');
        }

        return $this->render('servicio/new.html.twig', [
            'servicio' => $servicio,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="servicio_show", methods={"GET"})
     */
    public function show(Servicio $servicio): Response
    {
        return $this->render('servicio/show.html.twig', [
            'servicio' => $servicio,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="servicio_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Servicio $servicio, OpcionCatalogoRepository $opcionCatalogoRepository): Response
    {
        $form = $this->createForm(ServicioType::class, $servicio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $codigo = $servicio->getCodigoPorcentaje();
            $imp = $opcionCatalogoRepository->findOneByCodigoyCatalogo($codigo, 'iva');
            $porcentaje = $imp->getValorNumerico();
            $servicio->setPrecioSinImp($porcentaje);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('servicio_index');
        }

        return $this->render('servicio/edit.html.twig', [
            'servicio' => $servicio,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="servicio_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Servicio $servicio): Response
    {
        if ($this->isCsrfTokenValid('delete'.$servicio->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($servicio);
            $entityManager->flush();
        }

        return $this->redirectToRoute('servicio_index');
    }

    /**
     * @Route("/buscarServicio", name="buscar_servicio", methods={"POST"})
     */
    public function buscarServicio(Request $request): Response
    {

        $content = json_decode($request->getContent());
        $param = $content->param;
        //dump($content->param);
        $data = [];
        if($param){
            $em =$this->getDoctrine()->getManager();
            $servicios = $em->getRepository(Servicio::class)->findByParam($param);
            /* @var $serializer Serializer */
            $serializer = $this->get('serializer');
            $data = $serializer->normalize($servicios, null, [AbstractNormalizer::ATTRIBUTES=>
                [
                    'id',
                    'codigo',
                    'nombre',
                    'precio',
                    'precioSinImp',
                    'incluyeIva',
                    'codigoPorcentaje'
                ]
            ]);
            $setPorcentaje = function ($servicio) use ($em){
                $codigo = $servicio['codigoPorcentaje'];
                $impuesto = $em->getRepository(OpcionCatalogo::class)->findOneByCodigoyCatalogo($codigo, 'iva');
                $servicio['porcentaje'] = $impuesto->getValorNumerico();
                return$servicio;
            };
            $data = array_map($setPorcentaje, $data);
            //$html = $this->renderView('servicio/servicios.html.twig',['servicios'=>$data]);
        }
        return new JsonResponse($data);
    }
    /**
     * @Route("/obtener/servicio/reconexion", name="obtener_servicio_reconexion", methods={"GET"})
     */
    public function obtenerServicioReconexion
    (
        ServicioRepository $servicioRepository,
        OpcionCatalogoRepository $opcionCatalogoRepository,
        SerializerInterface $serializer
    ): Response
    {
        $servicio = $servicioRepository->obtenerServicioReconexion();
        $codigo = null;
        if($servicio) $codigo = $servicio->getCodigoPorcentaje();
        $impuesto = $opcionCatalogoRepository->findOneByCodigoyCatalogo($codigo, 'iva');
        $data = $serializer->normalize($servicio, null, [AbstractNormalizer::ATTRIBUTES=>
            [
                'id',
                'codigo',
                'nombre',
                'precio',
                'precioSinImp',
                'incluyeIva',
                'codigoPorcentaje'
            ]
        ]);
        $data["porcentaje"] = $impuesto ? $impuesto->getValorNumerico() : 0;
        return new JsonResponse($data);
    }
    /**
     * @Route("/admin/sincronizar", name="admin_sincronizar_servicios", methods={"GET"})
     */
    public function sincronizar
    (
        ServicioRepository $servicioRepository,
        OpcionCatalogoRepository $opcionCatalogoRepository,
        SerializerInterface $serializer,
        EntityManagerInterface $em
    ){
        if($this->isGranted('ROLE_SUPER_ADMIN')){
            $servicios = $servicioRepository->findAll();
            $impuestos = $opcionCatalogoRepository->findByCodigoCatalogo('iva');
            /* @var $impuestos ArrayCollection<OpcionCatalogo> */
            $impuestos = new ArrayCollection($impuestos);

            foreach ($servicios as $servicio){
                //$servicio->setPrecioSinImp();
                $codigo = $servicio->getCodigoPorcentaje();
                $criteria = Criteria::create();
                $criteria->where(Criteria::expr()->eq('codigo', (string) $codigo))->getFirstResult();
                /* @var $imp OpcionCatalogo */
                $imp = $impuestos->matching($criteria)->first();
                $porcentaje = $imp->getValorNumerico();
                $servicio->setPrecioSinImp($porcentaje);
            }
            $em->flush();
            $data = $serializer->normalize($servicios, null, [AbstractNormalizer::ATTRIBUTES=>[
                'id', 'nombre', 'precio', 'precioSinImp'
                ]]);
            return new JsonResponse($data);
        }
        return (new Response(null, 403));
    }

//obtenerServicioReconexion
}
