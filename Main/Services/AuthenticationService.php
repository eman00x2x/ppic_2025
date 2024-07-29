<?php

namespace Main\Services;

use Main\Model\AccountModel as Account;

class AuthenticationService
{

	private Account $account;
    
	function __construct() {
		$this->account = new Account();
	}

    public function checkCredentials($username, $password) {

        $data = $this->account->validateCredentials([
			"username" => $username,
            "password" => $password
		]);

        if($data) {
            $response = $this->isBlock($data['status']);

			if($response) {
               return $data;
            }else {
                $response = [
                    "status" => 2,
                    "type" => "error",
                    "message" => $response['message']
                ];
            }

        }else {
            $response = [
                "status" => 2,
                "type" => "error",
                "message" => "Invalid username or password"
            ];
        }

		return $response;

	}

    public function isBlock($status) {

		/** CHECK ACCOUNT STATUS */
		switch($status) {
			case "pending_activation":
				return [
                    "status" => 2,
                    "type" => "error",
                    "message" => "This account is pending activation, check the account email and activate your account."
                ];
                break;
			case "banned":
				return [
                    "status" => 2,
                    "type" => "error",
                    "message" => "This account is banned."
                ];
                break;
			case "inactive":
				return [
                    "status" => 2,
                    "type" => "error",
                    "message" => "This account is inactive."
                ];
                break;
            case 'expired_subscription':
                return [
                    "status" => 2,
                    "type" => "error",
                    "message" => "This user has been deactivated due to an expired subscription."
                ];
                break;
			default:
				return true;
		}

	}

}