<?php

namespace EO\Services;

use Pecee\Exceptions\InvalidArgumentException;
use Pecee\Http\Exceptions\MalformedUrlException;
use EO\Handlers\Exceptions\ValidationException;
use EO\Handlers\Exceptions\ResourceNotFoundException;
use EO\Auth\Auth;
use EO\Service;
use EO\Interfaces\IModel;
use EO\Facades\FileSystemFacade as FileSystem;
use EO\Facades\CacheFacade as Cache;
use EO\Model\AccountModel as Account;
use EO\Services\PropertyService as PropertyService;

class AccountService extends Service
{
	private $rootDirectory = ROOT . "/Public/global_assets/images";
	private bool $uniqueEmailAddress = true;
	private bool $uniqueUsername = true;

	public function __construct()
	{
		parent::__construct();

		$this->validator->setConstraints([
			"names.firstname" => [
				"required" => true,
				"textOnly" => true,
				"length" => ["min" => 2, "max" => 100],
				"restrictedWords" => true
			],
			"names.lastname" => [
				"required" => true,
				"textOnly" => true,
				"length" => ["min" => 2, "max" => 100],
				"restrictedWords" => true
			],
			"mobile_number" => [
				"required" => true
			],
			"username" => [
				"length" => ["min" => 4, "max" => 100],
				"required" => true,
				"username" => true,
				"restrictedWords" => true
			],
			"email" => [
				"required" => true,
				"email" => true,
			],
		]);
	}

	public function getAccounts(array $request = []): array
	{
		$this->buildFilters($request);

		try {
			self::$collections = Account::load( Account::columns() )->getCollections($request);
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

	public function getAccount(int $id): array
	{
		if ($_ENV['CACHE_ENABLE'] && ($account = Cache::getData("account-$id"))) {
			return $account;
		}
	
		self::$collections = Account::load( Account::columns() )->getId($id);
		$items = self::$collections->getItems();

		if ($items->isNotEmpty()) {
			$account = $items->map(function($data, $key) {
				return $this->formatResultData($data);
			})->first()->toArray();

			if ($_ENV['CACHE_ENABLE']) {
				Cache::setData("account-$id", $account);
			}

			return $account;
		}else {
			throw new ResourceNotFoundException("Resource Not Found! Account ID: $id");
		}
	
		return $items->toArray();
	}

	public function create(array $data): Int
	{
		$data['registered_at'] = DATE_NOW;

		$this->setCreateValidationConstraints($data);
		$data = $this->validateInput($data);

		// Encrypt the password in the input data
		$data['password'] = Account::encrypt($data['password']);

		if(!isset($data['status'])) { $data['status'] = "pending_activation"; }
		if(!isset($data['account_type'])) { $data['account_type'] = "Registered User"; }
		if(!isset($data['permissions'])) { $data['permissions'] = $this->getDefaultPermissions(); }
		if(!isset($data['names'])) { $data['names']['firstname'] = ""; $data['names']['lastname'] = ""; }

		try{
			$id = Account::create($data);
			$this->log([
				'type' => 'info',
				'message' => "Account creation with ID: $id succeeded",
				'data' => $data
			]);
		}catch(\Exception $e) {
			$this->log([
				"type" => "warning", 
				'message' => "Account creation failed",
				"data" => [
					"error" => $e->getMessage(),
					"data" => $data
				]
			]);
			throw new \Exception($e->getMessage());
		}

		return $id;
	}
	
	public function update(int $id, array $data): array
	{
		$this->setUpdateValidationConstraints($data, $id);
		$data = $this->validateInput($data);

		if(isset($data['password'])) {
			$data['password'] = Account::encrypt($data['password']);
		}

		try {
			Account::modify($data, $id);
			$this->log([
				"type" => "info", 
				"message" => "Account update with ID: $id succeeded",
				"data" => $data
			]);
		}catch (\Exception $e) {
			$this->log([
				"type" => "warning",
				"message" => "Account update with ID: $id failed",
				"data" => [
					"error" => $e->getMessage(),
					"data" => $data
				]
			]);
			throw new \Exception($e->getMessage());
		}

		if ($_ENV['CACHE_ENABLE']) {
			Cache::removeCache("account-$id");
		}

		return $data;
	}

	public function updateAccountsStatus(array $ids, string $status): void
	{
		if(empty($ids)) {
			throw new InvalidArgumentException("No IDs provided for updateAccountsStatus, IDs should be array and not empty!");
		}

		Account::modify(['status' => $status, "account_id" => $ids]);
		$this->log([
			"type" => "info", 
			"message" => "Account change status to $status succeeded", 
			"data" => [
				"ids" => $ids,
				"status" => $status
			]
		]);
	}

	public function destroy(int $id): void
	{
		$account = $this->getAccount($id);

		FileSystem::remove( $this->rootDirectory . "/accounts/" . basename( $account['photo'] ));

		// Delete all properties associated with the account being deleted
		$property_service = new PropertyService();
		$property_service->destroyByAccountId($id);

		// Delete the account record from the database
		Account::delete(["account_id" => $id]);
		$this->log([
			"type" => "info", 
			"message" => "Account deleted with ID: $id succeeded",
			"data" => $account
		]);

		// If caching is enabled, remove the cache entries for the profile and account
		if ($_ENV['CACHE_ENABLE']) {
			Cache::removeCache("account-$id");
		}
	}

	public function setCreateValidationConstraints(array $data): void
	{
		$this->validator->setConstraints([
			"email" => ["unique" => $this->validateUniqueEmail($data['email'])],
			"username" => ["unique" => $this->validateUniqueUsername($data['username'])],
			"password" => [
				"length" => ["min" => 6],
				"required" => true,
			],
			"confirm_password" => [
				"required" => true,
				"confirmPassword" => $data['password'],
			],
		]);
	}

	public function setUpdateValidationConstraints(array &$data, int $id): void
	{
		if ((isset($data['password']) && $data['password'] != "") || (isset($data['confirm_password']) && $data['confirm_password'] != "")) {
			$this->validator->setConstraints([
				"password" => [
					"required" => true,
					"length" => ["min" => 6]
				],
				"confirm_password" => [
					"required" => true,
					"confirmPassword" => $data['password']
				]
			]);
		}else {
			unset($data['password'], $data['confirm_password']);
		}

		$this->validator->setConstraints([
			"email" => ["unique" => $this->validateUniqueEmail($data['email'], $id)],
			"username" => ["unique" => $this->validateUniqueUsername($data['username'], $id)],
		]);
	}

	public function setResetPasswordValidationConstraints(array &$data): void
	{
		$this->validator->resetConstraints()->setConstraints([
			"password" => [
				"required" => true,
				"length" => ["min" => 6]
			],
			"confirm_password" => [
				"required" => true,
				"confirmPassword" => $data['password']
			]
		]);
	}

	/**
	 * Validates whether an email address is unique for an account.
	 *
	 * @param string $email The email address to be validated.
	 * @param int|null $id The ID of the account (optional).
	 * @return bool True if the email address is existing, false otherwise.
	 */
	private function validateUniqueEmail(string $email, ?int $id = null): bool
	{
		// Check if the uniqueEmailAddress property is true and if an email exists with the given email and ID
		if ($this->uniqueEmailAddress && $this->getEmail($email, $id)) {
			// If the condition is true, return true indicating the email is existing
			return true;
		}
		
		// If the condition is false, return false indicating the email is unique
		return false;
	}

	/**
	 * Check if a username is already taken by another account, excluding the account with the specified ID (if provided).
	 *
	 * @param string $username The username to check.
	 * @param int|null $id The ID of the account to exclude from the check.
	 * @return bool Returns true if the username is already taken, false otherwise.
	 */
	private function validateUniqueUsername(string $username, ?int $id = null): bool
	{
		// Check if the uniqueUsername property is true and if the getUsername method returns true for the provided username and ID.
		if ($this->uniqueUsername && $this->getUsername($username, $id)) {
			// If the username is already taken, return true.
			return true;
		}

		// If the username is not already taken, return false.
		return false;
	}

	/**
	 * Check the data integrity of the provided email address using the account object's checkDataIntegrity method
	 *
	 * @param string $email The email address to check.
	 * @param int|null $id The ID of the account (optional).
	 * @return mixed The email address retrieved from the account data.
	 */
	public function getEmail(string $email, ?int $id = null): mixed
	{
		$conditions = [];

		if ($id !== null) {
			$conditions = ["account_id[!]" => $id];
		}

		$collection = Account::getBy("email", $email, $conditions);
		$items = $collection->getItems();

		if($items->isNotEmpty()) {
			return $items->toArray();
		}

		return false;
	}

	/**
	 * Retrieves the account data associated with the provided username, optionally excluding the account with the specified ID.
	 *
	 * @param string $username The username to retrieve account data for.
	 * @param int|null $id The ID of the account to exclude from the retrieval (optional).
	 * @return mixed The account data associated with the provided username.
	 */
	public function getUsername(string $username, ?int $id = null): mixed
	{
		$conditions = [];
		if ($id !== null) {
			$conditions = ["account_id[!]" => $id];
		}

		$collection = Account::getBy("username", $username, $conditions);
		$items = $collection->getItems();

		if($items->isNotEmpty()) {
			return $items->toArray();
		}

		// Retrieve the account data where the username matches the provided username
		return false;
	}

	public function getApiKey(string $token)
	{
		$collection = Account::getBy("api_key", $token);
		$items = $collection->getItems();

		if($items->isNotEmpty()) {
			return $items->toArray();
		}

		return false;
	}

	public function getTotalAccountsPerStatus()
	{
		$collection = Account::select([
			"active" => Account::raw("CAST(SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as UNSIGNED)"),
			"inactive" => Account::raw("CAST(SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) as UNSIGNED)"),
			"pending_activation" => Account::raw("CAST(SUM(CASE WHEN status = 'pending_activation' THEN 1 ELSE 0 END) as UNSIGNED)"),
			"banned" => Account::raw("CAST(SUM(CASE WHEN status = 'banned' THEN 1 ELSE 0 END) as UNSIGNED)")
		])
		->getCollections([
			"rows" => 10000
		]);

		$items = $collection->getItems();

		if($items->isNotEmpty()) {
			return $items->first()->toArray();
		}

		return false;
	}

	public function getTotalAccounts()
	{
		$collection = Account::select([
			"total" => Account::raw("COUNT(*)")
		])->limit(10000)->get();

		$items = $collection->getItems();

		if($items->isNotEmpty()) {
			return $items->first()->toArray();
		}

		return false;
	}

	/**
	 * Validates the provided credentials against the account data.
	 *
	 * @param array $credentials The credentials to be validated.
	 *                           The array should contain the 'username' and 'password' keys.
	 * @return array|bool The account data if the credentials are valid, false otherwise.
	 */
	public function validateCredentials(array $credentials)
	{
		$collection = Account::getBy("username", $credentials['username']);
		$items = $collection->getItems();

		if ($items->isNotEmpty()) {
			$account = $items->first();

			// Verify the provided password against the stored password using the password_verify function
			if (password_verify($credentials['password'], $account->password)) {
				return $account->toArray();
			}
		}

		// If the credentials are invalid, return false
		return false;
	}

	public function downloadData()
	{
		$columns = [
			"fields" => [
				"account_id" => "accounts.account_id", 
				"photo_link" => "accounts.photo", 
				"full_name" => [
					"raw" => "CONCAT(JSON_UNQUOTE(<accounts.names>->'$.firstname'), ' ', JSON_UNQUOTE(<accounts.names>->'$.lastname'))"
				],
				"username" => "accounts.username", 
				"email" => "accounts.email", 
				"mobile_number" => "accounts.contact_number",
				"birthdate" => "accounts_profile.birthdate",
				"account_type" => "accounts.account_type", 
				"status" => "accounts.status", 
				"registered_at" => [
					"raw" => "FROM_UNIXTIME(registered_at)"
				]
			],
			"join" => [
				"accounts_profile" => ["account_id", "account_id"],
			]
		];

		$header = [array_map("strtoupper", array_keys($columns["fields"]))];

		self::$collections = Account::load($columns)->limit(10000)->getCollections();
		$items = self::$collections->getItems();

		$this->downloadToCSV(data: array_merge($header, $items->toArray()), header: array_keys($columns["fields"]), fileName: "accounts-" . DATE_NOW);
	}

	/**
	 * Formats the result data by reorganizing the name and calculating the full name.
	 *
	 * @param array $data The input data to be formatted
	 * @return array The formatted data
	 */
	private function formatResultData(IModel $data): IModel
	{
		$data->names = [
			"firstname" => $data->names['firstname'] ?? "",
			"lastname" => $data->names['lastname'] ?? "",
			"middlename" => $data->names['middlename'] ?? "",
			"suffix" => $data->names['suffix'] ?? "",
			"nickname" => $data->names['nickname'] ?? "",
		];

		$data->fullname = $data->names['firstname'] . " " . $data->names['lastname'];

		$data->photo = $data->photo ?? CDN . "/images/blank-profile.png";
		$data->registered_date = date("d M Y", $data->registered_at);

		unset($data->password);

		return $data;
	}

	private function buildFilters(array &$request): void
	{
		if (isset($request['search'])) {
			$request["OR"] = [
				"username[~]" => $request['search'],
				"email[~]" => $request['search']
			];
			unset($request['search']);
		}

		if(isset($request['registered_at'])) {
			if(isset($request['registered_at']['from']) && !isset($request['registered_at']['to'])) {
				$request['AND']['registered_at[>=]'] = strtotime($request['registered_at']['from']);
			}

			if(isset($request['registered_at']['from']) && isset($request['registered_at']['to'])) {
				$request['AND']['registered_at[<>]'] = [strtotime($request['registered_at']['from']), strtotime($request['registered_at']['to'])];
			}
			
			unset($request['registered_at']);
		}

		if(isset($request['account_type'])) {
			$request['AND']['account_type[~]'] = $request['account_type'];
			unset($request['account_type']);
		}

	}
	
	public function handleFileUpload(string $new_photo_url, ?int $account_id = null): string
	{
		if($account_id === null) {
			return $new_photo_url;
		}
		
		$data = $this->getAccount($account_id);

		if (isset($new_photo_url) && $new_photo_url !== $data['photo']) {
			$this->removeOldPhoto($data['photo']);
			$file_name = $this->moveNewPhoto($new_photo_url);
			return CDN . "/images/accounts/$file_name";
		}

		return $data['photo'];
	}

	private function removeOldPhoto(string $old_photo_url): void
	{
		if (basename($old_photo_url) !== "blank-profile.png") {
			FileSystem::remove($this->rootDirectory . "/accounts/" . basename($old_photo_url));
		}
	}

	private function moveNewPhoto(string $new_photo_url): string
	{
		return FileSystem::move(
			$this->rootDirectory . '/temporary/' . basename($new_photo_url),
			$this->rootDirectory . '/accounts/' . basename($new_photo_url),
		);
	}


	public function getStatuses(): array
	{
		return ["active", "pending_activation", "inactive", "banned"];
	}

	public function getAccountTypes(): array
	{
		return ["Administrator", "Registered User"];
	}

	function getPermissions(): array
	{
		return Auth::definePermissions();
	}

	function getDefaultPermissions(): array
	{
		return Auth::defineDefaultPermissions();
	}

}

