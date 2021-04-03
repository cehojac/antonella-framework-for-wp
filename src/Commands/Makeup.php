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
 class Makeup extends Command
{
    
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
        'nella'
    ];
    protected $dirs_to_exclude_win = [
        '.',
        '..',
        '.git',
        '.github',
        'docu',
        'docs',
        'wp-test',
        'resources'.DIRECTORY_SEPARATOR.'css',
        'resources'.DIRECTORY_SEPARATOR.'js',
		'src'.DIRECTORY_SEPARATOR.'Commands',
        'stubs',
		'cypress',
        'node_modules',
        'components',
        'vendor'.DIRECTORY_SEPARATOR.'vlucas',
        'vendor'.DIRECTORY_SEPARATOR.'symfony'.DIRECTORY_SEPARATOR.'polyfill-php80',
        'vendor'.DIRECTORY_SEPARATOR.'symfony'.DIRECTORY_SEPARATOR.'polyfill-mbstring',
		'vendor'.DIRECTORY_SEPARATOR.'symfony'.DIRECTORY_SEPARATOR.'console',
		'vendor'.DIRECTORY_SEPARATOR.'symfony'.DIRECTORY_SEPARATOR.'var-dumper',
    ];
    protected $dirs_to_exclude_linux = [
        '.git',
        '.github',
        'docu',
        'docs',
        'wp-test',
        'resources'.DIRECTORY_SEPARATOR.'css',
        'resources'.DIRECTORY_SEPARATOR.'js',
		'src'.DIRECTORY_SEPARATOR.'Commands',
		'stubs',
        'cypress',
        'node_modules',
        'components',
        'vendor'.DIRECTORY_SEPARATOR.'vlucas',
        'vendor'.DIRECTORY_SEPARATOR.'symfony'.DIRECTORY_SEPARATOR.'polyfill-php80',
        'vendor'.DIRECTORY_SEPARATOR.'symfony'.DIRECTORY_SEPARATOR.'polyfill-mbstring',
		'vendor'.DIRECTORY_SEPARATOR.'symfony'.DIRECTORY_SEPARATOR.'console',		
        'vendor'.DIRECTORY_SEPARATOR.'symfony'.DIRECTORY_SEPARATOR.'var-dumper',
    ];
    protected $dir;

    
    protected function configure()
    {
        $this->setName('makeup')
            ->setDescription('Compress and generate a .zip plugin`s file for upload to WordPress.')
            ->setHelp('Demonstration of custom commands created by Symfony Console component.');														// OPTIONAL [--color=your-color] --or
																							//			[--color your-color]
    }
 
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->dir = realpath(dirname(__FILE__) . '/../..');
        $output->writeln("<comment>Antonella is packing the plugin</comment>");
        $SO = strtoupper(substr(PHP_OS, 0, 3));
        
        if ($SO === 'WIN') {
            $this->makeup_win();
        } else {
            $this->makeup_linux();
        }
        $output->writeln("<info>The plugin's zip file is OK!</info>");
	}
    /** comprime para windows */
    public function makeup_win()
    {
       
        file_exists($this->dir.'/'.basename($this->dir).'.zip') ? unlink($this->dir.'/'.basename($this->dir).'.zip') : false;
        $zip = new \ZipArchive();
        $zip->open(basename($this->dir).'.zip', \ZipArchive::CREATE);

        $dirName = $this->dir;

        if (!is_dir($dirName)) {
            throw new Exception('Directory '.$dirName.' does not exist');
        }

        $dirName = realpath($dirName);
        if (substr($dirName, -1) != '/') {
            $dirName .= '/';
        }


        $dirStack = [$dirName];
      
        $cutFrom = strrpos(substr($dirName, 0, -1), '/') + strlen($this->dir) + 1;

        while (!empty($dirStack)) {
            $currentDir = array_pop($dirStack);
            $filesToAdd = [];

            $dir = dir($currentDir);

            while (false !== ($node = $dir->read())) {
                if (in_array($node, $this->files_to_exclude) || in_array($node, $this->dirs_to_exclude_win)) {
                    continue;
                }
                if (is_dir($currentDir.$node)) {
                    array_push($dirStack, $currentDir.$node.'/');
                }
                if (is_file($currentDir.$node)) {
                    $filesToAdd[] = $node;
                }
            }

            $localDir = substr($currentDir, $cutFrom);

            $zip->addEmptyDir($localDir);
            foreach ($filesToAdd as $file) {
                $zip->addFile($currentDir.$file, $localDir.$file);
            }
        }

        $zip->close();
    }

    /** 
    * Source: https://stackoverflow.com/questions/20264737/php-list-directory-structure-and-exclude-some-directories
    */
    public function makeup_linux() 
    {
    
        file_exists($this->dir.'/'.basename($this->dir).'.zip') ? unlink($this->dir.'/'.basename($this->dir).'.zip') : false;

        $zip = new \ZipArchive();
        $zip->open(basename($this->dir).'.zip', \ZipArchive::CREATE);

        $dirName = $this->dir;

        if (!is_dir($dirName)) {
            throw new Exception('Directory '.$dirName.' does not exist');
        }
        
        /** esto es lo unico nuevo exclude de antemano node_modules */
        $dirToExclude = $this->dirs_to_exclude_linux;
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

        // remove dir/path
        for ($i = 0; $i < $zip->numFiles; ++$i) {
            $entry_info = $zip->statIndex($i);
            foreach ($dirToExclude as $dirExclude) {
                $pos = strpos($entry_info['name'], $dirExclude);
                if ($pos !== false) {
                    $zip->deleteIndex($i);
                    //echo "Remove: " . $entry_info['name'] . " : dirExclude: " . $dirExclude . "\r\n";
                }
            }
        }
        
        $zip->close();
    }	
}