<?php

namespace EO\Http\Controllers;

use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;
use EO\Handlers\Exceptions\ResourceNotFoundException;
use EO\Handlers\Exceptions\ValidationException;
use EO\Handlers\Exceptions\MailerException;
use EO\Services\AccountService;
use EO\Services\AuthenticationService;
use EO\View;

class RegistrationController extends \EO\Http\BaseController
{
	protected AccountService $accountService;
	protected AuthenticationService $authenticationService;

	public function __construct() 
	{
		$this->accountService = new AccountService();
		$this->authenticationService = new AuthenticationService();
	}

	public function registrationForm() 
	{
		return View::set("/authentication/registration/registrationForm.php");
	}

	public function resendActivationEmailForm() 
	{
		return View::set("/authentication/registration/resendActivationEmailForm.php");
	}

	public function successPage() 
	{
		return View::set("/authentication/registration/successPage.php");
	}

	public function accountActivation() 
	{
		$request = input()->all(['token']);

		try {
			$data = $this->authenticationService->validateToken( $request['token'] );
		} catch (AuthenticationException $e) {
			throw new NotFoundHttpException($e->getMessage());
		}

		$this->accountService->update($data['account_id'], [
			"status" => "active"
		]);

		return View::set("/authentication/registration/accountActivation.php");
	}

	public function checkSingleSignOn() 
	{
		$request = input()->all();

		if(!$this->authenticationService->SSOLogin($data['email'])) {
			return $this->storeUserRegistration();
		}
	}

	public function storeUserRegistration() 
	{
		$registration_data = input()->all();
		$registration_data['registered_at'] = DATE_NOW;

		$this->accountService->validator->resetConstraints()->setConstraints([
			'username' => [
				'length' => ['min' => 4, 'max' => 100],
				'required' => true,
				'username' => true,
				'restrictedWords' => true
			],
			'email' => [
				'required' => true,
				'email' => true
			]
		]);

		try {
			$account_id = $this->accountService->create($registration_data);
		} catch (ValidationException $e) {
			return $this->handleMessageResponse($e->getMessage(), 'error', 2);
		} catch(\Exception $e) {
			return $this->handleMessageResponse($e->getMessage(), 'error', 2);
		}

		try {
			$this->authenticationService->sendActivationEmail([
				"account_id" => $account_id,
				"email" => $registration_data['email']
			]);
		} catch(MailerException $e) {
			$message = "Your registration was successful, however, we were unable to send you an activation email. click <a href='".url('registration.resendActivationEmailForm')."' style='color: #007bff;'>here</a> to resend the activation email.";
			return $this->handleMessageResponse($message, 'info', 2);
		}

		return $this->handleMessageResponse('Successfully registered!');
	}

	public function resendActivationEmail() 
	{
		$request = input()->all();

		try {
			$data = $this->authenticationService->validateEmail($request['email']);
			$this->authenticationService->sendActivationEmail($data);
		} catch (\Exception $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		}

		return $this->handleMessageResponse('Successfully sent activation email. To activate your account, Please check your registered email and click on the activation link.');
	}

}