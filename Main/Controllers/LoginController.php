<?php

namespace Main\Controllers;

use Main\Services\LoginService as Login;

class LoginController extends \Main\Controller
{
    protected $domain;

    function __construct() {
		parent::__construct();
		$this->domain = ADMIN;
    }

	function doLogin() {

		$request = input()->all(["username", "password", "user_agent"]);

		$login = new Login;
		$response = $login->verify($request, $this->domain);

		if($response['status'] === 2) {
			$this->getLibrary("Factory")->setMsg($response['message'], "error");
			$response['message'] = $this->helper(function: "get_message");
		}

		$this->setResponseType("JSON");
		// return the response
		return $this->render(data: $response);

	}

	function getLoginForm() {

		$this->document->addScriptDeclaration(str_replace([PHP_EOL,"\t"], ["",""], "
			$(document).ready(function(e) {
				initUserClient();

				height = $(document).height();
				$('.login-container').css('height',(height - 200));
				$('#user_agent').val(btoa(JSON.stringify(userClient)));
			});

			$(document).on('keypress', '#email, #password', function(e) {
				if(e.which == 13 || e.keyCode == 13) {
					$('.btn-login').trigger('click');
				}
			});

			function validateInput(input) {
				let message = [];

				const data = input.reduce(function (obj, item) {
					obj[item.name] = item.value;
					return obj;
				}, {});

				const validator = validate({
					username: data.username,
					password: data.password
				}, {
					username: { presence: { allowEmpty: false } },
					password: { presence: { allowEmpty: false } }
				});

				if (validator !== undefined) {
					for (key in validator) {
						message.push(validator[key]);
					}
					return message.join('<br /> ');
				}

				return false;
			}

		"));

		$this->document->setTitle("Login");

		$this->setTemplate("/login/login.php");
		return $this->render();
		
	}

	function getForgotPasswordForm() {

		$this->document->setTitle("Send Password Reset Link");

		$this->document->addScriptDeclaration(str_replace([PHP_EOL,"\t"], ["",""], "
			$(document).ready(function(e) {
				initUserClient();

				height = $(document).height();
				$('.login-container').css('height',(height - 200));
				$('#user_agent').val(btoa(JSON.stringify(userClient)));
			});

			$(document).on('keypress', '#email, #password', function(e) {
				if(e.which == 13 || e.keyCode == 13) {
					$('.btn-login').trigger('click');
				}
			});

			function validateInput(input) {
				let message = [];

				const data = input.reduce(function (obj, item) {
					obj[item.name] = item.value;
					return obj;
				}, {});

				const validator = validate({
					email: data.email
				}, {
					email: { presence: { allowEmpty: false } }
				});

				if (validator !== undefined) {
					for (key in validator) {
						message.push(validator[key]);
					}
					return message.join('<br /> ');
				}

				return false;
			}

		"));

		$this->setTemplate("/login/forgotPassword.php");
		return $this->render();
	}

	function getResetPasswordForm() {

		$this->document->setTitle("Password Reset");

		if(!isset($_GET['token'])) {
			$this->response(404);
		}else {

			$this->document->addScriptDeclaration(str_replace([PHP_EOL,"\t"], ["",""], "
				
				$(document).on('keypress', '#password, #cpassword', function(e) {
					if(e.which == 13 || e.keyCode == 13) {
						$('.btn-login').trigger('click');
					}
				});

				function validateInput(input) {
					let message = [];

					const data = input.reduce(function (obj, item) {
						obj[item.name] = item.value;
						return obj;
					}, {});

					const validator = validate({
						cpassword: data.cpassword,
						password: data.password
					}, {
						password: {length: {minimum: 6}},
						confirmPassword: { equality: 'password' }
					});

					if (validator !== undefined) {
						for (key in validator) {
							message.push(validator[key]);
						}
						return message.join('<br /> ');
					}

					return false;
				}

			"));
			
			$response = $this->auth->validatePaswordResetToken($_GET['token']);

			$this->setTemplate("/login/resetPassword.php");
			return $this->render($response);
			
		}

		$this->response(404);

	}

	function passwordResetSuccess() {
		$this->setTemplate("/login/passwordResetSuccess.php");
        return $this->render();
	}

	function getTwoStepVerificationCodeForm() {

		$this->document->addScriptDeclaration("

			document.addEventListener('DOMContentLoaded', function() {
				var inputs = document.querySelectorAll('[data-code-input]');
				// Attach an event listener to each input element
				for(let i = 0; i < inputs.length; i++) {
					inputs[i].addEventListener('input', function(e) {
						// If the input field has a character, and there is a next input field, focus it
						if(e.target.value.length === e.target.maxLength && i + 1 < inputs.length) {
							inputs[i + 1].focus();
						}
					});
					inputs[i].addEventListener('keydown', function(e) {
						// If the input field is empty and the keyCode for Backspace (8) is detected, and there is a previous input field, focus it
						if(e.target.value.length === 0 && e.keyCode === 8 && i > 0) {
							inputs[i - 1].focus();
						}
					});
				}
			});

		");

		$this->setTemplate("/login/2-step-verification-code.php");
		return parent::getTwoStepVerificationCodeForm();
	}

}