<?php
    
namespace Dev\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 *	@see https://symfony.com/doc/current/console.html
 */
class SetNameCommand extends BaseCommand {
	
	 // the name of the command (the part after "antonella")
    protected static $defaultName = 'setname';
	
	protected function configure()
    {
        $this->setDescription('Set Plugin Name, example: "My First Plugin"')
            ->setHelp('php antonella setname "Plugin Name" [--textdomain plugin-name]')
            ->addArgument('name', InputArgument::REQUIRED, 'Plugin Name')
			->addOption('textdomain', null, InputOption::VALUE_REQUIRED)
			->addOption('description', null, InputOption::VALUE_REQUIRED)
			->addOption('username', null, InputOption::VALUE_REQUIRED)
			->addOption('email', null, InputOption::VALUE_REQUIRED);
       
    }
 
    protected function execute(InputInterface $input, OutputInterface $output)
    {
		
		// retirve directory base
		// $dir = $this->getDirBase();		
		
		$name = $input->getArgument('name');
		$params = [
			'name' => $name,
			'textdomain' => $this->slug_title($name)
		];
		
		echo "ola";
		die();
		
		// valores desde consola
		$arguments = ['textdomain', 'description', 'username', 'email'];
		foreach ($arguments as $arg) {
			if ( $input->getOption($arg))
				$params[$arg] = $input->getOption($arg);
		}
		
		// valores desde git
		$arguments = ['username', 'email'];
		foreach ($arguments as $arg) {
			if ( !isset($params[$arg]) ) {
				$key = ( $arg === 'username' ? 'name' : $arg );
				$cmd = sprintf("git config user.%s 2>&1", $key);
				if ( shell_exec($cmd) ) {
					$params[$arg] = shell_exec($cmd);
				}
			}
		}
		
		var_dump( $params );
		die();
		
		$this->setNamePlugin($params, $output);
		
		// for show message in console
		// $output->writeln("<info>Your success message</info>");
		// $output->writeln("<error>Your error message</error>");
		
	}
	
	private function setNamePlugin($params, $output) {
		
	}
	
} /* generated with antollena framework */