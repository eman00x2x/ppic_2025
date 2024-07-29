<?php

namespace Main\Controllers;

use Main\Interfaces\IController as IController;
use Main\Services\AccountService as AccountService;
use Main\Services\AuthorizationService as AuthorizationService;

class AccountsController extends \Main\Controller implements IController
{
	
	protected AccountService $AccountService;

	/**
     * AccountsController constructor.
     */
	function __construct() {
		parent::__construct();		
		// initialize the account model
		$this->AccountService = new AccountService();
	}

	/**
	 * Returns a list of accounts
	 * 
	 * @return array The list of accounts
	 */
	function index() {

		$this->document->setTitle("Accounts");

		if($this->AuthService->userHasPermission('manage_users') === false) {
			$this->response(403);
		}

		// Check if there are any request parameters
		$request = input()->all() ?? false;

		if($this->AuthService->userHasRole("Organization")) {
			$request["AND"]["organization_id"] = $this->AuthService->user['organization_id'];
		}

		if(isset($request['search'])) {
			$request["OR"] = [
				"username[~]" => $request['search'],
				"email[~]" => $request['search']
			];
		}

		$account = $this->AccountService->list(request: $request, target_url: url("AccountsController@index"));

		if($account->results) {
			for($i = 0; $i < count($account->results); $i++) {
				foreach($account->results[$i] as $key => $val) {
					if($key == "registered_at") {
						$account->results[$i]['registered_at'] = date("d M Y", $val);
					}
				}
			}
		}

		// set the template
		$this->setTemplate("/admin/accounts/index.php");
		return $this->render(data: $account->results, model: $account);

	}

	function add() {

		if($this->AuthService->userHasPermission('manage_users') === false) {
			$this->response(403);
		}

		$data['account_types'] = $this->AccountService->account->types;
		$data['statuses'] = $this->AccountService->account->statuses;

		// set the template
		$this->setTemplate("/admin/accounts/add.php");
		return $this->render($data);

	}

	/**
	 * Returns the data for an account based on its ID
	 * 
	 * @param int $id The ID of the account to retrieve
	 * @return array The account data, or false if the account could not be found
	 */
	function edit($id) {

		if($this->AuthService->userHasPermission('edit_content') === false) {
			$this->response(403);
		}

		// ensure that the ID is a valid integer
		if(!is_numeric($id)) {
			// invalid data type, redirect to 404 page
			$this->response(404);
		}

		if($this->AuthService->userHasRole("Organization")) {
			$this->AccountService->account->where([
				"organization_id" => $this->AuthService->user['organization_id']
			]);
		}

		// retrieve the account data
		$account = $this->AccountService->get($id);

		if(isset($account->column['account_id'])) {

			$data = $account->column;
			$data['account_types'] = $this->AccountService->account->types;
			$data['statuses'] = $this->AccountService->account->statuses;

			$this->setTemplate("/admin/accounts/edit.php");
			// get the template and pass the data
			return $this->render(data: $data, model: $account);
		}
		
		// account not found, redirect to 404 page
		$this->response(404);

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

		$validation = $this->validateInput($request, [
			"username" => [
				"length" => [ "min" => 4, "max" => 100 ],
				"required" => true,
				"username" => true,
				"restrictedWords" => true
			],
			"password" => [
				"length" => [ "min" => 6 ],
				"required" => true
			],
			"confirm_password" => [
				"required" => true,
				"confirmPassword" => $request['password']
			],
			"email" => [
				"length" => [ "max" => 255 ],
				"required" => false,
				"email" => true
			]
		]);

		if($validation['status'] == 2) {
			$this->getLibrary("Factory")->setMsg($validation['message'], "error");
			
			return $this->render(data: [
				"status" => 2,
				"message" => $this->helper(function: "get_message")
			]);
		}

		$response = $this->AccountService->create($validation['validated']);

		$this->getLibrary("Factory")->setMsg($response['message'], $response['type']);

		return $this->render(data: [
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

		if(isset($request['password']) && $request['password'] != "") {
			$validation = $this->validateInput($request, [
				"password" => [
					"length" => [ "min" => 6 ],
					"required" => true
				],
				"confirm_password" => [
					"required" => true,
					"confirmPassword" => $request['password']
				]
			]);

			if($validation['status'] == 2) {
				$this->getLibrary("Factory")->setMsg($validation['message'], "error");
				return json_encode([
					"status" => 2,
					"message" => $this->helper(function: "get_message")
				]);
			}

			$request = $validation['validated'];

		}else { unset($request['password'], $request['confirm_password']); }

		$response = $this->AccountService->update($id, $request);

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
	 * Deletes an account
	 * @param int $id The ID of the account to delete
	 * @return JSON A JSON containing the status and message of the operation
	 */
	function delete($id) {

		if($this->AuthService->userHasPermission('delete_content') === false) {
			$this->response(403);
		}

		if($this->AuthService->userHasRole("Organization")) {
			$this->AccountService->account->where([
				"organization_id" => $this->AuthService->user['organization_id']
			]);
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

			$this->removeFile(basename( $data['profile_image'] ), $path = "/images/profiles");

			$result = $this->AccountService->destroy($id);
			
			$this->getLibrary("Factory")->setMsg($result['message'], $result['type']);
			
			$this->setResponseType("JSON");
			// return the response
			return $this->render(data: [
				"status" => $result['status'],
				"message" => $this->helper(function: "get_message")
			]);
			
		}

		$account = $this->AccountService->get($id);

		if(isset($account->column['account_id'])) {
			$this->setTemplate("/admin/accounts/delete.php");
			// get the template and pass the data
			return $this->render(data: $account->column, model: $account);
		}

		$this->setResponseType("JSON");
		return $this->render(data: $account);

	}

}