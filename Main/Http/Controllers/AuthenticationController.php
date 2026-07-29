<?php

namespace EO\Http\Controllers;

use EO\Handlers\Exceptions\AuthenticationException as AuthenticationException;
use EO\Auth\Auth as Auth;
use EO\Auth\SessionGuardian as SessionGuardian;
use EO\Services\AuthenticationService as AuthenticationService;
use EO\Services\AccountService as AccountService;
use EO\View;

class AuthenticationController extends \EO\Http\BaseController
{
	protected AuthenticationService $authenticationService;

	function __construct() 
	{
		$this->authenticationService = new AuthenticationService();
	}

  private function verifyUser()
  {
    $user = Auth::check();

		if($user !== null && $user != "") {
			redirect(url('dashboard'));
		}
  }

	function getLoginForm() 
	{
		$this->verifyUser();

		$data['redirect_url'] = url('dashboard');
		
		return View::set(path: "/authentication/login/login.php")->bind(data: $data);
	}

	function getLoginFormAdmin() 
	{
		$this->verifyUser();

		$data['redirect_url'] =  url('administration');
		
		return View::set(path: "/authentication/login/admin.login.php")->bind(data: $data);
	}

	function getRequestPasswordResetForm() 
	{
		return View::set(path: "/authentication/login/getRequestPasswordResetForm.php");
	}

	function getResetPasswordForm() 
	{
		$request = input()->all(["token"]) ?? null;

		if (!$request['token']) {
			return $this->handleMessageResponse('Invalid token!', 'error', 2);
		}

		try {
			$data = $this->authenticationService->validateToken( $request['token'] );
			$data['expired'] = false;
		} catch (\Exception $e) {
			$data['expired'] = true;
		}

		return View::set(path: "/authentication/login/resetPassword.php")->bind(data: $data);
	}

	function passwordResetSuccess() 
	{
		return View::set(path: "/authentication/login/passwordResetSuccess.php");
	}

	function getTwoStepVerificationCodeForm() 
	{
		return View::set(path: "/authentication/login/2-step-verification-code.php");
	}

	function doLogin() 
	{
		$request = input()->all(["username", "password", "user_agent"]);
		
		try {
			$authenticated = (array) $this->authenticationService->login($request);
		} catch (\Exception $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		}

		return $this->handleMessageResponse("Successfully logged in!");
	}

	function sendPasswordResetLink() 
	{
		$request = input()->all(["email"]);

		try {
			$data = $this->authenticationService->validateEmail($request['email']);
		} catch (\Exception $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		}
		
		try {
			$this->authenticationService->sendPasswordResetEmail($data);
		} catch (\MailerException $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		}

		return $this->handleMessageResponse("Successfully sent password reset link");
	}

	function saveNewPassword() 
	{
		$request = input()->all(['account_id', 'password', 'confirmPassword']);
		$account_service = new AccountService();

		try {
			$account_service->setResetPasswordValidationConstraints($request);
			$validated_data = $account_service->validateInput($request);
		} catch (\Exception $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		}

		$account_service->update($id, $validated_data);
		return $this->handleMessageResponse("Password successfully updated!");
	}

} 