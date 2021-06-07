<?php

namespace App\Controller;

use App\Entity\FormaPago;
use App\Entity\Pago;
use App\Entity\SAN;
use App\Entity\Solicitud;
use App\Entity\Usuario;
use App\Form\PagoType;
use App\Form\SolicitudType;
use App\Repository\ClienteRepository;
use App\Repository\FormaPagoRepository;
use App\Repository\SolicitudRepository;
use App\Service\FileUploader;
use App\Service\WhatsappApi;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route("/solicitud")
 */
class SolicitudController extends AbstractController
{
    /**
     * @Route("/", name="solicitud_index", methods={"GET"})
     */
    public function index(SolicitudRepository $solicitudRepository): Response
    {
        $user = $this->getUser()->getColaborador();
        $esAdmin = $this->isGranted('ROLE_ADMIN');
        if($esAdmin){
            $solicitudes = $solicitudRepository->findAll();
        }else{
            $solicitudes = $solicitudRepository->findByVendedor($user);
        }
        return $this->render('solicitud/index.html.twig', [
            'solicituds' => $solicitudes,
        ]);
    }

    /**
     * @Route("/new", name="solicitud_new", methods={"GET","POST"})
     */
    public function new(Request $request, FormaPagoRepository $formaPagoRepository, ClienteRepository $clienteRepository, MailerInterface $mailer, FileUploader $uploader): Response
    {
        $solicitud = new Solicitud();

        $form = $this->createForm(SolicitudType::class, $solicitud);
        $form->handleRequest($request);

        $fpago = $solicitud->getFormaPago();
        if(!$fpago){
            $fpago = $formaPagoRepository->findOneByCodigo('EF');
            $solicitud->setFormaPago($fpago);
            $solicitud->setCuentaBancaria(null);
        }



        if ($form->isSubmitted() && $form->isValid()) {
            /* @var $user Usuario */
            $user = $this->getUser();
            if (!$this->isGranted('ROLE_ADMIN')){
                $colaborador = $user->getColaborador();
                if($colaborador){
                    $solicitud->setVendedor($colaborador);
                }
            }

            $solicitud->setEstado('PENDIENTE');
            $solicitud->setFecha(new \DateTime());
            /** @var UploadedFile $foto1 */
            $foto1 = $form['aprobacion']->getData();
            if($foto1){
                $fn1 = $uploader->upload($foto1,'aprobacionEquifax');
                if($fn1){
                    $solicitud->setAprobacionEquifax($fn1);
                }
            }
            /** @var UploadedFile $foto2 */
            $foto2 = $form['validacion']->getData();
            if($foto2){
                $fn2 = $uploader->upload($foto2,'validacionEquifax');
                if($fn2){
                    $solicitud->setCapturaEquifax($fn2);
                }
            }

            $old_client = $clienteRepository->findOneByNumeroDni($solicitud->getCliente()->getDni()->getNumero());
            if($old_client){
                $solicitud->setCliente($old_client);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($solicitud);
            $entityManager->flush();
            $context = [];
            $context['colaborador']=$colaborador?$colaborador->getNombres():'';
            $context['url']  = $this->generateUrl('solicitud_index');
            $this->notificar($mailer,'Nueva Solicitud de Venta', 'solicitud/emailNuevaSolicitud.html.twig',$context);

            return $this->redirectToRoute('solicitud_index');
        }

        return $this->render('solicitud/new.html.twig', [
            'solicitud' => $solicitud,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="solicitud_show", methods={"GET"})
     */
    public function show(Solicitud $solicitud): Response
    {
        return $this->render('solicitud/show.html.twig', [
            'solicitud' => $solicitud,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="solicitud_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Solicitud $solicitud, ClienteRepository $clienteRepository): Response
    {
        $form = $this->createForm(SolicitudType::class, $solicitud);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $old_client = $clienteRepository->findOneByNumeroDni($solicitud->getCliente()->getDni()->getNumero());
            if($old_client){
                $solicitud->setCliente($old_client);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('solicitud_index');
        }

        return $this->render('solicitud/edit.html.twig', [
            'solicitud' => $solicitud,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="solicitud_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Solicitud $solicitud): Response
    {
        if ($this->isCsrfTokenValid('delete'.$solicitud->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($solicitud);
            $entityManager->flush();
        }

        return $this->redirectToRoute('solicitud_index');
    }
    /**
     * @Route("/aprobar/{id}", name="solicitud_aprobar", methods={"POST","GET"})
     */
    public function aprobar(Request $request, Solicitud $solicitud, FileUploader $uploader, WhatsappApi $wtp, LoggerInterface $logger): Response
    {
        if($solicitud->getEstado()!='PENDIENTE'){
            return $this->redirectToRoute('solicitud_index');
        }
        $san = new SAN();
        $san->setDireccion($solicitud->getCliente()->getDireccion());
        $form = $this->createFormBuilder($san)
            ->add('numero')
            ->add('valorSuscripcion')
            ->add('capturaSan',FileType::class,[
                'mapped'=>false
            ])
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $solicitud->setAprobar(true);
            $solicitud->setEstado('APROBADA');
            $san->setVendedor($solicitud->getVendedor());
            $san->setFecha(new \DateTime());
            $san->setCliente($solicitud->getCliente());
            $san->setPlan($solicitud->getPlan());
            $em = $this->getDoctrine()->getManager();
            /** @var UploadedFile $foto1 */
            $foto1 = $form['capturaSan']->getData();
            if($foto1){
                $fn = $uploader->upload($foto1,'solicitudesAprobadas');
                if($fn){
                    $solicitud->setCapturaEquifax($fn);
                }
            }
            $solicitud->setSan($san);
            $em->persist($san);
            $em->flush();
            $to = $solicitud->getVendedor() ? $solicitud->getVendedor()->getUsuario()->getPhone(): '';
            $nro = $solicitud->getId();
            $nroSan = $san->getNumero();
            $valor = $san->getValorSuscripcion();
            if($to){
                $message = "*Makrocel* notifica la *APROBACION* de la solicitud con *NRO $nro*. Ingresada a través de la aplicacion web. 
            El nro de SAN asociado es $nroSan y el valor de suscripcion es de: USD  $valor. *Gracias por formar parte de nuestro equipo.*";
                $cuid = uniqid();
                $response = $wtp->send(urlencode($message),$to,$cuid);
                $logger->debug($response->getContent());
            }
            return $this->redirectToRoute('solicitud_index');
        }
        return $this->render('solicitud/aprobar.html.twig', [
            'solicitud' => $solicitud,
            'form' => $form->createView(),
            's_a_n'=>$san
        ]);
    }
    /**
     * @Route("/rechazar/{id}", name="solicitud_rechazar", methods={"GET"})
     */
    public function rechazar(Request $request, Solicitud $solicitud, WhatsappApi $wtp, LoggerInterface $logger): Response
    {
        if($solicitud->getEstado()!='PENDIENTE'){
            return $this->redirectToRoute('solicitud_index');
        }
        $solicitud->setEstado('RECHAZADA');
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        $to = $solicitud->getVendedor()->getUsuario()->getPhone();
        $nro = $solicitud->getId();
        $message = "*Makrocel* lamenta notificar que la solicitud con *NRO $nro*, ingresada a traves de la aplicacion web, ha sido *RECHAZADA*. 
            Para mayor informacion comuniquese con un asesor. *Gracias por formar parte de nuestro equipo.*";
        $cuid = uniqid();
        $response = $wtp->send(urlencode($message),$to,$cuid);
        $logger->debug($response->getContent());
        return $this->redirectToRoute('solicitud_index');
    }
    /**
     * @Route("/pagar/{id}", name="solicitud_pagar", methods={"GET","POST"})
     */
    public function pagar(Request $request, Solicitud $solicitud, MailerInterface $mailer, FileUploader $uploader): Response
    {
        if($solicitud->getEstado()!='APROBADA'){
            return $this->redirectToRoute('solicitud_index');
        }
        $pago = new Pago();
        $form = $this->createForm(PagoType::class, $pago);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() ){
            $solicitud->setPago($pago);
            /** @var UploadedFile $foto1 */
            $foto1 = $form['captura']->getData();
            if($foto1){
                $fn = $uploader->upload($foto1,'pago-solicitud');
                if($fn){
                    $pago->setComprobante($fn);
                }
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($pago);
            $em->flush();

            $context = [];
            $colaborador = $this->getUser()->getColaborador();
            $context['colaborador']=$colaborador?$colaborador->getNombres():'';
            $context['id']=$solicitud->getId();
            $context['url'] = $this->generateUrl('solicitud_show', array('id' => $solicitud->getId()), UrlGeneratorInterface::ABSOLUTE_URL);

            $this->notificar($mailer,'Se ha reportado el pago de una solicitud', 'solicitud/emailPagoSolicitud.html.twig',$context);
            return $this->redirectToRoute('solicitud_index');
        }

        return $this->render('pago/new.html.twig',[
            'form'=>$form->createView()
        ]);
    }
    private function notificar(MailerInterface $mailer, $subject, $template, $context = []){

        $emailTemplate =new TemplatedEmail();
        $emailTemplate
            ->from(new Address('crm@makrocel.com', 'Notificación'))
            //->from(new Address('software.developer3000@gmail.com', 'Notificación'))
            //->to('eharo@makrocel.com')
            ->to('software.developer3000@gmail.com')
            ->subject($subject)
            ->htmlTemplate($template);
        $emailTemplate->context($context);
        $mailer->send($emailTemplate);
    }
}
