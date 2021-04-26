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
 */
class RefreshCommand extends BaseCommand {
	
	 // the name of the command (the part after "antonella")
    protected static $defaultName = 'refresh';
	
	//testdir
	protected $testdir = 'wp-test';
	
	protected $plugindir = '';
	
	protected $origin = '';
	
	protected $destiny = '';
	
	protected function configure()
    {
        $this->setDescription('Update current plugin')
            ->setHelp('Update current plugin');
            //->addArgument('name', InputArgument::REQUIRED, 'Argument description');
       
    }
 
    protected function execute(InputInterface $input, OutputInterface $output)
    {
		
		// retirve directory base
		$dir = $this->getDirBase();		
		
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
		
		$testdir = getenv('TEST_DIR') ? getenv('TEST_DIR') : $this->testdir;
		
		$slash = DIRECTORY_SEPARATOR;
        $pluginname = basename($dir);
        $filename = basename($dir).'.zip';
        $this->origen = $dir.$slash.$filename;
        $this->destiny = $dir.$slash.basename($testdir).$slash.'wp-content'.$slash.'plugins'.$slash.$filename;
        $this->plugindir = $dir.$slash.basename($testdir).$slash.'wp-content'.$slash.'plugins'.$slash.basename($dir);
				
		if (!file_exists(sprintf('%s/wp-cli.phar', $this->getDirBase()))) {
			$output->writeln("<error>wp-cli not found, please execute `php antonella serve`</error>");
		}
		
		// comprimimos nuetros plugin
		$command = $this->getApplication()->find('makeup');
		$greetInput = new ArrayInput([]);
		$returnCode = $command->run($greetInput, $output);
		
		// unistall plugin
		$this->uninstall();
		
		// install pluging
        $this->install($output, $pluginname, $testdir,[]);
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
		
		system("php wp-cli.phar plugin activate $pluginname --path=$testdir");
        $output->writeln("Your plugin has been refreshed in test.");
		
		foreach ($plugins as $plugin)		
			system("php wp-cli.phar plugin install $plugin --path=$testdir --activate");
        
		
	}
	
} /* generated with antollena framework */