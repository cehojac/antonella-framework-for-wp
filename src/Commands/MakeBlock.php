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

class MakeBlock extends BaseCommand {

    protected $namespace;
	
	protected function configure()
    {
        
		$this->setName('make:block')
            ->setDescription('Make a Custom Gutenberg Block')
			->setHelp('Example: php antonella make:block namespace/name [--callable | -c] [--enque | -e]')
            ->addArgument('name', InputArgument::REQUIRED, 'The block name with her namespace, Use => namespace/name [--callable | -c] [--enque | -e]')
			->addOption('callable', 'c', InputOption::VALUE_NONE, 'If set to true, allows a rendering from php')
			->addOption('enque', 'e', InputOption::VALUE_NONE, 'If set to true, the new block is added to the config.php file');
		
    }
 
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $data = $input->getArgument('name');
		$option = [
			'callable' => $input->getOption('callable'),
			'enque' => 	$input->getOption('enque')
		];
		
		$this->makeGutenbergBlock($data, $output, $option);
		
	}

	
	/**
     *	Crea un block de gutenberg haciendo uso de js moderno.
     *
     * 	@params array $data La data para crear el block
     *	Uso
     *		php antonella block namespace/block-name [--callable] [--enque]
     *	Donde
     *		--callable Indica que se trata de un block dynamic y sera renderizado desde php
     *		--enque	Si queremos que se agregue al array $gutenberg_blocks[]
     */
    protected function makeGutenbergBlock($data, $output, $option)
    {
        
		if (isset($data) && !empty($data)) {
            $block = str_replace('\\', '/', $data);
            if (count(explode('/', $block)) == 1) {
                // si no le proporcionamos un namespace agrega 'antonella' por default
                $block = sprintf('antonella/%s', $block);
            }

            // creamos el directorio dentro de components
            $folder = array_reverse(explode('/', $block))[0];
            $target = str_replace('\\', '/', sprintf($this->getDirBase().'/components/%s/index.js', $folder));
            if (!file_exists(dirname($target))) {
                mkdir(dirname($target), 0755, true);
                $output->writeln(sprintf("<info>The directory %s has been created</info>", dirname($target)));
            }
        }

        // creamos el fichero index.js mediante la plantilla ./stubs/block.stub
        // ó ./stubs/server-side-block.stub
        $StubGenerator = $this->getNamespace().'\Classes\StubGenerator';
        $template = 'block';
        if ($option['callable']) {
            $template = 'server-side-block';
        }
        $stub = new $StubGenerator(
            $this->getPath('stubs', $template),	// __DIR__ . '/stubs/block.stub',
            $target
        );
        $stub->render([
            '%BLOCK%' => $block,
            '%TITLE%' => ucwords($folder),
        ]);

        file_put_contents($this->getDirBase()."/components/$folder/editor.css", '/* Your style for editor and front-end */', FILE_APPEND | LOCK_EX);
        file_put_contents($this->getDirBase()."/components/$folder/style.css", '/* Your style for front-end */', FILE_APPEND | LOCK_EX);
        $output->writeln("<info>Your block $folder has been created</info>");

		if ($option['enque']) {
			
			$target = $this->getPath('config');							// src/config.php
            $content = explode("\n", file_get_contents($target));

            if (!$option['callable']) {
                $this->__search_and_replace($content,
                [
                    'public$gutenberg_blocks=[' => sprintf("\tpublic \$gutenberg_blocks = [ \n\t\t'%s' => [],", $block),	// append
                ]);
            } else {
                // Dynamic Block
                $slug = str_replace('/', '_', $block);
                $this->__search_and_replace($content,
                [
                    'public$gutenberg_blocks=[' => sprintf("\tpublic \$gutenberg_blocks = [ \n\t\t'%s' => [\n\t\t\t'render_callback' => __NAMESPACE__ . '\Gutenberg::%s_render_callback'\n\t\t],", $block, $slug),	// append
                ]);

                // Todo append render_callback Gutenber file
                $gutenberg = $this->getPath('gutenberg');	// src/Gutenberg.php
                $contenido = explode("\n", file_get_contents($gutenberg));
                $this->__search_and_replace($contenido, [
                    '}/*generatedwithantonellaframework*/' => sprintf("\n\tpublic static function %s_render_callback(\$attr){\n\t\t\\\\TODO\n\t}\n} /* generated with antonella framework */", $slug),
                ]);

                $nuevoContenido = implode("\n", $contenido);
                file_put_contents($gutenberg, $nuevoContenido);
                $output->writeln("<info>The src/Gutenberg.php File has been updated</info>");
            }
            $newContent = implode("\n", $content);
            file_put_contents($target, $newContent);

            $output->writeln("<info>The Config.php File has been updated</info>");

            // Todo append component componets/index.js
            $content = explode("\n", file_get_contents(str_replace('\\', '/', $this->getDirBase().'/components/index.js')));
            $found = false;
            foreach ($content as $line) {
                if (trim($line) === sprintf('import "./%s"', $folder)) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                file_put_contents($this->getDirBase().'/components/index.js', sprintf("\nimport \"./%s\";", $folder), FILE_APPEND | LOCK_EX);
                $output->writeln("<info>The components/index.js File has been updated</info>");
            }
        }
		
	}
}