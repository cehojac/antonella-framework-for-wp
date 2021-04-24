<?php

namespace Dev\Commands;
 
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
 
class Makeup extends Command
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
        'nella',
        'nella2',
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
	
    protected $dir;


    protected function configure()
    {
        $this->setDescription('Compress and generate a .zip plugin`s file for upload to WordPress.')
             ->setHelp('Demonstration of custom commands created by Symfony Console component.');
    }
 
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->dir = \Console::$dir;	// recupera el directorio base
		$output->writeln("<comment>Antonella is packing the plugin</comment>");
        		
		$this->makeup();		
		
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
            throw new Exception('Directory '.$dirName.' does not exist');
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
}