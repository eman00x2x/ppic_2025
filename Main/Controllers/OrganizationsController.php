<?php

namespace Main\Controllers;

use Main\Interfaces\IController as IController;
use Main\Services\OrganizationService as OrganizationService;

class OrganizationsController extends \Main\Controller
{

	protected OrganizationService $OrganizationService;

	function __construct() {
		parent::__construct();
		$this->OrganizationService = new OrganizationService();
	}

	function index() {

		$this->document->setTitle("Organizations");

		if($this->AuthService->userHasPermission('manage_users') === false) {
			$this->response(403);
		}

		// Check if there are any request parameters
		$request = input()->all() ?? false;

		if(isset($request['search'])) {
			$request["OR"] = [
				"name[~]" => $request['search'],
				"description[~]" => $request['search']
			];
		}

		$this->document->setTitle("Organizations");

		$organization = $this->OrganizationService->list(request: $request, target_url: url("OrganizationsController@index"));

		if($organization->results) {

			for($i = 0; $i < count($organization->results); $i++) {
				foreach($organization->results[$i] as $key => $val) {
					if($key == "created_at") {
						$organization->results[$i]['created_at'] = date("d M Y", $organization->results[$i]['created_at']);
					}
				}
			}

			$this->setTemplate("/admin/organizations/index.php");
			return $this->render(data: $organization->results, model: $organization);

		}

		// redirect to 404 page
		$this->response(404);
		
	}

	function add() {

		if($this->AuthService->userHasPermission('manage_users') === false) {
			$this->response(403);
		}

		$this->document->setTitle("New Organization");
		$this->document->addScriptDeclaration('

			function validateInput(input) {
				let message = [];

				const data = input.reduce(function (obj, item) {
					obj[item.name] = item.value;
					return obj;
				}, {});

				const validator = validate(
					{
						name: data.name
					},
					{
						name: {
							presence: { allowEmpty: false }
						}
					}
				);

				if (validator !== undefined) {
					for (key in validator) {
						message.push(validator[key]);
					}
					return message.join(", ");
				}

				return false;
			}

		');

		// get the template
		$this->setTemplate("/admin/organizations/add.php");
		return $this->render();
	}

	function edit($id) {

		if($this->AuthService->userHasPermission('edit_content') === false) {
			$this->response(403);
		}

		$this->document->setTitle("Edit Organization");
		$this->document->addScriptDeclaration('

			function validateInput(input) {
				let message = [];

				const data = input.reduce(function (obj, item) {
					obj[item.name] = item.value;
					return obj;
				}, {});

				const validator = validate(
					{
						name: data.name
					},
					{
						name: {
							presence: { allowEmpty: false }
						}
					}
				);

				if (validator !== undefined) {
					for (key in validator) {
						message.push(validator[key]);
					}
					return message.join(", ");
				}

				return false;
			}

		');

		$this->setTemplate("/admin/organizations/edit.php");
		$organization = $this->OrganizationService->get($id);

		if(isset($organization->column['organization_id'])) {
			// get the template and pass the data
			return $this->render(data: $organization->column, model: $organization);
		}
		
		// Organization not found, redirect to 404 page
		$this->response(404);
		
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
			"name" => [
				"length" => [ "min" => 4, "max" => 100 ],
				"required" => true,
				"restrictedWords" => true
			]
		]);

		if($validation['status'] == 2) {
			$this->getLibrary("Factory")->setMsg($validation['message'], "error");
			return json_encode([
				"status" => 2,
				"message" => $this->helper(function: "get_message")
			]);
		}

		$response = $this->OrganizationService->create($validation['validated']);

		$this->getLibrary("Factory")->setMsg($response['message'], $response['type']);

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

		$response = $this->OrganizationService->update($id, $request);
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

			$result = $this->OrganizationService->destroy($id);
			
			$this->getLibrary("Factory")->setMsg($result['message'], $result['type']);
			
			$this->setResponseType("JSON");
			// return the response
			return $this->render(data: [
				"status" => $result['status'],
				"message" => $this->helper(function: "get_message")
			]);
			
		}

		$organization = $this->OrganizationService->get($id);

		if(isset($organization->column['organization_id'])) {
			$this->setTemplate("/admin/organizations/delete.php");
			// get the template and pass the data
			return $this->render(data: $organization->column, model: $organization);
		}

		$this->setResponseType("JSON");
		return $this->render(data: $organization);

	}

}