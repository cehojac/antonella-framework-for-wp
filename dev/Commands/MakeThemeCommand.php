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
class MakeThemeCommand extends BaseCommand {
	
	 // the name of the command (the part after "antonella")
    protected static $defaultName = 'make:theme';
	
	protected function configure()
    {
        $this->setDescription('Generates starter code for a theme based on _s')
            ->setHelp('php antonella make:theme <slug> [--activate|-a] [--enable-network] [--theme_name=<title>] [--author=<full-name>] [--author_uri=<uri>] [--sassify] [--woocommerce]')
            ->addArgument('slug', InputArgument::REQUIRED, 'The slug for the new theme, used for prefixing functions')
			->addOption('activate', 'a', InputOption::VALUE_NONE, 'Activate the newly downloaded theme')
			->addOption('enable-network', null, InputOption::VALUE_NONE, 'Enable the newly downloaded theme for the entire network')
			->addOption('theme_name', null, InputOption::VALUE_REQUIRED, 'What to put in the ‘Theme Name:’ header in ‘style.css’')
			->addOption('author', null, InputOption::VALUE_REQUIRED, 'What to put in the ‘Author:’ header in ‘style.css’')
			->addOption('author_uri', null, InputOption::VALUE_REQUIRED, 'What to put in the ‘Author URI:’ header in ‘style.css’')
			->addOption('sassify', null, InputOption::VALUE_NONE, 'Include stylesheets as SASS')
			->addOption('woocommerce', null, InputOption::VALUE_NONE, 'Include WooCommerce boilerplate files')
			->addOption('debug', null, InputOption::VALUE_NONE, 'mode debug, Only show command and not execute');
       
    }
 
    protected function execute(InputInterface $input, OutputInterface $output)
    {
		
		// retirve directory base
		$dir = $this->getDirBase();	
		
		$dotenv = Dotenv::create($dir);
        $dotenv->load();		
		
		// retrive argument
		$cmd = sprintf('scaffold _s %1$s', $input->getArgument('slug'));
		
		if ( $input->getOption('activate') )
			$cmd.=' --activate';
		if ( $input->getOption('theme_name') )
			$cmd.=sprintf(' --theme_name="%s"', $input->getOption('theme_name'));
		if ( $input->getOption('author') )
			$cmd.=sprintf(' --author="%s"', $input->getOption('author'));
		if ( $input->getOption('author_uri') )
			$cmd.=sprintf(' --author_uri="%s"', $input->getOption('author_uri'));
		if ( $input->getOption('sassify') )
			$cmd.=' --sassify';
		if ( $input->getOption('woocommerce') )
			$cmd.=' --woocommerce';
		$testDIR = getenv('TEST_DIR') ?: 'wp-test';
        
		$cmd.=" --path=$testDIR --force";
		
		if ( $input->getOption('debug') ) {
			echo "$cmd\r\n";
			die();
		}
		
		$editor = str_replace('\\', '/', sprintf('%s/%s/wp-content/themes/%s/.editorconfig', $dir, $testDIR, $input->getArgument('slug')));
        if (file_exists($editor)) {
            @unlink($editor);
        }		
		
		system("php wp-cli.phar $cmd"); // muestra los avisos en consola
		
	}
	
} /* generated with antollena framework */