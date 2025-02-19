<?php

namespace EO\Services;

use EO\Handlers\Exceptions\AuthenticationException as AuthenticationException;
use EO\Interfaces\IService as IService;
use EO\Service as Service;
use EO\Model\LoginModel as Login;
use EO\Model\AccountModel as Account;

class LoginService extends Service
{
	function getLogins(array $request = []): array
	{
		try {
			self::$collections = login::load( Login::columns() )->getCollections($request);
			$items = self::$collections->getItems();

			if ($items->isNotEmpty()) {
				return $items->map(function($data, $key) {
					return $this->formatResultData($data);
				})->toArray();
			}
		} catch (MalformedUrlException $e) {
			// Throw a new exception of type ResourceNotFoundException with a message that includes the message from the caught exception
			throw new ResourceNotFoundException("Resource Not Found! " . $e->getMessage());
		}

		return $items->toArray();
	}

	function getLogin(int $id) {}

	function create(array $data)
	{
		return Login::create([
			"account_id" => $data['account_id'],
			"session_id" => $data['session_id'],
			"status" => 1,
			"login_at" => DATE_NOW,
			"login_details" => base64_decode($data['user_agent'])
		]);
	}

	function update(array $data, int $id): array
	{
		Login::modify($data, $id);
		return $data;
	}

	function updateBy(array $conditions, array $data): array
	{
		Login::modify($data, null, $conditions);
		return $data;
	}

	function destroy($id): void
	{
		Login::delete(["login_id" => $id]);
	}

	/**
	 * Checks for the duality of an account.
	 *
	 * This function checks if an account has any dual records based on the provided account ID.
	 * It first checks for data integrity and then checks for any records with the status set to 1.
	 *
	 * @param int $account_id The ID of the account to check for duality.
	 * @return int number of rows if duality is found, false otherwise.
	 */
	public function getDualLoginsCount($account_id)
	{
		self::$collections = Login::getBy(name: "account_id", value: $account_id, conditions: ["status" => 1]);
		$items = self::$collections->getItems();

		if($items->isNotEmpty()) {
			return $items->count();
		}

		return false;
	}

	function getBySessionId($session_id)
	{
		/* self::$collections = Login::load( Login::columns() )->getCollections(["session_id" => $session_id]); */
		self::$collections = Login::load( Login::columns() )->getBy("session_id", $session_id);
		$items = self::$collections->getItems();

		if($items->isNotEmpty()) {
			return $items->map(function($data, $key) {
				return $this->formatResultData($data);
			})->first()->toArray();
		}

		return false;
	}

	function getTotalLoginPerDay(array $filter = [])
	{
		$this->buildFilters($filter);

		self::$collections = Login::select([
			"total" => Login::raw("CAST(COUNT(login_id) AS UNSIGNED)"),
			"date" => Login::raw("DATE_FORMAT(DATE(FROM_UNIXTIME(login_at)),'%Y-%m-%d')")
		])
		->groupBy(["date"])
		->getCollections($filter);

		$items = self::$collections->getItems();

		if($items->isNotEmpty()) {
			return $items->toArray();
		}

		return false;
	}

	private function formatResultData($data)
	{
		$data->login_date = date("d M Y", $data->login_at);
		
		if($data->login_details != "") {
			$data->login_ip = $data->login_details['geo']['ip'];
			$data->login_browser = $data->login_details['browser'];
			$data->login_timezone = $data->login_details['geo']['timezone'];
			$data->login_provider = $data->login_details['geo']['org'];
			$data->login_location = $data->login_details['geo']['city'] . " " . $data->login_details['geo']['region'];
		}
		
		$data->account = $data->account->toArray();

		return $data;
	}

	private function buildFilters(array &$request): void 
	{
		if(isset($request['login_at'])) {
			if(isset($request['login_at']['from']) && !isset($request['login_at']['to'])) {
				$request['AND']['login_at[>=]'] = strtotime($request['login_at']['from']);
			}

			if(isset($request['login_at']['from']) &&  isset($request['login_at']['to'])) {
				$request['AND']['login_at[<>]'] = [strtotime($request['login_at']['from']), strtotime($request['login_at']['to'])];
			}

			unset($request['login_at']);
		}

		if(isset($request['account_id']) && $request['account_id'] == "null") {
			unset($request['account_id']);
		}
	}
	
}