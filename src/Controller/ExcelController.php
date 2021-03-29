<?php

namespace App\Controller;


use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExcelController extends AbstractController
{
    /**
     * @Route("/excel", name="excel")
     */
    public function index(): Response
    {
        $pathFile = $this->getParameter('excel_templates').'/os.xls';
        $newfile  = $this->getParameter('excel_templates').'/'.uniqid('os').'.xls';
        $reader = IOFactory::createReaderForFile($pathFile);
        $spreadsheet = $reader->load($pathFile);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getCell('A2')->setValue('HOLA MUNDO');
        $writer = IOFactory::createWriter($spreadsheet, "Xls");
        $writer->save($newfile);

        return $this->render('excel/index.html.twig', [
            'controller_name' => 'ExcelController',
        ]);
    }
    /**
     * @Route("/cargafso", name="fso",methods={"POST", "GET"})
     */
    public function cargarFSO(Request $request): Response
    {

        $form = $this->createFormBuilder([])
            ->add('archivo', FileType::class,['attr'=>['required'=>'required']])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $excelMapa = [
                'sanColumn' => 'B','estaoSanColumn' => 'C','estadoContratoColumn' => 'D','fechaOrdenColumn' => 'E',
                'nombreClienteColumn' => 'H', 'emailClienteColumn' => 'I','cantonColumn' => 'J','provinciaColumn' => 'K',
                'nroOrdenColumn' => 'Q', 'tipoOrdenColumn' => 'S', 'estadoOrdenColumn' => 'T', 'fechaOrdenColumn' => 'U',
                'codigoInstaladorColumn' => 'Y', 'nombreInstaladorColumn' => 'Z', 'fechaInstalacionColumn' => 'AA'
            ];
            $file = $form['archivo']->getData();
            if($file){
                dump($file);
            }
        }

        return $this->render('excel/fso.html.twig', [
            'controller_name' => 'ExcelController',
            'form'=>$form
        ]);
    }
}
