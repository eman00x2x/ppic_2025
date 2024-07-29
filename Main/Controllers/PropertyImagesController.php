<?php

namespace Main\Controllers;

use Main\Interfaces\IController as IController;
use Main\Services\PropertyImageService as PropertyImageService;

class PropertyImagesController extends \Main\Controller
{

	protected PropertyImageService $PropertyImageService;

	function __construct() {
		parent::__construct();
		$this->PropertyImageService = new PropertyImageService();
	}

	/**
	 * Save new organization record in the database
	 * 
	 * @return JSON A JSON containing the status and message of the operation
	 */
	function saveNew() {

		$this->setResponseType("JSON");

		// Collects all input data from the request and sets the current date and time as the registration date.
		$request = input()->all();
		$request["created_at"] = DATE_NOW;

		$validation = $this->validateInput($request, [
			"title" => [
				"length" => [ "min" => 4, "max" => 100 ],
				"required" => true,
				"restrictedWords" => true
			],
			"offer" => [
				"required" => true
			],
			"price" => [
				"required" => true
			]
		]);

		if($validation['status'] == 2) {
			$this->getLibrary("Factory")->setMsg($validation['message'], "error");
			return json_encode([
				"status" => 2,
				"message" => $this->helper(function: "get_message")
			]);
		}

		$response = $this->PropertyService->create($validation['validated']);

		$this->getLibrary("Factory")->setMsg($response['message'], $response['type']);

		return json_encode([
			"status" => $response['status'],
			"message" => $this->helper(function: "get_message")
		]);

	}

	/**
	 * Deletes an account
	 * @param int $id The ID of the account to delete
	 * @return JSON A JSON containing the status and message of the operation
	 */
	function delete($id) {

		if($this->AuthService->userHasPermission('delete_content') === false) {
			$this->response(403);
		}

		// ensure that the ID is a valid integer
		if(!is_numeric($id)) {
			$this->getLibrary("Factory")->setMsg("Invalid account!", "error");
			$this->setResponseType("JSON");

			$response = [
				"status" => 2,
				"message" => $this->helper(function: "get_message")
			];

			return $this->render(data: $response);
		}

		$request = input()->all();

		if(isset($request['delete'])) {

			$result = $this->PropertyService->destroy($id);
			
			$this->getLibrary("Factory")->setMsg($result['message'], $result['type']);
			
			$this->setResponseType("JSON");
			// return the response
			return $this->render(data: [
				"status" => $result['status'],
				"message" => $this->helper(function: "get_message")
			]);
			
		}

		$property = $this->PropertyService->get($id);

		if(isset($property->column['property_id'])) {
			$this->setTemplate("/admin/properties/delete.php");
			// get the template and pass the data
			return $this->render(data: $property->column, model: $property);
		}

		$this->setResponseType("JSON");
		return $this->render(data: $property);

	}

	function upload() {

        $response = $this->uploadFile($_FILES['browseFile'], [
			"destination_folder" => "/Cdn/images/properties",
			"temp_url" => CDN."images/temporary",
			"final_url" => CDN."images/properties",
            "file_type" => "image",
            "file_max_size" => "2MB"
		]);

		$this->setResponseType("JSON");
		return $this->render(data: $response);

    }


}