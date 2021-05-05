<?php

namespace Dev\Commands;
 
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
 
class MakeupCommand extends BaseCommand
{
    
    // the name of the command (the part after "antonella")
    protected static $defaultName = 'makeup';
    
    protected $files_to_exclude = [
        '.gitignore',
        '.gitmodules',
        '.gitattributes',
        '.travis.yml',
        'composer.json',
        'composer.lock',
        'package-lock.json',
        'antonella',
		'console',
        'readme.md',
        'bitbucket-pipelines.yml',
        'CHANGELOG.md',
        'CONTRIBUTING.md',
        'Gruntfile.js',
        'LICENSE',
        'readme.md',
        'README.md',
        'readme.txt',
        'bitbucket-pipelines.yml',
        'wp-cli.phar',
        '.env',
        '.env-example',
        'package.json',
        'mix-manifest.json',
        'webpack.mix.js',
        'webpack.config.js',
        'dev'.DIRECTORY_SEPARATOR.'Classes'.DIRECTORY_SEPARATOR.'StubGenerator.php',
		'StubGenerator.php'
    ];
    
    protected $dirs_to_exclude = [
        '.git',
        '.github',
        'dev',
        'docs',
        'wp-test',
        'resources'.DIRECTORY_SEPARATOR.'css',
        'resources'.DIRECTORY_SEPARATOR.'js',
		'cypress',
        'node_modules',
        'vendor'.DIRECTORY_SEPARATOR.'vlucas',
        'vendor'.DIRECTORY_SEPARATOR.'symfony'.DIRECTORY_SEPARATOR.'console',		
        'vendor'.DIRECTORY_SEPARATOR.'symfony'.DIRECTORY_SEPARATOR.'var-dumper',
	];
	
	protected $dirs_to_exclude_win=[
        '.',
        '..',
        '.git',
        '.github',
        'dev',
		'docs',
        'wp-test',
        'resources'.DIRECTORY_SEPARATOR.'css',
        'resources'.DIRECTORY_SEPARATOR.'js',
		'cypress',
        'node_modules',
        'vendor'.DIRECTORY_SEPARATOR.'vlucas',
        'vendor'.DIRECTORY_SEPARATOR.'symfony'.DIRECTORY_SEPARATOR.'console',		
        'vendor'.DIRECTORY_SEPARATOR.'symfony'.DIRECTORY_SEPARATOR.'var-dumper',
    ];
	
    protected $dir;


    protected function configure()
    {
        $this->setDescription('Compress and generate a .zip plugin`s file for upload to WordPress.')
             ->setHelp('Demonstration of custom commands created by Symfony Console component.');
    }
 
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->dir = $this->getDirBase();	// recupera el directorio base
		$output->writeln("<comment>Antonella is packing the plugin</comment>");
        		
		$SO=strtoupper(substr(PHP_OS, 0, 3));
        if($SO==='WIN') {
			$this->makeup_win();
        }
        else {
			$this->makeup();
		}
		
        $output->writeln("<info>============================</info>");
        $output->writeln("<info>The plugin's zip file is OK!</info>");
        $output->writeln("<info>============================</info>");
        
	}
   
    private function makeup() 
    {
    
        file_exists($this->dir.'/'.basename($this->dir).'.zip') ? unlink($this->dir.'/'.basename($this->dir).'.zip') : false;

        $zip = new \ZipArchive();
        $zip->open(basename($this->dir).'.zip', \ZipArchive::CREATE);

        $dirName = $this->dir;

        if (!is_dir($dirName)) {
            throw new \Exception('Directory '.$dirName.' does not exist');
        }
        
        $dirToExclude = $this->dirs_to_exclude;
        $files = new \RecursiveIteratorIterator( 
            new \RecursiveCallbackFilterIterator(
                new \RecursiveDirectoryIterator(
                    $this->dir,
                    \RecursiveDirectoryIterator::SKIP_DOTS
                ),
                function ($fileInfo, $key, $iterator) use ($dirToExclude) {
                    return $fileInfo->isFile() || !in_array($fileInfo->getBaseName(), $dirToExclude);
                }
            )
        );
        
        $dirName = realpath($dirName);
        $filesToExclude = $this->files_to_exclude;
        
        foreach ($files as $name => $file) {
            if (!$file->isDir() && !in_array($file->getFilename(), $filesToExclude)) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($dirName) + 1);
                $zip->addFile($filePath, $relativePath);			
            }
        }

        for ($i = 0; $i < $zip->numFiles; ++$i) {
            $entry_info = $zip->statIndex($i);
            foreach ($dirToExclude as $dirExclude) {
                $pos = strpos($entry_info['name'], $dirExclude);
                if ($pos !== false) {
                    $zip->deleteIndex($i);
                }
            }
        }
        
        $zip->close();
    }

	private function makeup_win()
    {

        file_exists($this->dir.'/'.basename($this->dir).'.zip')?unlink($this->dir.'/'.basename($this->dir).'.zip'):false;
        $zip = new \ZipArchive(); 
        $zip->open(basename($this->dir).'.zip', \ZipArchive::CREATE); 

        $dirName =$this->dir; 

        if (!is_dir($dirName)) { 
            throw new \Exception('Directory ' . $dirName . ' does not exist'); 
        } 

        $dirName = realpath($dirName);
		if (substr($dirName, -1) != '/') { 
            $dirName.= '/'; 
        }

		/* 
        * NOTE BY danbrown AT php DOT net: A good method of making 
        * portable code in this case would be usage of the PHP constant 
        * DIRECTORY_SEPARATOR in place of the '/' (forward slash) above. 
        */ 

        $dirStack = array($dirName); 
        //Find the index where the last dir starts 
        $cutFrom = strrpos(substr($dirName, 0, -1), '/')+strlen($this->dir)+1; 

        while (!empty($dirStack)) { 
            $currentDir = array_pop($dirStack); 
            $filesToAdd = array(); 

            $dir = dir($currentDir); 
           
            while (false !== ($node = $dir->read())) { 
             
				if(in_array($node,$this->files_to_exclude)||in_array($node,$this->dirs_to_exclude_win)){
                    continue; 
                } 
                if (is_dir($currentDir . $node)) { 
                   
                    array_push($dirStack, $currentDir . $node . '/'); 
                } 
                if (is_file($currentDir . $node)) { 
                    $filesToAdd[] = $node; 
                } 
            } 

            $localDir = substr($currentDir, $cutFrom);
            
           
            $zip->addEmptyDir($localDir); 
            foreach ($filesToAdd as $file) { 
                $zip->addFile($currentDir . $file, $localDir . $file);
               // echo("Added $localDir$file into plugin  \n"); 
            } 
        }

		// updated 28/4/2021 by david
		for ($i = 0; $i < $zip->numFiles; ++$i) {
            $entry_info = $zip->statIndex($i);
            foreach ($this->dirs_to_exclude as $dirExclude) {
				$dirExclude = str_replace('\\', '/', $dirExclude);
				$pos = strpos($entry_info['name'], $dirExclude);
                if ($pos !== false) {
                    $zip->deleteIndex($i);
                }
            }
        }

        $zip->close();
        
    }
}