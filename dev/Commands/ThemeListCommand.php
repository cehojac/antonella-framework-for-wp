<?php
    
namespace Dev\Commands;

use Dotenv\Dotenv;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 *	@see https://symfony.com/doc/current/console.html
 *		 https://developer.wordpress.org/cli/commands/theme/list/
 */
class ThemeListCommand extends BaseCommand {
	
	 // the name of the command (the part after "antonella")
    protected static $defaultName = 'theme:list';
	
	protected function configure()
    {
        $this->setDescription('Gets a list of themes')
            ->setHelp('php antonella theme:list')
            ->addOption('filter', null, InputOption::VALUE_REQUIRED, 'Filter results based on the value of a field, ex. --filter=name,my-theme')
			->addOption('field', null, InputOption::VALUE_REQUIRED, 'Prints the value of a single field for each theme, ex. --field=name')
			->addOption('fields', null, InputOption::VALUE_REQUIRED, 'Limit the output to specific object fields, ex. --fields=name,status')
			->addOption('format', null, InputOption::VALUE_REQUIRED, 'Render output in a particular format. Values: table|csv|json|count|yaml')
			->addOption('debug', null, InputOption::VALUE_NONE, 'mode debug, Only show command and not execute');
       
    }
 
    protected function execute(InputInterface $input, OutputInterface $output)
    {

		// retirve directory base
		$dir = $this->getDirBase();	
		
		$dotenv = Dotenv::create($dir);
        $dotenv->load();	

		// read options from console
		$cmd = 'theme list';
		if ( $input->getOption('filter') ) {
			$args = explode(":", $input->getOption('filter'));
			foreach ($args as $arg) {
				list($field, $value) = explode(',', $arg);
				$cmd.=sprintf(' --%1$s=%2$s', $field, $value);
			}
		}
		if ( $input->getOption('field') )
			$cmd.=sprintf(' --field=%1$s', $input->getOption('field'));
		if ( $input->getOption('fields') )
			$cmd.=sprintf(' --fields=%1$s', $input->getOption('fields'));
		if ( $input->getOption('format') )
			$cmd.=sprintf(' --format=%1$s', $input->getOption('format'));
		
		$testDIR = getenv('TEST_DIR') ?: 'wp-test';
		$cmd.=" --path=$testDIR";
		
		if ( $input->getOption('debug') ) {
			echo "$cmd\r\n";
			die();
		}
		
		system("php wp-cli.phar $cmd --path=$testDIR");		
	}
	
} /* generated with antollena framework */