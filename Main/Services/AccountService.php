<?php

namespace Main\Services;

use Main\Interfaces\IService as IService;
use Main\Model\AccountModel as Account;
use Main\Model\ProfileModel as Profile;

class AccountService implements IService
{

    public Account $account;

	public $uniqueEmailAddress = true;
	public $uniqueUsername = true;

    function __construct() {
        $this->account = new Account();
    }

    function list(array $request, string $target_url) {

		$this->account
			// Apply filters based on the request parameters.
			->filter(request: $request)
			// Arrange the accounts based on the request parameters default to registered_at in descending order.
			->sort(request: $request, sorting: ["registered_at" => "DESC"])
			// Retrieve the paginated list of accounts.
			->getList(
				// Determine the page number from the request, default to 1 if not provided.
				page: ($request['page'] ?? 1),
				// Determine the limit of accounts per page from the request, default to 20 if not provided.
				limit: ($request['rows'] ?? 20),
				// The target URL for pagination links.
				url: $target_url
			);

		return $this->account;

    }

    function get(int $id) {

        $this->account->join("accounts_profile", "account_id")->getId($id);

		if(isset($this->account->column['account_id'])) {

			$this->account->column['name'] = [
				"firstname" 	=> ($this->account->column['name']['firstname'] ?? ""),
				"lastname" 		=> ($this->account->column['name']['lastname'] ?? ""),
				"middlename" 	=> ($this->account->column['name']['middlename'] ?? ""),
				"suffix" 		=> ($this->account->column['name']['suffix'] ?? ""),
				"nickname" 		=> ($this->account->column['name']['nickname'] ?? "")
			];

			$this->account->column['birthdate'] = $this->account->column['birthdate'] ?? $this->account->column['birthdate'];

		}

		return $this->account;

    }

    function create(array $data) {

        // Checks if the provided email already exists in the database.
		if($this->account->getEmail($data['email'])) {
            $errors[] = "Email already exists!";
		}

        if($this->account->getUsername($data['username'])) {
			$errors[] = "Username already exists!";
		}

        if(isset($errors)) {
            return [
                "status" => 2,
				"type" => "error",
                "message" => implode(", ", $errors)
            ];
        }

        $data['password'] = $this->account->encrypt($data['password']);
		$result = $this->account->saveNew(data: $data);

        if($result['status'] == 2) {
			return [
				"status" => 2,
				"type" => "error",
				"message" => $result['message']
			];
		}else {

			$profile = new Profile();
			$data['account_id'] = $result['id'];
			$data["updated_at"] = DATE_NOW;

			$profile->saveNew(data: $data);

			return [
				"status" => 1,
				"type" => "success",
				"message" => "Successfully save!"
			];

		}

    }

    function update(int $id, array $data) {

        $response = $this->account->getId(id: $id);

		if($this->uniqueEmailAddress === true) {
			if($result = $this->account->where(["account_id[!=]" => $id])->getEmail($data['email'])) {
				$errors[] = "Email already exists!";
			}
		}

		if($this->uniqueUsername === true) {
			if($result = $this->account->where(["account_id[!=]" => $id])->getUsername($data['username'])) {
				$errors[] = "Username already exists!";
			}
		}

        if(isset($errors)) {
            return [
                "status" => 2,
				"type" => "error",
                "message" => implode(", ", $errors)
            ];
        }

        if($response) {

            $result = $this->account->where([
				"account_id" => $id
			])->save(data: $data);

			return [
				"status" => $result['status'],
				"type" => "success",
				"message" => $result['message']
			];

        }

        return [
			"status" => 2,
			"type" => "error",
            "message" => "Account not found!"
		];

    }

    function destroy($id) {

        $data = $this->account->getId(id: $id);

        if($data) {

            // delete the profile
			$profile = new Profile();

			$profile->where([
				"account_id" => $id
			])->delete();

			$this->account->where([
				"account_id" => $id
			])->delete();

            return [
                "status" => 1,
                "type" => "success",
                "message" => "Account deleted!"
            ];

        }

        return [
			"status" => 2,
			"type" => "error",
            "message" => "Account not found!"
		];

    }

}