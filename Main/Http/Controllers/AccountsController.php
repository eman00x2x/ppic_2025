<?php

namespace EO\Http\Controllers;

use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;
use EO\Handlers\Exceptions\ResourceNotFoundException;
use EO\Handlers\Exceptions\ValidationException;
use EO\Auth\Auth;
use EO\Interfaces\IController;
use EO\Http\BaseController;
use EO\Services\AccountService;
use EO\Services\LoginService;
use EO\View;

class AccountsController extends BaseController implements IController
{
	protected AccountService $accountService;
	protected LoginService $loginService;

	/**
	 * AccountsController constructor.
	 */
	public function __construct() 
	{
		$this->accountService = new AccountService();
		$this->loginService = new LoginService();
	}

	/**
	 * Returns a list of accounts.
	 * 
	 * @return array The list of accounts.
	 */
	public function index() 
	{
		/* $this->authorize("view_accounts", Auth::user()->account); */

		$request = input()->all() ?? [];

		$data['accounts'] = $this->accountService->getAccounts($request);

		return View::set(path: "/authenticated/accounts/index.php")->bind(data: $data);
	}

	public function add() 
	{
		$this->authorize("add_accounts");

		$data = [
			'collection' => [
				'types' => $this->accountService->getAccountTypes(),
				'statuses' => $this->accountService->getStatuses(),
				'permissions' => $this->accountService->getPermissions(),
			],
			'photo' => CDN . "/images/blank-profile.png",
			'default_permission' => $this->accountService->getDefaultPermissions()
		];

		return View::set(path: "/authenticated/accounts/add.php")->bind(data: $data);
	}

	public function edit($id) 
	{
		$account = $this->accountService->getAccount($id);

		$this->authorize("edit_accounts", $account);

		$data = [
			'collection' => [
				'types' => $this->accountService->getAccountTypes(),
				'statuses' => $this->accountService->getStatuses(),
				'permissions' => $this->accountService->getPermissions(),
			],
			'account' => $account,
		];

		return View::set(path: "/authenticated/accounts/edit.php")->bind(data: $data);
	}

	public function view($id) 
	{
		$data = $this->accountService->getAccount($id);

		$this->authorize("view_accounts", $data);

		$data['logins'] = $this->loginService->getLogins(["account_id" => $id, "rows" => 10, "sort" => "login_id|DESC"]);

		return View::set(path: "/authenticated/accounts/view.php")->bind(data: $data);
	}

	function confirmSelection() 
	{
		$request = input()->all();

		$ids = $request['ids'];
		$action = $request['action'];
		$action_value = $request['action_value'];

		$options = [
			"set_status" => [ "url" => url("AccountsController@setAccountsStatus") ],
			"delete" => [ "url" => url("AccountsController@delete") ],
		];

		$account = $this->accountService->getAccounts(["account_id" => $ids]);
		$data = [
			'accounts' => $account,
			'ids' => implode(",", $ids),
			'action' => $action,
			'action_value' => $action_value,
			'url' => $options[$action]['url']
		];

		return View::set(path: "/authenticated/accounts/confirmSelection.php")->bind(data: $data);
	}

	/**
	 * Save a new account record in the database.
	 * 
	 * @return JSON A JSON containing the status and message of the operation.
	 */
	public function saveNew()
	{
		$registration_data = input()->all();
		$registration_data['created_by'] = Auth::user()->account['full_name'];

		try {
			$this->accountService->create($registration_data);
		} catch (ValidationException $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		} catch (\Exception $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		}

		if (isset($registration_data['photo'])) {
			$this->accountService->moveNewPhoto($registration_data['photo']);
		}

		return $this->handleMessageResponse("Successfully created new account!");
	}

	/**
	 * Updates an existing account record in the database.
	 * 
	 * @param int $account_id The ID of the account to update.
	 * @return JSON A JSON containing the status and message of the operation.
	 */
	public function save($account_id)
	{
		$data = input()->all();

		if (isset($data['photo'])) {
			$data['photo'] = $this->accountService->handleFileUpload($data['photo'], $account_id);
		}

		try {
			$this->accountService->update($account_id, $data);
		} catch (ValidationException $e) {
			return $this->handleMessageResponse($e->getMessage(), 'error', 2);
		} catch (\Exception $e) {
			return $this->handleMessageResponse($e->getMessage(), 'error', 2);
		}

		return $this->handleMessageResponse('Successfully updated account!');
	}

	public function setAccountsStatus()
	{
		$request = input()->all();

		$ids = explode(",", $request['ids']);
		$status = $request['action_value'];

		try {
			$this->accountService->updateAccountsStatus($ids, $status);
		} catch (\Exception $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		}

		return $this->handleMessageResponse("Successfully updated accounts status!");
	}

	/**
	 * Deletes an existing account record in the database.
	 *
	 * @param int $id The ID of the account to delete.
	 * @return mixed Returns a JSON containing the status and message of the operation if the deletion is confirmed.
	 *               Otherwise, it renders a delete confirmation page for the account.
	 *               If the account is not found, it returns a 404 response.
	 */
	public function delete($id = null): mixed
	{
		$data = $this->accountService->getAccount($id);

		$this->authorize("delete_accounts", $data);

		if (input()->get('delete')) {
			$this->accountService->destroy($id);
			return $this->handleMessageResponse("Account Deleted Successfully!");
		}

		return View::set("/authenticated/accounts/delete.php")->bind(data: $data);
	}

	function exportAccounts()
	{
		return $this->accountService->downloadData();
	}

	function upload()
	{
		return View::set("JSON")->bind(data: $this->accountService->upload(
			data: $_FILES['browseFile'], 
			params: [
				"destination_folder" => "/Public/global_assets/images/accounts",
				"temp_url" => CDN . "/images/temporary",
				"final_url" => CDN . "/images/accounts",
				"file_type" => "image",
				"file_max_size" => "2MB"
			]
		));
	}

}