<?php

namespace EO\Http\Controllers\Website;

use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;
use EO\View;
use EO\Http\BaseController;
use EO\Services\AccountService;

class AccountsController extends BaseController
{
	protected AccountService $accountService;
	
	function __construct()
	{
		View::setTemplateBasePath( ROOT . "/Resources/Templates");
		
		$this->accountService = new AccountService();
	}

	function index()
	{
		$request = input()->all() ?? [];

		$request['AND'] = [
			'status' => "active",
			'account_id[!=]' => 22
		];
		$data['accounts'] = $this->accountService->getAccounts($request);
		return View::set(path: "/website/accounts/accounts.php")->bind(data: $data);
	}
	
	function viewAccount($name, $id)
	{
		$data = $this->accountService->getAccount($id);

		if($data['name'] !== $name) {
			throw new NotFoundHttpException("Resource Not Found!");
		}
		return View::set("/website/properties/property.php")->bind(data: $data);
	}

}