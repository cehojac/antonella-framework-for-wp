<?php
    
namespace Dev\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 *	@see https://symfony.com/doc/current/console.html
 */
class NamespaceCommand extends BaseCommand {
	
	// the name of the command (the part after "antonella")
    protected static $defaultName = 'namespace';
	
	protected function configure()
    {
        $this->setDescription('Set a new namespace')
            ->setHelp('php antonella namespace ABCDE')
            ->addArgument('namespace', InputArgument::OPTIONAL, 'New value, CH default')
            ->addOption('namespace', null, InputOption::VALUE_NONE, 'show current namespace');
       
    }
 
    protected function execute(InputInterface $input, OutputInterface $output)
    {
		
		if ( !$input->getArgument('namespace') && $input->getOption('namespace'))
        {
            $namespace = $this->getNamespace();
            $output->writeln("<info>The current namespace is: $namespace</info>");
        }
        else {
            
            // retrive argument
            $output->writeln("<info>Renaming the namespace...</info>");
            
            $newname = sprintf('Antonella\\%1$s', strtoupper($input->getArgument('namespace')) ?: $this->get_rand_letters());
            $newname_c = str_replace('\\', '\\\\', $newname);		// for composer.json
			
			$slash = DIRECTORY_SEPARATOR;
            $namespace = $this->getNamespace();
            $namespace_c = str_replace('\\', '\\\\', $namespace);	// for composer.json
			
			// composer
			$composer = file_get_contents($this->getDirBase().$slash.'composer.json');
			$composer = str_replace($namespace_c, $newname_c, $composer);
            file_put_contents($this->getDirBase().$slash.'composer.json', $composer);
            
			
			// core
			$files = [
				str_replace('\\', '/', sprintf('%1$s/antonella-framework.php', $this->getDirBase())),
				str_replace('\\', '/', sprintf('%1$s/%2$s.php', $this->getDirBase(), basename($this->getDirBase())))
			];
			
			foreach ($files as $file) {
				if (file_exists($file)) {
					$core = file_get_contents($file);
					$core = str_replace($namespace, $newname, $core);
					file_put_contents($file, $core);
				}
			}
			
			// resto de ficheros
			$dirName = $this->getDirBase().$slash.'src';
            $dirName = realpath($dirName);
            if (substr($dirName, -1) != '/') {
                $dirName .= $slash;
            }
            $dirStack = [$dirName];
            while (!empty($dirStack)) {
                $currentDir = array_pop($dirStack);
                $filesToAdd = [];
                $dir = dir($currentDir);
                while (false !== ($node = $dir->read())) {
                    if (($node == '..') || ($node == '.')) {
                        continue;
                    }
                    if (is_dir($currentDir.$node)) {
                        array_push($dirStack, $currentDir.$node.$slash);
                    }
                    if (is_file($currentDir.$node)) {
                        $file = file_get_contents($currentDir.$node);
                        $file = str_replace($namespace, $newname, $file);
                        file_put_contents($currentDir.$node, $file);
                    }
                }
            }
            
            system('composer dump-autoload');
            $output->writeln("<info>The new namespace is $newname</info>");
        
        }
	}
	
	/** 
	 * devuelve una cadena aleatoria de longitud $length 
	 *		@param $length int Longitud de la cadena
	 */
	private function get_rand_letters($length = 5) {
		
		if ($length > 0) {
            $rand_id = '';
            for ($i = 1; $i <= $length; ++$i) {
                mt_srand((float) microtime() * 1000000);
                $num = mt_rand(1, 26);
                $rand_id .= $this->assign_rand_value($num);
            }
        }

        return $rand_id;
		
	}
	
	// asigna a un numero una letra y lo devuelve
	private function assign_rand_value($num)
    {
        // accepts 1 - 26
        switch ($num) {
            case '1': $rand_value = 'A'; break;
            case '2': $rand_value = 'B'; break;
            case '3': $rand_value = 'C'; break;
            case '4': $rand_value = 'D'; break;
            case '5': $rand_value = 'E'; break;
            case '6': $rand_value = 'F'; break;
            case '7': $rand_value = 'G'; break;
            case '8': $rand_value = 'H'; break;
            case '9': $rand_value = 'I'; break;
            case '10': $rand_value = 'J'; break;
            case '11': $rand_value = 'K'; break;
            case '12': $rand_value = 'L'; break;
            case '13': $rand_value = 'M'; break;
            case '14': $rand_value = 'N'; break;
            case '15': $rand_value = 'O'; break;
            case '16': $rand_value = 'P'; break;
            case '17': $rand_value = 'Q'; break;
            case '18': $rand_value = 'R'; break;
            case '19': $rand_value = 'S'; break;
            case '20': $rand_value = 'T'; break;
            case '21': $rand_value = 'U'; break;
            case '22': $rand_value = 'V'; break;
            case '23': $rand_value = 'W'; break;
            case '24': $rand_value = 'X'; break;
            case '25': $rand_value = 'Y'; break;
            case '26': $rand_value = 'Z'; break;
        }

        return $rand_value;
    }
	
} /* generated with antollena framework */