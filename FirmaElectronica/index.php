<?php

$output=null;
$retval=null;

$fileName = 'Fact-000000011';
$fileInput = "$fileName.xml";
$fileOutput = "$fileInput.signed.xml";
$p12File = "smartlinks.p12";
$p12Password = "cristian1995firma";

exec("java -jar FirmaElectronica.jar $fileInput $p12File $p12Password $fileOutput", $output, $retval);
echo "Returned withs $retval and output:\n";
print_r($output);

const WS_TEST_RECEIV = 'https://celcer.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl';
const WS_TEST_AUTH = 'https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl';  # noqa
const WS_RECEIV = 'https://cel.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl';
const WS_AUTH = 'https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl';
try {
    /*
    $myfile = fopen($fileOutput, "r") or die("Unable to open file!");
    $contentFile = fread($myfile,filesize($fileOutput));
    fclose($myfile);
    
    */
    $myfile = fopen($fileOutput, "r") or die("Unable to open file!");
    $contentFile = fread($myfile,filesize($fileOutput));
    fclose($myfile);
    $decodeContent = file_get_contents($fileOutput);
    $base64Content = base64_encode($decodeContent);
    $parametros = new stdClass();  
    $xml = '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">'.
    		'<Body>'.
		        '<validarComprobante xmlns="http://ec.gob.sri.ws.recepcion">'.
		            '<xml xmlns="">'.$base64Content.'</xml>'.
		        '</validarComprobante>'.
    		'</Body>'.
		'</Envelope>';
    $parametros->xml = $decodeContent;
    // print_r($decodeContent);
    $url = WS_TEST_RECEIV;
    $client = new SoapClient($url, [ "trace" => 1 ] );
    $result = $client->validarComprobante($parametros);
    print_r($result);


    $xmlObject = simplexml_load_string($contentFile) or die("Error: Cannot create object");
    $accessKey = (string)$xmlObject->infoTributaria->claveAcceso[0];
    $url = WS_TEST_AUTH;
    $client = new SoapClient($url, [ "trace" => 1 ] );
    $parametros =  new stdClass();
    $parametros->claveAccesoComprobante = $accessKey;
    $result = $client->autorizacionComprobante($parametros);
    print_r($result);
    
} catch ( SoapFault $e ) {
    echo $e->getMessage();
}

?>