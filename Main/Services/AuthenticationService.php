<?php

namespace EO\Services;

use EO\Handlers\Exceptions\AuthenticationException;
use EO\Handlers\Exceptions\MailerException;
use EO\Facades\EventFacade as Event;
use EO\Service;
use EO\Auth\Auth;
use EO\Database\Collections;
use EO\Model\AccountModel as Account;
use EO\Services\LoginService as LoginService;

class AuthenticationService extends Service 
{
	private LoginService $loginService;

	function __construct() 
	{
		parent::__construct();
		$this->loginService = new LoginService;
	}

	public function login(array $credentials)
	{
		if (Auth::check()) {
			return Auth::user();
		}

		if (Auth::attempt($credentials)) {
			$authenticated_user = Auth::user();

			if ($account_status_message = $this->verifyStatus($authenticated_user->status)) {
				$this->log([
					'type' => 'warning',
					'message' => $account_status_message, 
					'data' => (array) $authenticated_user
				]);
				throw new AuthenticationException($account_status_message);
			}

			/* if ($this->loginService->getDualLoginsCount($authenticated_user->account_id) > 0) {
				Auth::forceLogout();
				throw new AuthenticationException('Someone is already using this account. You have been logged out in all devices for your account security.');
			} */

			$this->loginService->create([
				'account_id' => $authenticated_user->account_id,
				'session_id' => $authenticated_user->session_id,
				'user_agent' => $authenticated_user->user_agent
			]);

			$this->log([
				'type' => 'info',
				'message' => 'Login attempt succeded',
				'data' =>(array) $authenticated_user
			]);

			return $authenticated_user;
		}

		$this->log([
			'type' => 'info',
			'message' => 'Login attempt failed',
			'data' => (array) $credentials
		]);
		throw new AuthenticationException('Invalid credentials.');
	}

	public function SSOLogin($email) 
	{
		if (Auth::check()) {
			return Auth::user();
		}

		if (Auth::attemptSSO($email)) {
			$authenticated_user = Auth::user();

			if ($account_status_message = $this->verifyStatus($authenticated_user->status)) {
				$this->log([
					'type' => 'warning',
					'message' => $account_status_message,
					'data' => (array) $authenticated_user
				]);
				throw new AuthenticationException($account_status_message);
			}

			if ($this->loginService->getDualLoginsCount($authenticated_user->account_id) > 0) {
				Auth::forceLogout();
				throw new AuthenticationException('Someone is already using this account. You have been logged out in all devices for your account security.');
			}

			$this->loginService->create([
				'account_id' => $authenticated_user->account_id,
				'session_id' => $authenticated_user->session_id,
				'user_agent' => $authenticated_user->user_agent
			]);

			$this->log([
				'type' => 'info',
				'message' => 'Login attempt successful', 
				'data' => (array) $authenticated_user
			]);
			return $authenticated_user;
		}

		$this->log([
			'type' => 'info',
			'message' => 'Login attempt failed',
			'data' => (array) $email
		]);
		return false;
	}
	
	public function sendPasswordResetEmail(array $data): void
	{
		$token_expiration = '+24 hours';
		$token_data = [
			'account_id' => $data['account_id'],
			'email' => $data['email']
		];

		$token = $this->generateToken($token_data, $token_expiration);
		$url = DOMAIN . url('resetPassword', null, ['token' => $token]);
		$web_url_token = $this->generateToken(['username' => $data['username'], 'url' => $url]);

		$eventData = [
			'email' => $data['email'],
			'data' => [
				'username' => $data['username'],
				'url' => $url,
				'web_url' => DOMAIN . '/mail/PasswordResetEmail/' . $web_url_token
			]
		];

		Event::dispatch('account.password.reset.request', $eventData);
	}

	public function sendActivationEmail(array $data): void
	{
		$token_expiration = '+7 days';
		$token_data = [
			'account_id' => $data['account_id'],
			'email' => $data['email'],
		];

		$token = $this->generateToken($token_data, $token_expiration);
		$url = DOMAIN . url("/registration/accountActivation", null, ['token' => $token]);
		$web_url_token = $this->generateToken(['url' => $url]);

		$eventData = [
			'email' => $data['email'],
			'data' => [
				'url' => $url,
				'web_url' => DOMAIN . '/mail/ActivationEmail/' . $web_url_token,
			],
		];

		Event::dispatch('account.registered', $eventData);
	}

	public function verifyStatus($account_status) 
	{
		$status_messages = [
			"pending_activation" => "Account pending activation. Please check your email and activate your account.",
			"banned" => "Account is banned.",
			"inactive" => "Account is inactive.",
			"expired_subscription" => "User has been deactivated due to expired subscription."
		];

		return $status_messages[$account_status] ?? false;
	}
	
	public function validateEmail(string $email): array 
	{
		self::$collections = Account::where(['email' => $email])->get();
		$items = self::$collections->getItems();

		if($items->isNotEmpty()) {
			return $items->first()->toArray();
		}

		throw new AuthenticationException('Invalid email!');
	}

	public function validateToken(string $token): array 
	{
		if(($decoded_token = $this->decodeToken($token)) === false) {
			$this->log([
				'type' => 'warning',
				'message' => 'Token validation failed',
				'data' => $decoded_token
			]);
			throw new AuthenticationException('Invalid token!');
		}

		if(!isset($decoded_token['email'])) {
			$this->log([
				'type' => 'warning',
				'message' => 'Token validation failed',
				'data' => $decoded_token
			]);
			throw new AuthenticationException('Invalid token!');
		}

		if ($decoded_token['expiration'] < time()) {
			$this->log([
				'type' => 'warning',
				'message' => 'Expired token!',
				'data' => $decoded_token
			]);
			throw new AuthenticationException('Expired token!');
		}

		return $this->validateEmail($decoded_token['email']);
	}

	public function decodeToken(string $token): array 
	{
		if (!ctype_xdigit($token) || strlen($token) % 2 !== 0) {
			return false;
		}

		$binary_content = hex2bin($token);

		if (base64_encode(base64_decode($binary_content, true)) !== $binary_content) {
			return false;
		}

		$decoded_content = base64_decode($binary_content);

		if (json_decode($decoded_content, true) === null) {
			return false;
		}

		return json_decode($decoded_content, true);
	}

	private function generateToken(array $data, $duration = "+7 days"): string 
	{
		$expiration_date_time = (new \DateTime())->modify($duration);
		$data['expiration'] = $expiration_date_time->getTimestamp();

		return bin2hex(base64_encode(json_encode($data)));
	}

}