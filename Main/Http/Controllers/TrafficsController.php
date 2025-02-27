<?php

namespace EO\Http\Controllers;

use EO\View;
use EO\Http\BaseController;
use EO\Services\TrafficService;
use EO\Auth\Auth;

class TrafficsController extends \EO\Http\BaseController
{
	private TrafficService $trafficService;

	function __construct() 
	{
		$this->trafficService = new TrafficService();
	}

	function index() 
	{
		$request = input()->all();

		$request['created_at'] = [
			"from" => $request['created_at']['from'] ?? date("Y-m-d", strtotime("-12 months")),
			"to" => $request['created_at']['to'] ?? date("Y-m-d", DATE_NOW)
		];

		if(!Auth::isAdmin()) {
			$request['account_id'] = Auth::user()->id;
		}

		$data['traffics'] = $this->trafficService->totalTrafficsPerUrl($request);

		unset($request['page']);
		$data['filters'] = $request;

		return View::set(path: "/authenticated/traffics/index.php")->bind(data: $data);
	}

	function saveNew() 
	{
		$request = input()->all();

		if(str_contains($request['traffic']['url'], "?_ga=") || str_contains($request['traffic']['url'], "?_gl=")) {
			return $this->handleMessageResponse("Traffics ignored");
		}

		$traffics = $this->trafficService->saveToFile($request);
		return $this->handleMessageResponse("Traffics saved");
	}

	function confirmSelection() 
	{
		$request = input()->all();

		$traffic_ids = $request['ids'];
		$action = $request['action'];
		$action_value = $request['action_value'];

		$options = [
			"delete" => [
				"url" => url("traffics.deleteMultiple"),
				"message" => "You are about to Delete (Permanent) " . count($traffic_ids) . " traffics. All data related to these traffics will be permanently deleted and this action is ireversible, Are you sure do you want to continue the deletion of these traffics?"
			]
		];

		$filter['traffic_id'] = $traffic_ids;
		$traffics = $this->trafficService->getTraffics($filter);

		$data = [
			"traffics" => $traffics,
			"ids" => implode(",", $traffic_ids),
			"action" => $action,
			"action_value" => $action_value,
			"url" => $options[$action]['url'],
			"message" => $options[$action]['message']
		];

		return View::set("/authenticated/traffics/confirmSelection.php")->bind(data: $data);
	}

	function deleteMultiple() 
	{
		$this->authorize("delete_traffic");

		$request = input()->all();
		$ids = explode(",", $request['ids']);

		$this->trafficService->destroySelected($ids);
		return $this->handleMessageResponse("Traffics deleted successfully");
	}

	function delete($id = null)
	{
		$this->authorize("delete_traffic");
		$this->trafficService->destroySelected($id);
		return $this->handleMessageResponse("Traffics deleted successfully");
	}

	public function download(): void 
	{
		$accountId = Auth::isAdmin() ? null : Auth::user()->id;

		$this->trafficService->downloadData($accountId);
	}

	function trafficsDaily() {
		
	}

}