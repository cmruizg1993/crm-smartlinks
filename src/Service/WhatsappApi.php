<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Esta clase contiene los métodos para poder establecer una comunicación con Whatsapp
 */
class WhatsappApi
{

    /**
     * Error
     *
     * Contiene errores globales de la clase
     *
     * @var string
     * @access protected
     */
    protected $error = '';

    private $client;

    //private const token = "ZfSrMJelV1jrgRZ8k59igHkyfUtH8eCtJu69ry68&uid=593994666777";
    //token tuenti cristian
    private const token = "1RR5vsUIzmZjwA9VgQizLRrn0NMGOxr8EeuxTDxw&uid=593963756187";
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }
    public function send($message, $to, $cuid){
        $token = self::token;
        $url = "https://wis.chat/w/api/send/chat/?token=$token&to=$to&custom_uid=$cuid&text=$message";
        return $this->client->request(
            'GET',
            $url
        );
    }


}