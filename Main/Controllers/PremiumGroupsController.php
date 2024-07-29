<?php

namespace Main\Controllers;

use Main\Interfaces\IController as IController;
use Main\Services\PremiumGroupService as PremiumGroupService;

class PremiumGroupsController extends \Main\Controller
{

	protected PremiumGroupService $PremiumGroupService;

	function __construct() {
		parent::__construct();
		$this->PremiumGroupService = new PremiumGroupService();
	}

	function index() {

		$this->document->setTitle("Premium Groups");

		if($this->AuthService->userHasPermission('manage_products') === false) {
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

		$premiumGroups = $this->PremiumGroupService->list(request: $request, target_url: url("PremiumGroupsController@index"));

		if($premiumGroups->results) {

			for($i = 0; $i < count($premiumGroups->results); $i++) {
				foreach($premiumGroups->results[$i] as $key => $val) {

					if($key == "created_at") {
						$premiumGroups->results[$i]['created_at'] = date("d M Y", $premiumGroups->results[$i]['created_at']);
					}else if($key == "description") {
						$premiumGroups->results[$i]['description'] = $this->helper("nice_trim", [
							"string" => $premiumGroups->results[$i]['description'],
							"max_length" => 80
						]);
					}
				}
			}

		}

		$this->setTemplate("/admin/premium_groups/index.php");
		return $this->render(data: $premiumGroups->results, model: $premiumGroups);

	}

	function add() {

		if($this->AuthService->userHasPermission('edit_content') === false) {
			$this->response(403);
		}

		$this->document->setTitle("New Premium Group");
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
		$this->setTemplate("/admin/premium_groups/add.php");
		return $this->render();
	}

	function edit($id) {

		if($this->AuthService->userHasPermission('edit_content') === false) {
			$this->response(403);
		}

		$this->document->setTitle("Edit Premium Group");
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

		$premiumGroups = $this->PremiumGroupService->get($id);

		if(isset($premiumGroups->column['premium_group_id'])) {
			$this->setTemplate("/admin/premium_groups/edit.php");
			// get the template and pass the data
			return $this->render(data: $premiumGroups->column, model: $premiumGroups);
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

		$response = $this->PremiumGroupService->create($validation['validated']);

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

		$response = $this->PremiumGroupService->update($id, $request);
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

			$result = $this->PremiumGroupService->destroy($id);
			
			$this->getLibrary("Factory")->setMsg($result['message'], $result['type']);
			
			$this->setResponseType("JSON");
			// return the response
			return $this->render(data: [
				"status" => $result['status'],
				"message" => $this->helper(function: "get_message")
			]);
			
		}

		$premiumGroups = $this->PremiumGroupService->get($id);

		if(isset($premiumGroups->column['premium_group_id'])) {
			$this->setTemplate("/admin/premium_groups/delete.php");
			// get the template and pass the data
			return $this->render(data: $premiumGroups->column, model: $premiumGroups);
		}

		$this->setResponseType("JSON");
		return $this->render(data: $premiumGroups);

	}

}