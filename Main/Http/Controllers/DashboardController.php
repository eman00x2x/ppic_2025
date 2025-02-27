<?php

namespace EO\Http\Controllers;

use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;
use EO\Handlers\Exceptions\ResourceNotFoundException;
use EO\Handlers\Exceptions\ValidationException;
use EO\Auth\Auth;
use EO\Interfaces\IController;
use EO\Services\AccountService;
use EO\Services\LoginService;
use EO\Services\PropertyService;
use EO\Services\TrafficService;
use EO\Services\LeadService;
use EO\View;

/**
 * Class DashboardController
 */
class DashboardController extends \EO\Http\BaseController
{
	protected AccountService $accountService;
	protected LoginService $loginService;
	protected PropertyService $propertyService;
	protected TrafficService $trafficService;
	protected LeadService $leadService;

	function index() 
	{
		$data['topUrls'] = $this->getTotalTrafficsPerUrl();
		$data['totalAccounts'] = $this->getTotalAccounts();
		$total_accounts_per_status = $this->getTotalAccountsPerStatus();

		$data['accountStatusBgColor'] = [
			"Active" => "bg-success",
			"Inactive" => "bg-secondary",
			"Pending Activation" => "bg-warning",
			"Banned" => "bg-danger"
		];

		foreach($total_accounts_per_status as $status => $total) {
			$data["totalAccountsPerStatus"][ucwords(str_replace("_", " ", $status))] = number_format($total, 0);
		}

		return View::set(path: "/authenticated/dashboard/index.php")->bind(data: $data);
	}

	function getTotalTrafficsPerUrl() 
	{
		$request = input()->all();

		if(!Auth::isAdmin()) {
			$request["account_id"] = Auth::user()->id;
		}

		if(isset($request['filter'])) {
			$request['created_at'] = convertToDateFilter($request['filter']);
			unset($request['filter']);
		}else {
			if(!isset($request['created_at']['from']) && !isset($request['created_at']['to'])) {
				$request['created_at'] = convertToDateFilter('last-12-months');
			}
		}

		return (new TrafficService())->totalTrafficsPerUrl($request, 10);
	}

	function getTotalAccounts() 
	{
		$this->accountService = new AccountService();
		return $this->accountService->getTotalAccounts();
	}

	function getTotalAccountsPerStatus()
	{
		$this->accountService = new AccountService();
		$data = $this->accountService->getTotalAccountsPerStatus();

		if(!$data) {
			$data = [ 
				"active" => 0,
				"inactive" => 0,
				"pending_activation" => 0,
				"banned" => 0
			];
		}

		return $data;
	}

}