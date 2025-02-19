<?php

namespace EO\Http\Controllers;

use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;
use EO\Handlers\Exceptions\ResourceNotFoundException;
use EO\Handlers\Exceptions\ValidationException;
use EO\Auth\Auth;
use EO\Interfaces\IController;
use EO\Services\LoginService;
use EO\View;

class LoginController extends \EO\Http\BaseController implements IController
{
	protected LoginService $loginService;

	public function __construct() 
	{
		$this->loginService = new LoginService();
	}

	public function index() {}
	public function add() {}
	public function edit($id) {}

	public function confirmSelection()
	{
		$request = input()->all();

		$login_ids = $request['ids'];
		$login_ids_count = count($login_ids);
		$action = $request['action'];
		$action_value = $request['action_value'];

		$options = [
			'delete' => [
				'url' => url('LoginController@delete'),
				'message' => "You are about to Delete (Permanent) {$login_ids_count} login(s). All data related to this login(s) will be permanently deleted and this action is ireversible, Are you sure do you want to continue the deletion of these login(s)?",
			],
		];

		$logins = $this->loginService->getLogins(['login_id' => $login_ids]);

		$data = [
			'logins' => $logins,
			'ids' => implode(',', $login_ids),
			'action' => $action,
			'action_value' => $action_value,
			'url' => $options[$action]['url'],
			'message' => $options[$action]['message'],
		];

		return View::set(path: '/logins/confirmSelection.php')->bind(data: $data);
	}

	public function saveNew() {}
	public function save($id) {}

	public function delete($id = null) 
	{
		$request = input()->all();
		$login_ids = explode(",", $request['ids']);

		try {
			$deletedLogins = $this->loginService->destroy($login_ids);
		} catch (\Exception $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		}

		return $this->handleMessageResponse('Logins permanently deleted successfully');
	}

}