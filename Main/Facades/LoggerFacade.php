<?php

namespace EO\Facades;

use EO\Handlers\LoggerHandler;

class LoggerFacade
{
    public static LoggerHandler $logger;
    public static function setLogger(Loggerhandler $logger) 
	{
		self::$logger = $logger;
    }

    public static function log($log_type, $message, $data = []) 
	{
		self::$logger->log($log_type, $message, $data);
    }
}