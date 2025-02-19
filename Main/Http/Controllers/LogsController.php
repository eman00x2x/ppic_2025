<?php

namespace EO\Http\Controllers;

use EO\View;
use EO\Http\BaseController;
use EO\Services\LogsService;

class LogsController extends \EO\Http\BaseController
{
	private LogsService $logsService;

	function __construct()
	{
		$this->logsService = new LogsService();
	}

	function index()
	{
		$request = input()->all();
		$data = $this->logsService->getLogs($request);
		return View::set("/authenticated/administration/logs/index.php")->bind(data: $data);
	}

	function confirmSelection()
	{
		$request = input()->all();

		$log_ids = $request['ids'];
		$action = $request['action'];
		$action_value = $request['action_value'];

		$options = [
			"delete" => [
				"url" => url("logs.deleteMultiple"),
				"message" => "You are about to Delete (Permanent) " . count($log_ids) . " logs. All data related to these logs will be permanently deleted and this action is ireversible, Are you sure do you want to continue the deletion of these logs?"
			]
		];

		$filter['log_id'] = $log_ids;
		$logs = $this->logsService->getLogs($filter);

		$data = [
			"logs" => $logs,
			"ids" => implode(",", $log_ids),
			"action" => $action,
			"action_value" => $action_value,
			"url" => $options[$action]['url'],
			"message" => $options[$action]['message']
		];

		return View::set("/authenticated/administration/logs/confirmSelection.php")->bind(data: $data);
	}

	function deleteMultiple()
	{
		$request = input()->all();
		$ids = explode(",", $request['ids']);

		$this->logsService->destroyLogs($ids);
		return $this->handleMessageResponse("Traffics deleted successfully");
	}

	function delete($id = null)
	{
		$this->logsService->destroyLogs($id);
		return $this->handleMessageResponse("Logs deleted successfully");
	}

}