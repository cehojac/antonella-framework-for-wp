<?php
    
namespace Dev\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *	@see https://symfony.com/doc/current/console.html
 */
class SetNameCommand extends BaseCommand {
	
	 // the name of the command (the part after "antonella")
    protected static $defaultName = 'setname';
	
	protected function configure()
    {
        $this->setDescription('Set Plugin Name')
			->setHidden(true)
            ->setHelp('php antonella setname');       
    }
 
    protected function execute(InputInterface $input, OutputInterface $output)
    {
		
		// valores por default
		$params = [
			'name' => ucwords(str_replace('-', ' ', basename($this->getDirBase()))),
			'description' => 'My first plugin with Antonella Framework',
			'textdomain' => basename($this->getDirBase()),
			'version' => '1.0'
		];
		
				
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
		
		$params['username'] = $params['username'] ?: 'Carlos Herrera';
		$params['email'] = $params['email'] ?: '';
		
		$this->setNamePlugin($params, $output);
	}
	
	private function setNamePlugin($params, $output) {
		
		if (file_exists('antonella-framework.php')) {
			$name = sprintf('%1$s.php',basename($this->getDirBase()));

			$target = 'antonella-framework.php';							
            $content = explode("\n", file_get_contents($target));

			$this->__replace_if_matches($content,
			[
                'Plugin Name' => sprintf('Plugin Name: %1$s', $params['name']),
				'Description' => sprintf('Description: %1$s', $params['description']),
				'Version' => sprintf('Version: %1$s', $params['version']),
				'Author URI' => 'Author URI:',
				'Author' => sprintf('Author: %1$s <%2$s>', preg_replace('~[\r\n]+~', '', $params['username']), preg_replace('~[\r\n]+~', '', $params['email'])),
				'Text Domain' => sprintf('Text Domain: %1$s', $params['textdomain'])
			]);

			$out = implode("\n", $content);
			file_put_contents($target, $out);
			$output->writeln("<info>The file has been updated</info>");

			rename("antonella-framework.php", $name);
			$output->writeln("<info>the file has been renamed</info>");
			die();
		}

		// $output->writeln("<info>Success</info>");
	}
	
} /* generated with antollena framework */