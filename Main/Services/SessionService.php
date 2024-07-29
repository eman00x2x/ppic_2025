<?php

namespace Main\Services;

use Josantonius\Session\Session;
use Main\Model\AccountLoginModel as AccountLogin;

class SessionService
{

	private $lifetime = "+30 minutes";

    public String $domain = "";
	
    public Session $sessionHandler;
    private AccountLogin $accountLogin;

	function __construct() {

		$this->sessionHandler = new Session();
		$this->accountLogin = new AccountLogin();

	}

	public function init() {

		if(!$this->sessionHandler->isStarted()) {
			$this->sessionHandler->start();
		}

		if(!$this->sessionHandler->has("id")) {
			$this->buildAttributes();
		}

		if(time() >= $this->sessionHandler->get("end")) {
			$this->renew();
		}

	}

    public function monitor() {

		if(input()->get("logout")) {
			$data = $this->sessionHandler->get("account");
			$account_id = $data['account_id'] ?? null;
			return $this->endSession($account_id);
		}

		$response = $this->verifySession();

		if($response === false) {
			/** invalid session */
			$this->endSession();
			return false;
		}

		return $response;
		
	}

    public function verifySession() {

		$data = $this->sessionHandler->get("account");

		if($data) {
		
			$this->accountLogin->where([
				"status" => 1,
				"account_id" => $data['account_id']
			])->getList();

			if($this->accountLogin->rows > 1) { 
				return $this->endSession($data['account_id']);
			}

			$this->accountLogin
				->join(table: "accounts", table_key_id: "account_id", other_key_id: "account_id")
					->join(table: "accounts_profile", table_key_id: "account_id", other_key_id: "account_id")
						->where([
				"status" => 1,
				"session_id" => $data['login_session_id']
			]); 

			$result = $this->accountLogin->getId($data['account_login_id']);

			if($result) {

				if($result['session_id'] != $data["login_session_id"]) {
					return false;
				}

				return $this->updateSession($result);

			}else {
				return false;
			}

		}

		return false;

	}

    public function updateSession($data) {

		foreach($data as $key => $val) {
			$_SESSION['account'][$key] = $val;
		}

		return $_SESSION;

	}

    public function endSession($account_id = null) {

        if($account_id) {
			$this->accountLogin->where([
				"account_id" => $account_id
			])->save([
				"status" => 0
			]);
		}

		if(!$this->sessionHandler->isStarted()) {
			$this->sessionHandler->start();
		}

		$this->sessionHandler->regenerateId();
		$this->sessionHandler->clear();
		$this->sessionHandler->destroy();
		
		return false;
	}

	public function buildAttributes() {

		$timestamp = time();

		$this->sessionHandler->replace([
			"id" => $this->sessionHandler->getId(),
			"started" => $timestamp,
			"end" => strtotime($this->lifetime, $timestamp)
		]);
		
	}

	public function renew() {
		$this->sessionHandler->regenerateId();
		$this->buildAttributes();
	}

	public function getAttributes(): mixed {
		return $this->sessionHandler->all();
	}

	public function setDomain($domain) {
		$this->domain = $domain;
		return $this;
	}

	public function getDomain($domain) {
		$this->domain = $domain;
	}

}