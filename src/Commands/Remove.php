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
 class Remove extends Command
{
    protected $understant = '<comment>Antonella no understand you. please read the manual in https://antonellaframework.com</comment>';
    protected function configure()
    {
        $this->setName('remove')
            ->setDescription('Remove Antonella`s Modules. Now only is possible add blade and dd')
            ->setHelp('Demonstration of custom commands created by Symfony Console component.')
            ->addArgument('module', InputArgument::REQUIRED, 'Blade or DD');																		// OPTIONAL [--color=your-color] --or
																							//			[--color your-color]
    }
 
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        switch ($input->getArgument('module')) {
            case 'blade':
                return $this->RemoveBlade();
                break;
            case 'dd':
                return $this->RemoveDD();
                break;
            default:
                $output->writeln($this->understant);
                
        }
        die();
	}
    protected function RemoveDD()
    {
        echo "Removing Var-Dumper dd()... \n";
        exec('composer remove symfony/var-dumper');
        echo "Var-Dumper dd() Removed! \n";
    }
    protected function RemoveBlade()
    {
        echo "Removing Blade... \n";
        exec('composer remove jenssegers/blade');
        echo "Blade Removed! \n";
    }
}