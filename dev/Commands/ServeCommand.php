<?php
    
namespace Dev\Commands;

use Dotenv\Dotenv;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 *	@see https://symfony.com/doc/current/console.html
 *
 *	run
 *		php antonella serve
 *		php antonella --force
 *		php antonella --port 8080 --force
 */
class ServeCommand extends BaseCommand {
	
	 // the name of the command (the part after "antonella")
    protected static $defaultName = 'serve';
	
	// port
	protected $port = '8010';
	
	//testdir
	protected $testdir = 'wp-test';
	
	// locale
	protected $locale = 'en_US';
	
	protected $plugindir = '';
	
	protected $origin = '';
	
	protected $destiny = '';
	
	protected function configure()
    {
        $this->setDescription('Create a server local')
            ->setHelp('php antonella serve [--force]')
			->addOption('port', null, InputOption::VALUE_REQUIRED, 'port', 8010)
            ->addOption('force', null, InputOption::VALUE_NONE, 'refesh current value');
       
    }
 
    protected function execute(InputInterface $input, OutputInterface $output)
    {
		
		// retirve directory base
		$dir = $this->getDirBase();		
		
		// obtenemos las options desde consola
		$force = $input->getOption('force');
		
		if (!file_exists('.env')) {
            $output->writeln("<error>Antonella response: You need create and config the .env file. you have a .env-example file reference</error>");
            die();
        }
		
		$dotenv = Dotenv::create($dir);
        $dotenv->load();
        if (!getenv('DBNAME')) {
            $output->writeln("<error>Antonella response: You need config the DBNAME into .env file</error>");
            die();
        }
        
		$dbname = getenv('DBNAME');
        $dbuser = getenv('DBUSER');
        $dbpass = getenv('DBPASS');
        		
		$testdir = getenv('TEST_DIR') ? getenv('TEST_DIR') : $this->testdir;
        $port = getenv('PORT') ? getenv('PORT') : $this->port;
		$locale = getenv('LOCALE') ? getenv('LOCALE') : $this->locale;
		
		$slash = DIRECTORY_SEPARATOR;
        $pluginname = basename($dir);
        $filename = basename($dir).'.zip';
        $this->origen = $dir.$slash.$filename;
        $this->destiny = $dir.$slash.basename($testdir).$slash.'wp-content'.$slash.'plugins'.$slash.$filename;
        $this->plugindir = $dir.$slash.basename($testdir).$slash.'wp-content'.$slash.'plugins'.$slash.basename($dir);
        $extra_php = '';
		if ($force) $extra_php = ' --force';
		
		// instalamos el wp-cli
		$this->InstallWPCLI();
		
		$output->writeln("");
        if (!file_exists($testdir) && !is_dir($testdir)) {
            $output->writeln("<info>Folder Test not exist!!! Creating the folder...</info>");
            mkdir($testdir);
        }
        
        if (!file_exists($testdir.$slash.'index.php')) {
            $output->writeln("<info>Downloading WordPress [$locale]...</info>");
            system("php wp-cli.phar core download --locale=$locale --path=$testdir");
        }
		
		// si le pasamo el port y force como argumentos
		if ($force && $input->getOption('port')) {
			// seteamos el nuevo port
			$port = $input->getOption('port');
		}
		
		system("php wp-cli.phar config create --dbname=$dbname --dbuser=$dbuser --dbpass=$dbpass --path=$testdir  --extra-php=\" define( 'WP_DEBUG', true ); define( 'WP_DEBUG_LOG', true ); define( 'QM_ENABLE_CAPS_PANEL', true );  \" $extra_php");
        system("php wp-cli.phar core install --url=localhost:$port --title=\"Antonella Framework Test\" --path=$testdir --admin_user=test --admin_password=test --admin_email=test@test.com --skip-email");
        
		
		if ($force) {
			// setea siteurl y homeurl
			system("php wp-cli.phar option update home http://localhost:$port --path=$testdir");
			system("php wp-cli.phar option update siteurl http://localhost:$port --path=$testdir");
		}
		
		// comprimimos nuetros plugin
		$command = $this->getApplication()->find('makeup');
		$greetInput = new ArrayInput([]);
		$returnCode = $command->run($greetInput, $output);
		
		// unistall plugin
		$this->uninstall();
		
		// install pluging
        $this->install($output, $pluginname, $testdir, 
		[
			'show-current-template',
			'debug-bar',
			'query-monitor'
		]);
		
		$output->writeln(sprintf("http://localhost:%s", $port));
		$output->writeln("Remember: adminuser:test | password: test");
        system("php -S localhost:$port --docroot=$testdir");
	}
	
	public function InstallWPCLI() {
        if (!file_exists(sprintf('%s/wp-cli.phar', $this->getDirBase()))) {
            echo "Dowloading package wp-cliphar...\r\n";
			system('curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar');
        }
    }
	
	private function uninstall() {
		
		if (file_exists($this->plugindir) && is_dir($this->plugindir)) {
            $it = new \RecursiveDirectoryIterator($this->plugindir, \RecursiveDirectoryIterator::SKIP_DOTS);
            $files = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);
            foreach ($files as $file) {
                if ($file->isDir()) {
                    rmdir($file->getRealPath());
                } else {
                    unlink($file->getRealPath());
                }
            }
            // rmdir($plugindir);
        }
		
	}
	
	private function install($output, $pluginname, $testdir, $plugins = []) {
		
		file_exists($this->destiny) ? unlink($this->destiny) : false;
        copy($this->origen, $this->destiny);
        $zip = new \ZipArchive();
        $res = $zip->open($this->destiny);
        $zip->extractTo($this->plugindir);
        $zip->close();
        file_exists($this->destiny) ? unlink($this->destiny) : false;
		
		system("@php wp-cli.phar plugin activate $pluginname --path=$testdir");
        $output->writeln("Your plugin has been refreshed in test.");
		
		foreach ($plugins as $plugin)		
			system("@php wp-cli.phar plugin install $plugin --path=$testdir --activate");
        
		
	}
	
	
} /* generated with antollena framework */