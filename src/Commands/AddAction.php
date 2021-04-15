<?php

namespace CH\Commands;
 
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
 
/**
  * @see https://code.tutsplus.com/es/tutorials/how-to-create-custom-cli-commands-using-the-symfony-console-component--cms-31274
  *	https://symfony.com/doc/current/console
  *	https://symfony.com/doc/current/console/input.html
  *	https://symfony.com/doc/current/console/input.html#using-command-options		
  */

class AddAction extends BaseCommand {

	protected $namespace;

    	protected function configure()
    	{
        
		$this->setName('add:action')
            		->setDescription('Add a action hook')
			->setHelp('Example: init:ExampleController@index:10:1 [--enque | -e]')
            		->addArgument('data', InputArgument::REQUIRED, 'The hook next to the controller and its method, Use => tag:Controller@method:priority:num_args')
			->addOption('enque', 'e', InputOption::VALUE_NONE, 'If set to true, the hook is added to the config.php file');
		
    	}
 
    	protected function execute(InputInterface $input, OutputInterface $output)
    	{

        	$data = $input->getArgument('data');
		$option = $input->getOption('enque');
		$this->addAction($data, $output, $option);
		
	}
	
	/**
     	 *	Añade un hook de action y lo encola al array $add_action[] del fichero config.php.
     	 *
     	 *	@param array $data argumentos de la linea de comando
     	 *	Uso
     	 *		php antonella add:action tag:Controller@method:prioridad:num_args --enque
     	 *		php antonella add:action tag:Controller@method --enque
     	 *
     	 *	@see https://developer.wordpress.org/reference/functions/add_action/
     	 */
    	public function addAction($data, $output, $option)
    	{
        
		list($tag, $callable, $priority, $args) = array_pad(explode(':', $data), 4, null);
        	$priority = $priority ?? 10; 			// IF IS_NULL asigna le 10
        	$args = $args ?? 1; 				// Si IS_NULL asigna le 1
        	list($controller, $method) = array_pad(explode('@', $callable), 2, 'index');
        
        	$namespace = $this->getNamespace();
        	$class = str_replace('/', '\\', sprintf('%s\Controllers\%s', $namespace, $controller));

        	/* Si no existe el method o el controller lo añade y/o crea */
        	if (!method_exists($class, $method)) {
            		$this->__append_method_to_class([
                		'class' => $class,
                		'method' => $method,
                		'args' => $args, ]);
            		$output->writeln("<info>The method $method has been added to Controller $class</info>");
        	}

        	/* Encolamos el metodo al array $actions[] de config.php */
        	if ($option) {
            		$target = $this->getPath('config');						// src/config.php
            		$content = explode("\n", file_get_contents($target));

            		$class = ltrim($class, $namespace); // removemos el namespace
            		$this->__search_and_replace($content,
            		[
                		'public$add_action=[];' => sprintf("\tpublic \$add_action = [\n\t\t['%s', [__NAMESPACE__ . '%s','%s'], %s, %s]\n\t];", $tag, $class, $method, $priority, $args),
                		'public$add_action=[' => sprintf("\tpublic \$add_action = [\n\t\t['%s', [__NAMESPACE__ . '%s','%s'], %s, %s]", $tag, $class, $method, $priority, $args),	// append
            		]);

            		$newContent = implode("\n", $content);
            		file_put_contents($target, $newContent);
            		$output->writeln("<info>The array add_action has been updated</info>");
            		$output->writeln("<info>The Config.php File has been updated</info>");
        	}
    	}
}