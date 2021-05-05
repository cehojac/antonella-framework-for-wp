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

class MakeController extends BaseCommand {

    // the name of the command (the part after "antonella")
    protected static $defaultName = 'make:controller';

    protected $namespace;

    protected function configure()
    {
        $this->setDescription('Make a controller file in the folder src/Controllers')
             ->setHelp('Set a name for you controller. For example greatController')
             ->addArgument('name', InputArgument::REQUIRED, 'Name to controller file');
       
    }
 
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $name = $this->setNameController( $input->getArgument('name') );

        $this->makeController($name);
        $output->writeln("<info>===================================================================</info>");
		$output->writeln("<info>Controller $name created into src/Controllers folder</info>");
        $output->writeln("<info>===================================================================</info>");
	}

    /**
     * Crea un controllador dentro de la carpeta src/Controllers.
     *
     * @param string $name nombre del controlador
     * Example:
     * 		php antonella make:controller ExampleController	out: src/Controllers/ExampleController.php
     *      php antonella make:controller Admin/AdminController	out: src/Controllers/Admin/AdminController.php
     */
    protected function makeController($name)
    {
        
        $this->namespace = $this->getNamespace();
		$target = $this->getPath('controllers', rtrim($name, '.php')); 	// devuelve el paths para los controllers, src/Controllers
        
		// si la ruta no existe la crea
		if (!file_exists(dirname($target))) {
            mkdir(dirname($target), 0755, true);
        }
		
        // Crea una clase a partir de una fichero plantilla (dev/stubs/controller.stub)
        $StubGenerator = 'Dev\Classes\StubGenerator';
        $stub = new $StubGenerator(
            $this->getPath('stubs', 'controller'),				// 'dev/stubs/controller.stub',
            $target
        );

        $folder = array_reverse(explode('/', dirname($target)))[1];
        $name = rtrim($name,'.php');
        $stub->render([
            '%NAMESPACE%' => $this->namespace.'\\Controllers'.($folder == 'src' ? '' : '\\'.str_replace('/', '\\', dirname($name))),
            '%CLASSNAME%' => array_reverse(explode('/', $name))[0],
        ]);
		
    }

    /**
     * Añade Controller al final del fichero si no existe
     */
    private function setNameController( $name ) {
        $name = rtrim($name, '.php');               // removemos la extension .php del nombre
        $fileName = explode('/', $name);
        if ( count($fileName) == 2) {
            list($folder, $fileName) = $fileName;
            $fileName = strrpos($fileName, 'Controller') === false ? $fileName.'Controller' : $fileName;
            return sprintf('%1$s/%2$s.php', ucfirst($folder), ucfirst($fileName));
        }
        $name = strrpos($fileName[0], 'Controller') === false ? $fileName[0].'Controller' : $fileName[0];
        return sprintf('%1$s.php', ucfirst($name));
    }
}