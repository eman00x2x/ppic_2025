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

class ChartsController
{
	private PropertyService $propertyService;

	function getTotalLoginPerDay($account_id)
	{
		$request = input()->all();
		if(isset($request['filter'])) {
			$request['login_at'] = convertToDateFilter($request['filter']);
			unset($request['filter']);
		}else {
			if(!isset($request['login_at']['from']) && !isset($request['login_at']['to'])) {
				$request['login_at'] = convertToDateFilter('last-12-months');
			}
		}

		if($account_id != "null") {
			$request['account_id'] = $account_id;
		}
		
		$data = (new LoginService())->getTotalLoginPerDay($request);

		if(!$data) {
			$data = [ "" => 0 ];
		}
		
		return View::set("JSON")->bind(data: $data);
	}

	function getTotalPropertiesPerCategory($account_id)
	{
		$request = input()->all();
		if(isset($request['filter'])) {
			$request['created_at'] = convertToDateFilter($request['filter']);
			unset($request['filter']);
		}else {
			if(!isset($request['created_at']['from']) && !isset($request['created_at']['to'])) {
				$request['created_at'] = convertToDateFilter('last-12-months');
			}
		}

		if($account_id != "null") {
			$request['account_id'] = $account_id;
		}

		$data = (new PropertyService())->getTotalPropertiesPerCategory($request);

		if(!$data) {
			$data[] = [ "" => 0 ];
		}

		return View::set("JSON")->bind(data: $data);
	}

	function getTotalPropertiesPerStatus($account_id)
	{
		$request = input()->all();
		if(isset($request['filter'])) {
			$request['created_at'] = convertToDateFilter($request['filter']);
			unset($request['filter']);
		}else {
			if(!isset($request['created_at']['from']) && !isset($request['created_at']['to'])) {
				$request['created_at'] = convertToDateFilter('last-12-months');
			}
		}

		if($account_id != "null") {
			$request['account_id'] = $account_id;
		}

		$data = (new PropertyService())->getTotalPropertiesPerStatus($request);

		if(!$data) {
			$data = [
				"available" => 0,
				"sold" => 0,
				"remove" => 0
			];
		}

		return View::set("JSON")->bind(data: $data);
	}

	function getTotalPropertiesPerListingType($account_id)
	{
		$request = input()->all();
		if(isset($request['filter'])) {
			$request['created_at'] = convertToDateFilter($request['filter']);
			unset($request['filter']);
		}else {
			if(!isset($request['created_at']['from']) && !isset($request['created_at']['to'])) {
				$request['created_at'] = convertToDateFilter('last-12-months');
			}
		}

		if($account_id != "null") {
			$request['account_id'] = $account_id;
		}

		$data = (new PropertyService())->getTotalPropertiesPerListingType($request);

		if(!$data) {
			$data = [ 0 => [
				"listing_type" => "for sale",
				"total" => 0
			]];
		}

		return View::set("JSON")->bind(data: $data);
	}

	function getMonthlyPostings($account_id)
	{
		$request = input()->all();
		if(isset($request['filter'])) {
			$request['created_at'] = convertToDateFilter($request['filter']);
			$request['modified_at'] = convertToDateFilter($request['filter']);
		}else {
			if(!isset($request['created_at']) && !isset($request['modified_at'])) {
				$request['created_at'] = convertToDateFilter('last-12-months');
			}

			if(!isset($request['modified_at'])) {
				$request['modified_at'] = convertToDateFilter('last-12-months');
			}
		}

		if($account_id != "null") {
			$request['account_id'] = $account_id;
		}

		$this->propertyService = new PropertyService();
		$result['created'] = $this->propertyService->getTotalPropertiesPosted($request);

		$result['modified'] = $this->propertyService->getTotalPropertiesUpdated($request);

		foreach($result as $key => $val) {
			if($val) {
				for($i=0; $i<count($val); $i++) {
					$data[ $val[$i]['date'] ][$key] = (int) $val[$i]['total'];
				}
			}else {
				$data[ date("Y-m-d", DATE_NOW) ][$key] = 0;
			}
		}

		ksort($data);
		return View::set("JSON")->bind(data: $data);
	}

	function getTotalTrafficsPerDay($account_id)
	{
		$request = input()->all() ?? [];
		if(isset($request['filter'])) {
			$request['created_at'] = convertToDateFilter($request['filter']);
			unset($request['filter']);
		}else {
			if(!isset($request['created_at']['from']) && !isset($request['created_at']['to'])) {
				$request['created_at'] = convertToDateFilter('last-12-months');
			}
		}

		if($account_id != 'null') {
			$request['account_id'] = $account_id;
		}

		$data = (new TrafficService())->totalTrafficsPerDay($request);

		return View::set("JSON")->bind(data: $data);
	}
	function getTotalLeadsPerDay($account_id)
	{
		$request = input()->all();
		if(isset($request['filter'])) {
			$request['created_at'] = convertToDateFilter($request['filter']);
			unset($request['filter']);
		}else {
			if(!isset($request['created_at']['from']) && !isset($request['created_at']['to'])) {
				$request['created_at'] = convertToDateFilter('last-12-months');
			}
		}

		if($account_id != "null") {
			$request['account_id'] = $account_id;
		}

		$data = (new LeadService())->getTotalLeadsPerDay($request);

		if(!$data) {
			$data = [ 0 => [
				"date" => date("Y-m-d", DATE_NOW),
				"total" => 0
			]];
		}

		return View::set("JSON")->bind(data: $data);
	}
}