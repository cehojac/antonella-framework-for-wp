<?php

namespace CH\Commands;
 
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
 
/**
  * @see https://code.tutsplus.com/es/tutorials/how-to-create-custom-cli-commands-using-the-symfony-console-component--cms-31274
  *		 https://symfony.com/doc/current/console
  *		 https://symfony.com/doc/current/console/input.html
  *		 https://symfony.com/doc/current/console/input.html#using-command-options		
  */
 class MakeController extends Command
{
    protected $namespace;

    protected $paths;
    
    protected function configure()
    {
        $this->setName('make:controller')
            ->setDescription('Make a controller file in the folder src/Controllers')
            ->setHelp('Set a name for you controller. For example greatController')
            ->addArgument('nameController', InputArgument::REQUIRED, 'Name to controller file');
       
    }
 
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $name = $input->getArgument('nameController');
        $this->makeController($name);
        $output->writeln("<info>===================================================================</info>");
		$output->writeln("<info>Controller $name.php created into src\Controllers folder</info>");
        $output->writeln("<info>===================================================================</info>");
	}
    /**
     * Crea un controllador dentro de la carpeta src/Controllers.
     *
     * @param array $data datos de la linea de comandos donde $data[2] contiene el nombre del controlador
     *                    Example:
     *                    php antonella make ExampleController	out: src/Controllers/ExampleController.php
     *                    php antonella make Admin/AdminController	out: src/Controllers/Admin/AdminController.php
     */
    protected function makeController($data)
    {
        $c = new \Console;
        $this->namespace = $c->namespace;
        $this->paths = $c->paths;
        

        $target = $c->getPath('controllers', $data);
        if (!file_exists(dirname($target))) {
            mkdir(dirname($target), 0755, true);
        }
        // Crea una clase a partir de una fichero plantilla (stubs/controller.stub)
        $StubGenerator = $this->namespace.'\Classes\StubGenerator';
        $stub = new $StubGenerator(
            $c->getPath('stubs', 'controller'),	// __DIR__ . '/stubs/controller.stub',
            $target
        );

        $folder = array_reverse(explode('/', dirname($target)))[1];
        $stub->render([
            '%NAMESPACE%' => $this->namespace.'\\Controllers'.($folder == 'src' ? '' : '\\'.str_replace('/', '\\', dirname($data))),
            '%CLASSNAME%' => array_reverse(explode('/', $data))[0],
        ]);

    }
}