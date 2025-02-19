<?php

namespace EO\Http\Controllers\Website;

use EO\View;
use EO\Http\BaseController;
use EO\Services\TrafficService;
use EO\Auth\Auth;

class TrafficsController extends BaseController
{
	private TrafficService $trafficService;

	function __construct() 
	{
		$this->trafficService = new TrafficService();
	}

	function saveNew()
	{	
		$request = input()->all();
		$traffics = $this->trafficService->saveToFile($request);
		
	}
}