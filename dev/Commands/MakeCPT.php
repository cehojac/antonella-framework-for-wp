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

class MakeCPT extends BaseCommand {

    // the name of the command (the part after "antonella")
    protected static $defaultName = 'make:cpt';

    protected $namespace;

    protected function configure()
    {
        
		$this->setDescription('Make a Custom Post Type')
			 ->setHelp('Example: name [--enque | -e]')
             ->addArgument('name', InputArgument::REQUIRED, 'The name of the cpt, Use => php antonella make:cpt name');
		
    }
 
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $name = $input->getArgument('name');
		$this->makeCpt($name, $output);
		
	}
	
	/**
     * CustomPost function
     * crea dentro del array post_types en config.php un nuevo custom.
     *
     * @author Alberto Leon <email@email.com>
     *
     * @version 1.0.0
     *
     * @param string $name el nombre del custom post type
     * @param OutputInterface $output
	 *
     * @return void
     */
    public function makeCpt($name, $output)
    {
        
		if (isset($name) and !empty($name)) {

            // Si existe no lo añadimos
            $className = sprintf('\\%1$s\\Config', $this->getNamespace());
            $config = new $className();

            $cpts = $config->post_types;
            $find = false;
            foreach ($cpts as $cpt) {
                if ( in_array($name, [ $cpt['singular'] ]) || 
                     in_array(mb_strtolower($name),[ mb_strtolower($cpt['singular']), mb_strtolower($cpt['slug']) ])) {
                    $find = true;
                    break;
                }
            }
            
            if ( $find ) {
                $output->writeln("<info>Warning !!! The Custom Post Type \"$name\" was already created</info>");
                die();
            }

            // Abrir el archivo
            $slash = DIRECTORY_SEPARATOR;
            $archivo = $this->getPath('config');
            $abrir = fopen($archivo, 'r+');
            $contenido = fread($abrir, filesize($archivo));
            fclose($abrir);
            //Separar linea por linea
            $contenido = explode("\n", $contenido);
            //Modificar linea deseada
            for ($i = 0; $i < sizeof($contenido); ++$i) {
                if (strpos($contenido[$i], 'public $post_types = [') !== false) {
                    $contenido[$i] = '    public $post_types = [
        [
            "singular"      => "'.$name.'",
            "plural"        => "'.$name.'s",
            "slug"          => "'.$this->slug_title($name).'",
            "position"      => 99,
            "taxonomy"      => [],
            "image"         => "antonella-icon.png",
            "gutemberg"     => true
        ],
';
                }
            }
            $contenido = implode("\n", $contenido);
            file_put_contents($archivo, $contenido);
            $output->writeln("<info>Add new Custom PostType {$name} in src/Config.php file</info>");
        } else {
            $output->writeln("<error>The name is required</error>");
        }		
    }

	
	
}