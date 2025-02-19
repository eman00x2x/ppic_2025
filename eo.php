#!/usr/bin/env php
<?php

require __DIR__ . '/Config/config.php';
require __DIR__ . '/Vendor/autoload.php';

use EO\EOEngine;
use EO\CLI\CommandDispatcher;

(new EOEngine())->bootstrapEngine();

$commands = [
    new EO\CLI\Commands\Generate\GenerateModelCommand(),
    new EO\CLI\Commands\Generate\GenerateServiceCommand(),
    new EO\CLI\Commands\Generate\GenerateControllerCommand(),
];

// Dispatch command to the framework's command handler
(new CommandDispatcher($commands))->dispatch($argv);

/**
 * Usage Linux:
 * php eo.php generate:model [ModelName]
 * php eo.php generate:service [ServiceName]
 * 
 * Usage Windows:
 * eo.bat generate:model [ModelName]
 * eo.bat generate:service [ServiceName]
 */