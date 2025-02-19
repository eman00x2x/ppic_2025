<?php

namespace EO\CLI;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Style\SymfonyStyle;

class CommandDispatcher
{
	protected $application;

	public function __construct(array $commands = [])
	{
		$this->application = new Application();

		// Register all commands in the array
		foreach ($commands as $command) {
			$this->registerCommand($command);
		}
	}

	public function registerCommand($command)
	{
		$this->application->add($command);
	}

	/**
	 * Dispatch command to the framework's command handler
	 * @param array $argv
	 */
	public function dispatch(array $argv)
	{
		// Symfony's Input and Output handling
		$input = new ArgvInput($argv);
		$output = new ConsoleOutput();

		$io = new SymfonyStyle($input, $output);

		try {
			// Run the Symfony application (command execution)
			$this->application->run($input, $output);
		} catch (\Exception $e) {
			// Handle any errors or exceptions
			$io->error("{$e->getMessage()}");
		}
		
	}
}