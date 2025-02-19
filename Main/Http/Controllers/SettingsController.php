<?php

namespace EO\Http\Controllers;

use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;
use EO\Handlers\Exceptions\ValidationException;
use EO\Handlers\Exceptions\ResourceNotFoundException;
use EO\Interfaces\IController;
use EO\Services\SettingsService as SettingsService;
use EO\Auth\Auth;

class SettingsController extends \EO\Http\BaseController
{
	protected SettingsService $settingsService;

	function __construct() 
	{
		$this->settingsService = new SettingsService();

		$this->authorize("manage_settings", Auth::user()->account);
	}

	function index() 
	{
		$this->authorize("update_system_settings", Auth::user()->account);

		try {
			$data = $this->settingsService->getSettings();

			return \EO\View::set(path: "/authenticated/settings/settings.php")->bind(data: $data);
		} catch (ResourceNotFoundException $e) {
			$this->response(404);
		}
	}

	function webSettings() 
	{
		$this->authorize("update_web_settings", Auth::user()->account);

		try {
			$data = $this->settingsService->getSettings();
			return \EO\View::set(path: "/authenticated/settings/webSettings.php")->bind(data: $data);
		} catch (ResourceNotFoundException $e) {
			$this->response(404);
		}

	}

	function save() 
	{
		$request = input()->all();
		
		try{
			$this->settingsService->update($request);
		}catch(\Exception $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		}
			
		return $this->handleMessageResponse("Settings updated successfully");
	}

}