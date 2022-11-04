<?php
// src/Service/FacturacionElectronica.php
namespace App\Service;

use App\Entity\Configuracion;
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

    public function __construct($targetDirectory, $fElectronicaDirectory)
    {
        $this->targetDirectory = $targetDirectory;
        $this->fElectronicaDirectory = $fElectronicaDirectory;
    }
    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
    public function crearArchivoXml($claveAcceso, $content){
        $fileName = "Fact-$claveAcceso";
        $fullName = "$this->targetDirectory/$fileName.xml";
        $file = fopen($fullName, "w");
        fwrite($file, $content);
        fclose($file);
        return $fileName;
    }
    public function firmarArchivoXml($fileName, Configuracion $configuracion){
        //$fileName = "Fact-$claveAcceso";
        $fileInput = "$this->targetDirectory/$fileName.xml";
        $fileOutput = "$this->targetDirectory/firmados/$fileName.xml";
        $p12Name = $configuracion->getP12Name();
        $p12File = "$this->fElectronicaDirectory/$p12Name";;
        $p12Password = $configuracion->getP12Password();
        $jarFile = "$this->fElectronicaDirectory/FirmaElectronica.jar";

        $retval=null;
        $process = new Process(['java', '-jar', $jarFile, $fileInput, $p12File, $p12Password, $fileOutput]);
        $process->run();
        //$output = $process->getOutput();
        $exitCode = $process->getExitCode();
        //exec("java -jar $jarFile $fileInput $p12File $p12Password $fileOutput", $output, $retval);
        return $exitCode;
    }
    public function recepcion($fileName){
        $fileOutput = "$this->targetDirectory/firmados/$fileName.xml";
        $decodeContent = file_get_contents($fileOutput);
        $parametros = new \stdClass();
        $parametros->xml = $decodeContent;
        $url = FacturacionElectronica::WS_TEST_RECEIV;
        $client = new \SoapClient($url, [ "trace" => 1 ] );
        $result = $client->validarComprobante($parametros);
        dump($result);
        return $result;
    }
    public function autorizacion(){

    }
}