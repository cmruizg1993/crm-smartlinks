<?php
// src/Service/FacturacionElectronica.php
namespace App\Service;

use App\Entity\Empresa;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Process\Process;
use Symfony\Component\String\Slugger\SluggerInterface;

class FacturacionElectronica
{
    const WS_TEST_RECEIV = 'https://celcer.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl';
    const WS_TEST_AUTH = 'https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl';  # noqa
    const WS_RECEIV = 'https://cel.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl';
    const WS_AUTH = 'https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl';

    private $targetDirectory;
    private $fElectronicaDirectory;
    private $testing = true;
    public function __construct($targetDirectory, $fElectronicaDirectory, $appEnvironment)
    {
        $this->targetDirectory = $targetDirectory;
        $this->fElectronicaDirectory = $fElectronicaDirectory;
        $this->testing = $appEnvironment == 'dev' ;
    }
    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
    public function crearArchivoXml($claveAcceso, $content){
        $fileName = "Fact-$claveAcceso";
        $fullName = "$this->targetDirectory/$fileName.xml";
        $file = fopen($fullName, "w");
        $utf8Content = $this->eliminar_acentos($content);
        fwrite($file, $utf8Content);
        fclose($file);
        return $fileName;
    }
    public function firmarArchivoXml($claveAcceso, Empresa $configuracion){
        //$fileName = "Fact-$claveAcceso";
        $fileName = "Fact-$claveAcceso";
        $fileInput = "$this->targetDirectory/$fileName.xml";
        $fileOutput = "$this->targetDirectory/firmados/$fileName.xml";
        $p12Name = $configuracion->getP12Name();
        $p12File = "$this->fElectronicaDirectory/$p12Name";;
        $p12Password = $configuracion->getP12Password();
        $jarFile = "$this->fElectronicaDirectory/FirmaElectronica.jar";
        $output = null;
        $retval=null;
        $process = new Process(['java', '-jar', $jarFile, $fileInput, $p12File, $p12Password, $fileOutput]);
        $process->run();
        //dump($process->getOutput());
        $output = $process->getExitCode();
        //exec("java -jar $jarFile $fileInput $p12File $p12Password $fileOutput", $output, $retval);
        return $output;
    }
    public function recepcion($claveAcceso, $testing = true){
        $fileName = "Fact-$claveAcceso";
        $fileOutput = "$this->targetDirectory/firmados/$fileName.xml";
        $decodeContent = file_get_contents($fileOutput);
        $parametros = new \stdClass();
        $parametros->xml = $decodeContent;
        $url = $testing ? FacturacionElectronica::WS_TEST_RECEIV: FacturacionElectronica::WS_RECEIV;
        $client = new \SoapClient($url);
        $result = $client->validarComprobante($parametros);
        //dump($result);
        return $result;
    }
    public function autorizacion($claveAcceso, $testing = true){
        $url = $testing ? FacturacionElectronica::WS_TEST_AUTH: FacturacionElectronica::WS_AUTH;
        $client = new \SoapClient($url );
        $parametros =  new \stdClass();
        $parametros->claveAccesoComprobante = $claveAcceso;
        $result = $client->autorizacionComprobante($parametros);
        //dump($result);
        return $result;
    }
    public function obtenerPathXml($claveAcceso){
        $fileName = "Fact-$claveAcceso";
        $fileOutput = "$this->targetDirectory/firmados/$fileName.xml";
        return $fileOutput;
    }
    public function obtenerPathPdf($claveAcceso){
        $fileName = "Fact-$claveAcceso";
        $fileOutput = "$this->targetDirectory/firmados/$fileName.pdf";
        return $fileOutput;
    }
    function eliminar_acentos($cadena){

        //Reemplazamos la A y a
        $cadena = str_replace(
            array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
            array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
            $cadena
        );

        //Reemplazamos la E y e
        $cadena = str_replace(
            array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
            array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
            $cadena );

        //Reemplazamos la I y i
        $cadena = str_replace(
            array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
            array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
            $cadena );

        //Reemplazamos la O y o
        $cadena = str_replace(
            array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
            array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
            $cadena );

        //Reemplazamos la U y u
        $cadena = str_replace(
            array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
            array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
            $cadena );

        //Reemplazamos la N, n, C y c
        $cadena = str_replace(
            array('Ñ', 'ñ', 'Ç', 'ç'),
            array('N', 'n', 'C', 'c'),
            $cadena
        );

        return $cadena;
    }
}