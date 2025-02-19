<?php

namespace EO\Http\Controllers;

use EO\Handlers\Exceptions\AuthenticationException as AuthenticationException;
use EO\Auth\Auth as Auth;
use EO\Services\TwoFactorAuthenticationService as TwoFactorAuthenticationService;
use EO\View;

class TwoFactorAuthenticationController extends \EO\Http\BaseController
{
	protected TwoFactorAuthenticationService $twoFactorAuthenticationService;
	private $account;

	function __construct() 
	{
		$this->twoFactorAuthenticationService = new TwoFactorAuthenticationService;
		$this->account = (Auth::user())->account;
	}

	function index() 
	{
		$this->twoFactorAuthenticationService->sendAuthorizationCodeEmail($this->account['email']);
		return View::set(path: "/authentication/login/2-step-verification-code.php")->bind(data: $this->account);
	}

	function verifyAuthorizationCode() 
	{
		$request = input()->all(["authorization_code"]);

		try {
			$this->twoFactorAuthenticationService->validateAuthorizationCode($request["authorization_code"]);
		} catch (\Exception $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		}
	}

	function sendAuthorizationCode()
	{
		try {
			$this->twoFactorAuthenticationService->sendAuthorizationCodeEmail($this->account['email']);
		}catch (\AuthenticationException $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		}

		return $this->handleMessageResponse("Authorization code sent! Please check your registered email.");
	}

} 