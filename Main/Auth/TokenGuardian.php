<?php

namespace EO\Auth;

use Pecee\Http\Request as Request;
use EO\Services\AccountService as AccountService;

class TokenGuardian
{
	public String $domain = "";
   
	protected AccountService $accountService;
	protected $user;

	public function __construct(Request $request) {
		$this->request = $request;
		$this->accountService = new AccountService;
	}

	/**
	 * Retrieves the user associated with the current request.
	 *
	 * @return mixed|null The user associated with the current request, or null if not found.
	 */
	public function user() {
		if ($this->user !== null) {
			return $this->user;
		}

		$api_key = $this->getApiKeyForRequest();

		if (!empty($api_key)) {
			$result = $this->accountService->getApiKey($api_key);
			$this->user = (object) $result[0];
		}

		return $this->user;
	}

	/**
	 * Retrieves the token for the current request.
	 *
	 * This method checks the 'Authorization' header of the request for a token.
	 * If the header is empty, it falls back to checking the 'api_key' parameter
	 * in the request.
	 *
	 * If the token is prefixed with 'Bearer ', the prefix is removed before
	 * returning the token.
	 *
	 * @return string|null The token for the request, or null if no token is found.
	 */
	protected function getApiKeyForRequest() {
		$api_key = $this->request->getHeader('Authorization');

		if (empty($api_key)) {
			$api_key = input()->get("api_key")->value;
		}

		// Remove "Bearer " if the token is prefixed with it
		if (stripos($api_key, 'Bearer ') === 0) {
			$api_key = substr($api_key, 7);
		}

		return $api_key;
	}

	/**
	 * Checks if the user is valid based on the API key.
	 *
	 * @return bool Returns true if the user is valid, false otherwise.
	 */
	public function check() {
		return !is_null($this->user());
	}

	/**
	 * Checks if the current user is an administrator.
	 *
	 * @return bool Returns true if the user is an administrator, false otherwise.
	 */
	public function isAdmin() {
		if($this->user()) {
			return $this->user()->account_type == "Administrator";
		}
	}

}