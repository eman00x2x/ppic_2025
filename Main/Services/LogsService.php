<?php

namespace EO\Services;

use Pecee\Exceptions\InvalidArgumentException;
use Pecee\Http\Exceptions\MalformedUrlException;
use EO\Handlers\Exceptions\ValidationException;
use EO\Handlers\Exceptions\ResourceNotFoundException;
use EO\Service as Service;
use EO\Interfaces\IModel;
use EO\Facades\CacheFacade as Cache;
use EO\Model\LogModel as Logs;

class LogsService extends Service
{
	function __construct() 
	{
		parent::__construct();
	}

	function getLogs(array $request): array 
	{
		$this->buildFilters($request);
		try {
			self::$collections = Logs::getCollections($request);
			$items = self::$collections->getItems();

			if ($items->isNotEmpty()) {
				return $items->map(function($data, $key) {
					return $this->formatResultData($data);
				})->toArray();
			}
		} catch (MalformedUrlException $e) {
			// Throw a new exception of type ResourceNotFoundException with a message that includes the message from the caught exception
			throw new ResourceNotFoundException("Resource Not Found! " . $e->getMessage());
		}

		return $items->toArray();
	}

	function getLog(int $id): array 
	{
		self::$collections = Logs::getId($id);
		$items = self::$collections->getItems();

		if ($items->isNotEmpty()) {
			return $items->map(function($data, $key) {
				return $this->formatResultData($data);
			})->first()->toArray();
		} else{
			throw new ResourceNotFoundException("Resource Not Found! Logs ID: $id");
		}
		
		return $items->toArray();
	}

	function create(array $data)
	{
		return Logs::create($data);
	}

	function destroy($id): void 
	{
		Logs::delete(["log_id" => $id]);
	}

	public function destroyLogs(array $ids): void
	{
		Logs::delete(["log_id" => $ids]);
	}

	private function formatResultData(IModel $data): IModel
	{
		$data->date = date("d M Y", $data->time);

		if (preg_match('/(.+?) \{(.+)\}/', $data->message, $matches)) {
			$main_message = $matches[1];
			$context_json = "{".$matches[2]."}";
			$context = json_decode($context_json, true);

			$data->main_message = $main_message;
			$data->context = $context;
		}

		return $data;
	}

	private function buildFilters(array &$request): void 
	{
		if(isset($request['time'])) {

			if(isset($request['time']['from']) && !isset($request['time']['to'])) {
				$request['AND']['time[>=]'] = strtotime($request['time']['from']);
			}

			if(isset($request['time']['from']) &&  isset($request['time']['to'])) {
				$request['AND']['time[<>]'] = [strtotime($request['time']['from']), strtotime($request['time']['to'])];
			}

			unset($request['time']);
		}

	}
}