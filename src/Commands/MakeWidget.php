<?php

namespace CH\Commands;
 
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

class MakeWidget extends BaseCommand {

    protected $namespace;

    protected function configure()
    {
        
		$this->setName('make:widget')
            ->setDescription('Make a widget in the folder src/Widgets')
			->setHelp('Set a name for you widget. For example MyFirstWidget [--enque | -e]')
            ->addArgument('name', InputArgument::REQUIRED, 'Name for you widget')
			->addOption('enque', 'e', InputOption::VALUE_NONE, 'If set to true, the new widget is added to the config.php file');
		
    }
 
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $name = $input->getArgument('name');
		$option = $input->getOption('enque');
		$this->makeWidget($name, $output, $option);
		
	}

	
	/**
     *	Crea un widget desde la consola.
     *
     *	@param array $data arguments leidos desde la consola
	 *	@param OutputInterface $output Salida por pantalla
	 *  @param string $option option
     *	@param string --enque Optional indica si queremos añadirlo al array widgets de config.php
     *	example => php antonella widget MyFirstWidget
     * 	example => php antonella widget MyFirtWidget [--enque | -e]
     */
    protected function makeWidget($data, $output, $option)
    {
        
		$namespace = $this->getNamespace();

        $target = $this->getPath('widgets', $data);
        if (!file_exists(dirname($target))) {
            mkdir(dirname($target), 0755, true);
        }
		
        // Crea una clase a partir de una fichero plantilla (stubs/controller.stub)
        $StubGenerator = $this->getNamespace().'\Classes\StubGenerator';
        $stub = new $StubGenerator(
            $this->getPath('stubs', 'widget'),	//__DIR__ . '/stubs/widget.stub',
            $target
        );

        $stub->render([
            '%NAMESPACE%' => $namespace.'/Widgets',
            '%CLASSNAME%' => $data,
        ]);

        /* Comprobamos si hemos pasado el parametro --enque */
        if ($option) {
            $target = $this->getPath('config');							// src/config.php
            $content = explode("\n", file_get_contents($target));

            $this->__search_and_replace($content,
            [
                'public$widgets=[];' => sprintf("\tpublic \$widgets = [ \n\t\t[__NAMESPACE__ . '\Widgets\%s']\n\t];", $data),
                'public$widgets=[' => sprintf("\tpublic \$widgets = [ \n\t\t[__NAMESPACE__ . '\Widgets\%s']", $data),	// append
            ]);

            $newContent = implode("\n", $content);
            file_put_contents($target, $newContent);

            $output->writeln("<info>The Config.php File has been updated</info>");
		}

        $output->writeln("<info>The Widget $data.php created into src/Widgets folder</info>");
	}
}