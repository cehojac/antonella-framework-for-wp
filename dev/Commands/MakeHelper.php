<?php

namespace Dev\Commands;
 
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
 
/**
  * @see https://code.tutsplus.com/es/tutorials/how-to-create-custom-cli-commands-using-the-symfony-console-component--cms-31274
  *		 https://symfony.com/doc/current/console
  *		 https://symfony.com/doc/current/console/input.html
  *		 https://symfony.com/doc/current/console/input.html#using-command-options		
  */
class MakeHelper extends BaseCommand {
	
    // the name of the command (the part after "antonella")
    protected static $defaultName = 'make:helper';

    protected $namespace;

    protected function configure()
    {
        $this->setDescription('Make a helper file in the folder src/Helpers')
             ->setHelp('Set a name for you helper. For example auxiliarHelper')
             ->addArgument('name', InputArgument::REQUIRED, 'Name to helper file');
       
    }
 
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $name = rtrim($input->getArgument('name'), '.php');   // removemos el .php
        $this->makeHelper($name);
		$output->writeln("<info>======================================================</info>");
		$output->writeln("<info>The Helper $name.php created into src/Helpers folder</info>");
        $output->writeln("<info>======================================================</info>");

        
	}
    /**
     * Crea un fichero helpers para albergar funciones auxiliares.
     *
     * @param array $data argumentos de la linea de comandos
     *                    donde $data[2] representa el nombre del fichero
     *                    Uso:	php antonella make:helper auxiliares
     *                    Out: src/Helpers/auxiliares.php
     */
    public function makeHelper($data)
    {
        $this->namespace = $this->getNamespace();
		$target = $this->getPath('helpers', $data);
		
		// Si la ruta no existe la crea
        if (!file_exists(dirname($target))) {
            mkdir(dirname($target), 0755, true);
        }
		
        $StubGenerator = 'Dev\Classes\StubGenerator';
        $stub = new $StubGenerator(
            $this->getPath('stubs', 'helper'),			// 'dev/stubs/helper.stub',
            $target
        );

        $folder = array_reverse(explode('/', dirname($target)))[0];
        $stub->render([
            '%NAME%' => $data,
        ]);
    }
    
}