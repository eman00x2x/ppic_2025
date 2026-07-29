<?php

use EO\Handlers\CacheHandler;
use EO\Handlers\LoggerHandler;
use EO\Handlers\ScheduleHandler;
use EO\Facades\LoggerFacade as Logger;
use EO\Facades\CacheFacade;
use EO\Support\Helpers\EnvParser;

require_once("Config/config.php");
require_once("Main/Support/helpers.php");
require_once("Vendor/autoload.php");
require_once("Vendor/pecee/simple-router/helpers.php");

new EnvParser( ROOT . "/.env" );

define("DOMAIN", $_ENV['DOMAIN']);
define("CDN", $_ENV['CDN']);
define("DEVELOPMENT", $_ENV['DEVELOPMENT']);

if ($_ENV['CACHE_ENABLE']) {
	CacheFacade::setCache( new CacheHandler() );
}
Logger::setLogger( new LoggerHandler() );

try {
	(new ScheduleHandler())->run();
} catch(Exception $e) {
	Logger::log("critical", "Scheduler error during run", [
		"route" => "cron.php",
		"data" => [
			"message" => $e->getMessage(),
		]
	]);
}