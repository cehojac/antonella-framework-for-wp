<?php
    
namespace Dev\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 *	@see https://symfony.com/doc/current/console.html
 */
class BatchCommand extends BaseCommand {
	
	 // the name of the command (the part after "antonella")
    protected static $defaultName = 'batch';
	
	protected function configure()
    {
        
		$this->setDescription('Execute batch command')
            ->setHelp('php antonella batch')
            ->addOption('file', null, InputOption::VALUE_REQUIRED, 'File to execute the batch command, por default execute ./batch.php');
       
    }
 
    protected function execute(InputInterface $input, OutputInterface $output)
    {
		
		$files = [
			'batch.php',
			'dev/batch.php'
		];

		if ( $input->getOption('file') )
			array_unshift($files, $input->getOption('file'));	// <filename>.php || to/path/<filename>.php 
		
		$filename = 'batch.php';
		$find = false;
		foreach ($files as $file) {
			$file = str_replace('\\', '/', sprintf('%1$s/%2$s', $this->getDirBase(), $file));
			if (file_exists($file)) {
				$filename = $file;
				$find = true;
				break;
			}
		}

		if (!$find) {
			$filename = $input->getOption('file') ?: $filename;
			$output->writeln("<error>Error: file $filename not found in the given path</error>");
			die();
		}	

		$commands = require_once( $filename );
		$output->writeln("*** Executing [$filename]");
		foreach ($commands as $command) {
			$output->writeln("*** Run command: [$command]");
			system("$command");
		}
	}
	
} /* generated with antollena framework */