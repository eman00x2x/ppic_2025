<?php

namespace Main\Services;

use Library\Mailer;
use Main\Model\AccountLoginModel as AccountLogin;
use Main\Model\AccountModel as Account;
use Main\Services\AuthenticationService as Auth;
use Main\Services\SessionService as Session;

class LoginService
{
    
	public Session $loginSession;
    protected Auth $auth;
    protected AccountLogin $accountLogin;

    function __construct() {
		$this->auth = new Auth();
		$this->loginSession = new Session();
		$this->accountLogin = new AccountLogin();
    }

	function verify($request, $domain) {

		$result = $this->auth->checkCredentials($request['username'], $request['password']);

		if($result['status'] === 2) {
			return $result;
		}

		$this->accountLogin->where([
			"status" => 1,
			"account_id" => $result['account_id']
		])->getList();

		if($this->accountLogin->rows >= 1) {

			$this->loginSession->endSession($result['account_id']);

			return [
				"status" => 2,
				"message" => "Someone already using this account. You will be logged out in all devices for your account security."
			];

		}

		$result['user_agent'] = base64_decode($request['user_agent']);
		$this->recordLogin($result, $domain);

		return [
			"status" => 1,
			"message" => "Successfully login"
		];

	}

	public function recordLogin($data, $domain) {

		$this->loginSession->init();

		/** LOGIN SESSION */
		$login_session_id = $this->loginSession->sessionHandler->getId();
		$data['login_session_id'] = $login_session_id;

		$response = $this->accountLogin->saveNew([
			"account_id" => $data['account_id'],
			"session_id" => $login_session_id,
			"status" => 1,
			"login_at" => DATE_NOW,
			"login_details" => $data['user_agent']
		]);

		if($response['status'] == 1) {

			$data['account_login_id'] = $response['id'];
			
			foreach($data as $key => $val) {
				$_SESSION['account'][$key] = $val;
			}

			$_SESSION['domain'] = $domain;

		}

	}

	public function generateToken(string $email, int $account_id) {
		$time_validity = strtotime("+24 hours");
		return bin2hex(base64_encode("email=$email&account_id=$account_id&validity=$time_validity"));
    }

	public function splitToken(string $token): array {

		$token = base64_decode(hex2bin($token));
		$uris = explode("&", $token);
		$data = [];

		foreach($uris as $uri) {
            $uri = explode("=", $uri);
            $data[$uri[0]] = $uri[1];
        }

		if(isset($data['email'])) {
			return $data;
		}

		return false;

	}

	public function getPasswordResetToken(string $email, $callable) {
		
		$account = new Account();
		$data = $account->getEmail($email);

		if($data) {

            $token = $this->generateToken(email: $data['email'], account_id: $data['account_id']);
            
			$callable(token: $token, data: $data);
            return [
				"status" => 1,
				"data" => $data
			];

        }

		return [
			"status" => 2,
            "type" => "error",
            "message" => "Invalid email"
		];

	}

	public function validatePaswordResetToken($token) {

		$token = $this->splitToken(token: $token);

		if($token['validity'] < DATE_NOW) {
			return [
				"status" => 2,
				"type" => "error",
				"message" => "Expired token"
			];
		}

		$account = new Account();
		$data = $account->getEmail($token['email']);
		
		if($data) {
            return [
				"status" => 1,
                "data" => $data
			];
        }

		return [
			"status" => 2,
			"type" => "error",
			"message" => "Invalid token"
		];

	}

    function sendPasswordResetLink($request) {
		
		if(isset($request['email'])) {
		
			$response = $this->getPasswordResetToken($request['email'], function($token, $data) {
				
				$message = $this->createPasswordResetEmailTemplate($token, $data);

				$mail = new Mailer();
				$response = $mail
					->build($message)
						->send([
							"to" => [
								$data['email']
							]
						], CONFIG['site_name'] . " Password Reset Link Request ");

				if($response['status'] == 2) {
					throw new \Exception($response['message']);
				}

			});

			if($response['status'] == 1) {
				$response = [
					"status" => 1,
					"message" => "Password reset link has been sent to your registered email."
				];
			}else {
				$response = [
					"status" => 2,
					"message" => "Email \"".$post['email']."\" does not recognized by our system!."
				];
			}

		}else {
			return [
				"status" => 2,
				"message" => "Invalid Email."
			];
		}
		
	}

	function createPasswordResetEmailTemplate($token, $data) {
		$html[] = "<h1><img src='".CDN."images/logo.png' /></h1><br/><table cellpadding='10' cellspacing='2' border='1'>";
		$html[] = "<p>Hi ".$data['username']."!</p>";
		
		$html[] = "<p>You request a password reset link through our system, Please click the link below to reset your password now.</p>";
		
		$link = rtrim(DOMAIN, "/").url("LoginController@getResetPasswordForm", null, ['token' => $token]);
		
		$html[] = "<p>This link will be available for the next 24 hours</p>";
		$html[] = "<p style='padding:10px;'><a href='$link'>Reset your password</a></p>";

		return implode("", $html);
	}

	function saveNewPassword() {

		$post = input()->all(['account_id', 'password', 'confirmPassword']);

		$account = $this->getModel("Account");
		$data = $account->getId(id: $post['account_id']);

		if($data) {
			$request = $account->addValidationRule([
				"password" => [
					"required" => true,
					"length" => [
						"minimum" => 6
					]
				],
				"confirmPassword" => [
					"required" => true,
					"confirmPassword" => $post['password']
				]
			])->save($post);

			if($request['status'] == 1) {
				$this->getLibrary("Factory")->setMsg("Password saved successfully", "success");
				
				$response = array(
                    "status" => 1,
                    "message" => $this->helper(function: "get_message")
                );
			}else {
				$this->getLibrary("Factory")->setMsg($request['message'], "error");

				$response = array(
                    "status" => 2,
                    "message" => $this->helper(function: "get_message")
                );
			}
			
		}else {
			$this->getLibrary("Factory")->setMsg("Account Invalid", "error");

			$response = array(
                "status" => 2,
                "message" => $this->helper(function: "get_message")
            );
		}

		$this->setResponseType("JSON");
		return $this->render($response);

	}

}