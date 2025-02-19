<?php

namespace EO\Auth;

use Josantonius\Session\Session;
use EO\Services\AccountService as AccountService;
use EO\Services\LoginService as LoginService;

class SessionGuardian
{
	private $lifetime = "+30 minutes";
   
	public Session $session;
	protected LoginService $loginService;
	protected AccountService $accountService;

	protected $user;

	public function __construct()
	{
		$this->session =  new Session;
		$this->loginService = new LoginService;
		$this->accountService = new AccountService;

		if(!$this->session->isStarted()) {
			$this->session->start();
		}

		if(!$this->session->has("id")) {
			$this->attributes();
		}

		if(time() >= $this->session->get("end")) {
			$this->renew();
		}
	}

	public function user(): mixed
	{
		if ($this->user !== null) {
			return $this->user;
		}

		$authenticated = $this->session->get("account");

		if (isset($authenticated['session_id']) && !is_null($authenticated['session_id'])) {
     		$account = $this->loginService->getBySessionId(session_id: $authenticated['session_id']);
			$this->session->replace(["account" => $account]);
			$this->user = (object) $account;
		}

		return $this->user;
	}

	/**
	 * Attempt to authenticate a user using the given credentials.
	 *
	 * @param  array  $credentials
	 * @return bool
	 */
	public function attempt(array $credentials = []): bool
	{
		if ($results = $this->accountService->validateCredentials($credentials)) {
			$results['session_id'] = $this->session->getId();
			$results['user_agent'] = $credentials['user_agent'];
			$this->login($results);
			return true;
		}

		return false;
	}

	public function attemptSSO(string $email): bool
	{
		if ($results = $this->accountService->getEmail($email)) {
			$results['session_id'] = $this->session->getId();
			$results['user_agent'] = $credentials['user_agent'];
			$this->login($results);
			return true;
		}

		return false;
	}

	public function login(array $user): void
	{
		$this->session->set("account", $user);
		$this->user = (object) $user;
	}

	public function logout()
	{
		$authenticated = $this->session->get("account");

		if(isset($authenticated['session_id'])) {
			$this->loginService->update([ "status" => 0 ], $authenticated['login_id']);
			$this->session->remove("account");
			$this->session->remove("two_factor_auth");

        	$this->session->regenerateId();
		    /* $this->session->clear();
		    $this->session->destroy(); */
		}
		
		$this->user = null;
	}

	public function forceLogout()
	{
		$authenticated = $this->session->get("account");

		if(isset($authenticated['account_id'])) {
			$this->loginService->updateBy(
				conditions: [
					"account_id" => $authenticated['account_id']
				], 
				data: [
					"status" => 0
				]
			);

			$this->session->remove("account");
			$this->session->remove("two_factor_auth");
			$this->session->regenerateId();
			/* $this->session->clear();
			$this->session->destroy(); */
		}

		$this->user = null;
	}

	public function check() 
	{
		return ($this->user() !== null || $this->user() != "");
	}

	public function renew() 
	{
		$this->session->regenerateId();
		$this->attributes();
	}

	public function attributes()
	{
		$timestamp = time();

		$this->session->replace([
			"id" => $this->session->getId(),
			"started" => $timestamp,
			"end" => strtotime($this->lifetime, $timestamp)
		]);
	}

	/**
	 * Checks if the current user is an administrator.
	 *
	 * @return bool Returns true if the user is an administrator, false otherwise.
	 */
	function isAdmin()
	{
		$authenticated = $this->session->get("account");
		
		if(isset($authenticated['account']['account_type']) && $authenticated['account']['account_type'] == "Administrator") {
			return true;
		}

		return false;
	}

	private function setDomain($domain)
	{
		$this->session->set("domain", $domain);
	}

	public function getDomain()
	{
		return $this->session->get("domain");
	}

}