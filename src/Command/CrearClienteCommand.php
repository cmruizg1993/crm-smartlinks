<?php


namespace App\Command;


use App\Service\Validador_CI_RUC;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class CrearClienteCommand extends Command
{
    private $validador ;
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:crear-cliente';

    protected function configure()
    {
        $this->setDescription('Este comando nos permite crear un nuevo cliente.');
    }

    public function __construct(Validador_CI_RUC $validador)
    {
        $this->validador = $validador;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // ... put here the code to create the user
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'Creación de un Nuevo Cliente',
            '============',
            '',
        ]);

        $helper = $this->getHelper('question');
        $question = new Question('Tipo de Identificacón
        [1->Cédula | 2->RUC Natural | 3->RUC Jurídico | 4->Pasaporte]: ', 1);
        $tipodni = $helper->ask($input, $output, $question);
        $tipodni_nombre = '';
        switch ((int) $tipodni){
            case '1':
                $tipodni_nombre = 'CEDULA';
                break;
            case '2':
                $tipodni_nombre ='RUC NATURAL';
                break;
            case '3':
                $tipodni_nombre = 'RUC JURIDICO';
                break;
            case '4':
                $tipodni_nombre = 'PASAPORTE';
                break;
            default:
                $output->writeln('<error>Tipo de identificacion no valido !</error>');
                return Command::FAILURE;
        }
        $question = new Question("Ingrese su $tipodni_nombre : ");

        $dni = $helper->ask($input, $output, $question);

        $validador = $this->validador;

        $es_valido = false;

        switch ((int) $tipodni){
            case '1':
                $es_valido = $validador->validarCedula($dni);
                break;
            case '2':
                $es_valido = $validador->validarRucPersonaNatural($dni);
                break;
            case '3':
                $es_valido = $validador->validarRucSociedadPrivada($dni);
                break;
            case '4':
                $es_valido = $validador->validarPasaporte($dni);
                break;
        }
        if(!$es_valido){
            $error = $validador->getError();
            $output->writeln("<error>$error</error>");
            $output->writeln("<error>NUMERO DE DOCUMENTO NO VALIDO</error>");
            return Command::FAILURE;
        }



        //$output->writeln($dni);
        // this method must return an integer number with the "exit status code"
        // of the command. You can also use these constants to make code more readable

        // return this if there was no problem running the command
        // (it's equivalent to returning int(0))
        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;
    }
}