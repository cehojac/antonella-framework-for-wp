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
 class HelloWorld extends Command
{
    protected function configure()
    {
        $this->setName('hello:world')
            ->setDescription('Muestra por pantalla Hello World!')
            ->setHelp('Demonstration of custom commands created by Symfony Console component.')
            ->addArgument('username', InputArgument::REQUIRED, 'Pass the username.')		// REQUIRED
			->addArgument('age', InputArgument::OPTIONAL, 'Dime tu edad')					// OPTIONAL
			->addOption(
				'color',
				null,
				InputOption::VALUE_OPTIONAL,
				'¿Cuál es tu color favorito?'
			);																				// OPTIONAL [--color=your-color] --or
																							//			[--color your-color]
    }
 
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $color = $input->getOption('color') ?: 'undefined';
		$age = $input->getArgument('age');
		
		if ($age)
			$output->writeln(sprintf('Hello World!, %s y tengo %s años', $input->getArgument('username'), $age));
		else
			$output->writeln(sprintf('Hello World!, my name is %s', $input->getArgument('username')));
		
		$output->writeln("Tu color preferido es: " . $color);
	}
}