<?php
    
namespace Dev\Commands;

use Dotenv\Dotenv;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 *	@see https://symfony.com/doc/current/console.html
 */
class ThemeDeleteCommand extends BaseCommand {
	
	 // the name of the command (the part after "antonella")
    protected static $defaultName = 'theme:delete';
	
	protected function configure()
    {
        
		$this->setDescription('Deletes one or more themes')
            ->setHelp('php antonella theme:delete')
            ->addArgument('theme', InputArgument::OPTIONAL, 'One or more themes to delete. For example: twentynineteen[,twentytwenty]')
			->addOption('all', null, InputOption::VALUE_NONE, 'If set, all themes will be deleted except active theme')
			->addOption('force', null, InputOption::VALUE_NONE, 'To delete active theme use this')
			->addOption('debug', null, InputOption::VALUE_NONE, 'mode debug, Only show command and not execute');
    }
 
    protected function execute(InputInterface $input, OutputInterface $output)
    {
		
		// retirve directory base
		$dir = $this->getDirBase();	
		
		$dotenv = Dotenv::create($dir);
        $dotenv->load();	

		// read options from console
		$cmd = 'theme delete';
		
		if ( $input->getArgument('theme') ) {
			$cmd.=sprintf(' %1$s', implode(' ', explode(',', $input->getArgument('theme'))));
		}
		else {
			if ( $input->getOption('all') )
				$cmd.=' --all';
			if ( $input->getOption('force') )
				$cmd.=' --force';
		}
		
		$testDIR = getenv('TEST_DIR') ?: 'wp-test';
		$cmd.=" --path=$testDIR";
		
		if ( $input->getOption('debug') ) {
			echo "$cmd\r\n";
			die();
		}
		
		system("php wp-cli.phar $cmd");		
		
	}
	
} /* generated with antollena framework */