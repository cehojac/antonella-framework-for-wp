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
 class Add extends Command
{
    protected $understant = '<comment>Antonella no understand you. please read the manual in https://antonellaframework.com</comment>';
    protected function configure()
    {
        $this->setName('add')
            ->setDescription('Add Antonella`s Modules. Now only is possible add blade and dd')
            ->setHelp('Demonstration of custom commands created by Symfony Console component.')
            ->addArgument('module', InputArgument::REQUIRED, 'Blade or DD');																		// OPTIONAL [--color=your-color] --or
																							//			[--color your-color]
    }
 
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        switch ($input->getArgument('module')) {
            case 'blade':
                return $this->AddBlade();
                break;
            case 'dd':
                return $this->AddDD();
                break;
            default:
                $output->writeln($this->understant);
                
        }
        die();
	}
    protected function AddBlade()
    {
        echo "You need add blade? (Template system)  Type 'yes' to continue: ";
        $handle = fopen('php://stdin', 'r');
        $line = fgets($handle);
        echo $line;
        fclose($handle);
        if (trim($line) === 'yes' || trim($line === 'y')) {
            echo "\n";
            echo "Adding Blade... \n";
            exec('composer require jenssegers/blade');
            echo "Blade Added! \n";
        } else {
            echo "ABORTING!\n";
            echo "Remember: if you need add blade only  type 'php antonella add blade' ";
            exit;
        }
    }
    protected function AddDD()
    {
        echo "\n";
        echo "Adding Var-Dumper dd() ... \n";
        exec('composer require symfony/var-dumper --dev');
        echo "Var-Dumper dd() Added! \n";
    }
}