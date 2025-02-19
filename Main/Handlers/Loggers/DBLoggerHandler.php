<?php

namespace EO\Handlers\Loggers;

use Monolog\Level;
use Monolog\Logger;
use Monolog\LogRecord;
use Monolog\Handler\AbstractProcessingHandler;
use EO\Services\LogsService;

class DBLoggerHandler extends AbstractProcessingHandler
{
	private LogsService $logsService;
	public function __construct(int|string|Level $level = Level::Debug, bool $bubble = true) {
		parent::__construct($level, $bubble);
		$this->logsService = new LogsService();
	}

	protected function write(LogRecord $record): void {
		$data = [
			'channel' => $record->channel,
			'level' => [
				"name" => $record->level->getName(),
				"code" => $record->level->value
			],
			'message' => $record->formatted,
			'time' => $record->datetime->format('U')
		];
		
		$this->logsService->create($data);
	}
}