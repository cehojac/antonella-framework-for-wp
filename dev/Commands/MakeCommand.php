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
class MakeCommand extends BaseCommand {
	
    // the name of the command (the part after "antonella")
    protected static $defaultName = 'make:command';

    protected $namespace;

    protected function configure()
    {
        $this->setDescription('Make a new Command')
             ->setHelp('Set a name for you new command.')
             ->addArgument('name', InputArgument::REQUIRED, 'Name new command')
			 ->addArgument('short-code', InputArgument::REQUIRED, 'Short code command, use shortcode or short:code');
       
    }
 
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $data = [
			'name' => rtrim($input->getArgument('name'), '.php'),   // removemos el .php si esta incluido
			'short_code' => $input->getArgument('short-code')
		];
		
        $this->makeNewCommand($data, $output);
		
	}
    /**
     * Crea un fichero helpers para albergar funciones auxiliares.
     *
     * @param array $data Nombre del comando junto a su short code
     * 	Uso:	php antonella make:command <name> short:code
     */
    public function makeNewCommand($data, $output)
    {
		extract($data);
		
        $this->namespace = $this->getNamespace();
		$name = strrpos($name, 'Command') === false ? $name.'Command' : $name;
		$target = $this->getPath('commands', $name);
		
		// Crea una clase a partir de una fichero plantilla (dev/stubs/command.stub)
        $StubGenerator = 'Dev\Classes\StubGenerator';
        $stub = new $StubGenerator(
            $this->getPath('stubs', 'command'),				// 'dev/stubs/command.stub',
            $target
        );
		
		// remplace
		$stub->render([
			'%NAMESPACE%' => 'Dev\\Commands',
			'%CLASSNAME%' => $name,
			'%SHORTCODE%' => $short_code
		]);
		
		$output->writeln("<info>The ClassName $name.php created into dev/Commands folder</info>");
    }
    
}