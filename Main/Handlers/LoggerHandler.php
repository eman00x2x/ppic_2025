<?php

namespace EO\Handlers;

use Monolog\Logger;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use EO\Handlers\Loggers\DBLoggerHandler;

class LoggerHandler
{
	private Logger $logger;

	public function __construct() 
	{
		$this->logger = new Logger('EOLogger');
		$this->logger->pushHandler( $this->setDBLogger() );
	}

	public function log(string $log_type, string $message, array $data = []): void
	{
		$this->logger->$log_type($message, $data);
	}

	private function setDBLogger() {
		$date_format = "M d Y g:i a";
		$output = "%datetime% > %level_name% > %message% %context% %extra%\n";
		$formatter = new LineFormatter($output, $date_format);

		$db_handler = new DBLoggerHandler();
		$db_handler->setFormatter($formatter);

		return $db_handler;
	}

}