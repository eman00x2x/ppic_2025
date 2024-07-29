<?php

namespace Main\Controllers;

use Main\Interfaces\IController as IController;
use Main\Services\ProfileService as ProfileService;

class ProfilesController extends \Main\Controller implements IController
{
	protected ProfileService $ProfileService;

	/**
     * Admin\Application\Controller\ProfilesController constructor.
     */
	function __construct() {
		parent::__construct();
		$this->ProfileService = new ProfileService();
	}

	function index() {}
	function add() {}

	function edit($id) {

		if($this->AuthService->userHasPermission('edit_content') === false) {
			$this->response(403);
		}

		if($this->AuthService->userHasRole("Organization")) {
			$this->AccountService->account->where([
				"organization_id" => $this->AuthService->user['organization_id']
			]);
		}

		$profile = $this->ProfileService->get($id);

		$this->setTemplate("/admin/profile/edit.php");

		// get the template and pass the data
		return $this->render(data: $profile->column, model: $profile);
	}

	/**
	 * Save new account record in the database
	 * 
	 * @return JSON A JSON containing the status and message of the operation
	 */
	function saveNew() {

		$this->setResponseType("JSON");

		// Collects all input data from the request and sets the current date and time as the registration date.
		$request = input()->all();
		$request["registered_at"] = DATE_NOW;

		$request = $this->validateInput($request, [
			"firstname" => [
				"length" => [ "min" => 2, "max" => 100 ],
				"required" => true,
				"restrictedWords" => true
			],
			"birthdate" => [ 
				"required" => true,
				"date" => true
			]
		]);

		if($request['status'] == 2) {
			$this->getLibrary("Factory")->setMsg($request['message'], "error");
			return json_encode([
				"status" => 2,
				"message" => $this->helper(function: "get_message")
			]);
		}

		$response = $this->ProfileService->create($request['validated']);

		$status = "success";
		if($response['status'] == 2) {
			$status = "error";
		}

		$this->getLibrary("Factory")->setMsg($response['message'], $status);

		return json_encode([
			"status" => $response['status'],
			"message" => $this->helper(function: "get_message")
		]);

	}

	/**
	 * Updates an existing account record in the database
	 * @param int $id The ID of the account to update
	 * @return JSON A JSON containing the status and message of the operation
	 */
	function save($id) {

		$request = input()->all();

		$request = $this->validateInput($request, [
			"firstname" => [
				"length" => [ "min" => 2, "max" => 100 ],
				"required" => true,
				"restrictedWords" => true
			],
			"birthdate" => [ 
				"required" => true,
				"date" => true
			]
		]);

		if($request['status'] == 2) {
			$this->getLibrary("Factory")->setMsg($request['message'], "error");
			return json_encode([
				"status" => 2,
				"message" => $this->helper(function: "get_message")
			]);
		}

		$response = $this->ProfileService->update($id, $request['validated']);

		if($response['status'] == 2) {

			$this->getLibrary("Factory")->setMsg($response['message'], "error");

		}else {
			
			if(isset($request['profile_image']) && $response['profile_image'] != $request['profile_image']) {
				$this->moveFile(basename($request['profile_image']), "/images/temporary", "/images/profiles");
			}

			$this->getLibrary("Factory")->setMsg($response['message'], "success");

		}

		return json_encode([
			"status" => $response['status'],
			"message" => $this->helper(function: "get_message")
		]);
		
	}

	function delete($id) {}

	function upload() {

        $response = $this->uploadFile($_FILES['browseFile'], [
			"destination_folder" => "/Cdn/images/profiles",
			"temp_url" => CDN."images/temporary",
			"final_url" => CDN."images/profiles",
            "file_type" => "image",
            "file_max_size" => "2MB"
		]);

		$this->setResponseType("JSON");
		return $this->render(data: $response);

    }

}