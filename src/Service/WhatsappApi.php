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

    private const token = "ZfSrMJelV1jrgRZ8k59igHkyfUtH8eCtJu69ry68&uid=593994666777";

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }
    public function send($message, $to, $cuid){
        $token = self::token;
        $url = "https://wis.chat/w/api/send/chat/?token=$token&to=$to&custom_uid=$cuid&text=$message";
        $response = $this->client->request(
            'GET',
            $url
        );

        /*
        $statusCode = $response->getStatusCode();
        // $statusCode = 200
        $contentType = $response->getHeaders()['content-type'][0];
        // $contentType = 'application/json'
        $content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]
        */
        return $response;
    }


}