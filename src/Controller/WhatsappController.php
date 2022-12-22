<?php

namespace App\Controller;

use App\Entity\ContactWtp;
use App\Entity\Mensaje;
use App\Entity\MensajeWtp;
use App\Entity\MensajeWtpOut;
use App\Entity\Usuario;
use App\Repository\MensajeWtpRepository;
use App\Service\WhatsappApi;
use phpDocumentor\Reflection\Types\This;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WhatsappController extends AbstractController
{
    public $wtp;
    /**
     * @Route("/chat-whatsapp", name="whatsapp_index", methods = {"GET"})
     */
    public function index(MensajeWtpRepository $wtpRepository): Response
    {

        $mensajes = $wtpRepository->findBy(array(), array('id' => 'DESC'));;
        return $this->render('whatsapp/index.html.twig',[
           'mensajes'=>$mensajes
        ]);
    }

    /**
     * @Route("/whatsapp/receive", name="whatsapp_receive", methods = {"GET","POST"})
     */
    public function receive(Request $request, LoggerInterface $logger, WhatsappApi $wtp): Response
    {
        $this->wtp = $wtp;

        $obj = $request->request->all();
        $json = json_encode($obj);
        $logger->debug($json);
        //return new JsonResponse();

        $event = $obj['event'];
        $contact = $obj['contact'];
        $message = $obj['message'];
        $logger->info("event:".$event); // MESSAGE , ACK
        $logger->info("contact:",$contact); //
        $logger->info("message:",$message); //


        $em = $this->getDoctrine()->getManager();
        if ($event == 'message'){

            if($message['dir']!=''){

                $contactwtp = $em->getRepository(ContactWtp::class)->findOneByUid($contact['uid']);
                if(!$contactwtp){
                    $contactwtp = new ContactWtp();
                    $contactwtp->setName($contact['name']);
                    if(isset($contact['pic'])){
                        $contactwtp->setPic($contact['pic']);
                    }
                    $contactwtp->setType($contact['type']);
                    $contactwtp->setUid($contact['uid']);
                }

                //$msj = json_decode($message['body']);
                $mensajewtp = new MensajeWtp();
                $mensajewtp->setBody($message['body']['text']);
                $mensajewtp->setContact($contactwtp);
                $mensajewtp->setUid($message['uid']);
                $mensajewtp->setType($message['type']);
                $mensajewtp->setCuid($message['cuid']);
                $mensajewtp->setDir($message['dir']);
                $mensajewtp->setDtm($message['dtm']);
                $mensajewtp->setMediakey($message['mediakey']);
                $mensajewtp->setMimetype($message['mimetype']);
                $mensajewtp->setUrl($message['clientUrl']);

                $em->persist($mensajewtp);
                $em->flush();
            }
        }

        return new Response('OK');

    }

    /**
     * @Route("/whatsapp/send", name="whatsapp_send", methods = {"POST"})
     */
    public function send(Request $request, LoggerInterface $logger, WhatsappApi $wtp): Response
    {
        $to = $request->request->get('to');
        $message = $request->request->get('message');
        //dump($to);
        //dump($message);
        $cuid = uniqid();

        $response = $wtp->send(urlencode($message),$to,$cuid);
        $logger->debug($response->getContent());
        return $this->redirectToRoute('whatsapp_index');
    }
    private function enviarMenu($to, $cuid, ContactWtp $contact){
        $em = $this->getDoctrine()->getManager();
        /* @var $mensaje Mensaje */
        $mensaje = $em->getRepository(Mensaje::class)->findOneByCodigo('menup');
        $mensaje_coded = urlencode($mensaje->getTexto());

        $response = $this->wtp->send($mensaje_coded, $to, $cuid);
        $mensaje_out = new MensajeWtpOut();
        $mensaje_out->setContact($contact);
        $mensaje_out->setMensaje($mensaje);
        $em->persist($mensaje_out);
        $em->flush();

    }
    private function enviarRespuesta($to, $cuid, ContactWtp $contact, Mensaje $mensaje){
        $em = $this->getDoctrine()->getManager();
        $mensaje_coded = urlencode($mensaje->getTexto());
        $response = $this->wtp->send($mensaje_coded, $to, $cuid);
        $mensaje_out = new MensajeWtpOut();
        $mensaje_out->setContact($contact);
        $mensaje_out->setMensaje($mensaje);
        $em->persist($mensaje_out);
        $em->flush();
    }
}


/*
$usuario = $em->getRepository(Usuario::class)->findOneByPhone($contactwtp->getUid());

if($usuario){
    $text = $message['body']['text'];

    $name = $usuario->getUsername();

    $logger->info("El usuario $name ha enviado un mensaje");
    if($message['body']['text'] == 'start'){
        $logger->info("Se ha enviado el menú de opciones");
        $this->enviarMenu($contactwtp->getUid(), 'xxxx', $contactwtp);
    }else{

        $last = $em->getRepository(MensajeWtpOut::class)
            ->findOneBy(['contact'=>$contactwtp],['id'=>'DESC']);


        if($last){
            $text_last = $last->getMensaje()->getTexto();
            $logger->info("Ultimo mensaje de tipo: ".$last->getMensaje()->getTipo());
            $tipo = $last->getMensaje()->getTipo();

            if($tipo == Mensaje::SELECT){
                $hijos = $last->getMensaje()->getHijos();
                $is_match = false;
                foreach ($hijos as $hijo){
                    if($hijo->getCodigo()==$text){
                        $this->enviarRespuesta(
                            $contactwtp->getUid(),
                            'xxxx',
                            $contactwtp,
                            $hijo
                        );
                        $is_match = true;
                        break;
                    }
                }
                if (!$is_match){
                    $errormsj = $em->getRepository(Mensaje::class)->findOneByCodigo('error');
                    $this->enviarRespuesta(
                        $contactwtp->getUid(),
                        'xxxx',
                        $contactwtp,
                        $errormsj
                    );
                }
            }
        }else{
            $logger->info("primer mensaje de $name, opcion no valida. Debe enviar el comando start");
        }
    }
}
*/