<?php

namespace Dev\Commands;
 
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

class MakeShortcode extends BaseCommand {

    // the name of the command (the part after "antonella")
    protected static $defaultName = 'make:shortcode';
    
    protected $namespace;

    protected function configure()
    {
        
		$this->setDescription('Make a shortcode')
			 ->setHelp('Example: name:ExampleController@shortcode [--enque | -e]')
             ->addArgument('data', InputArgument::REQUIRED, 'The shortcode next to the controller and its method, Use => name:Controller@method')
			 ->addOption('enque', 'e', InputOption::VALUE_NONE, 'If set to true, the new shortcode is added to the config.php file');
		
    }
 
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $data = $input->getArgument('data');
		$option = $input->getOption('enque');
		$this->makeShortcode($data, $output, $option);
		
	}

	
	/**
     * Crea un shortCode y lo añade al array $shortcodes[] del fichero config.php.
     *
     * @param array $data Datos de entrada
     * 	Use:
     *  	php antonella make:shortcode name:Controller@method [--enque | -e]
     */
    protected function makeShortcode($data, $output, $option)
    {
        
		
		list($tag, $callable) = explode(':', $data);
        list($controller, $method) = array_pad(explode('@', $callable), 2, 'short_code');
        
        $namespace = $this->getNamespace();
        $class = str_replace('/', '\\', sprintf('%s\Controllers\%s', $namespace, $controller));

        /* Si no existe el method o el controller lo añade y/o crea */
        if (!method_exists($class, $method)) {
            $this->__append_method_to_class([
                'class' => $class,
                'method' => $method,
            ]);
            $output->writeln("<info>The method $method has been added to Controller $class.php</info>");
        }

        /* Encolamos el metodo al array $actions[] de config.php */
        if ($option) {
            $target = $this->getPath('config');							// src/config.php
            $content = explode("\n", file_get_contents($target));

                                                                        // $class = ltrim($class, $namespace); // removemos el namespace
            $class = substr( $class, strlen($namespace));
            $this->__search_and_replace($content,
            [
                'public$shortcodes=[];' => sprintf("\tpublic \$shortcodes = [\n\t\t['%s', __NAMESPACE__ . '%s::%s']\n\t];", $tag, $class, $method),
                'public$shortcodes=[' => sprintf("\tpublic \$shortcodes = [\n\t\t['%s', __NAMESPACE__ . '%s::%s']", $tag, $class, $method),	// append
            ]);

            $newContent = implode("\n", $content);
            file_put_contents($target, $newContent);
            $output->writeln("<info>The array shortcodes has been updated</info>");
            $output->writeln("<info>The Config.php File has been updated</info>");
        }
	}
}