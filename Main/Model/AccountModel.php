<?php

namespace Main\Model;

use Main\Interfaces\IModel as IModel;

/**
 * Class AccountModel
 * This class represents the Account Model and implements IModel interface.
 */
class AccountModel extends \Main\Model implements IModel 
{

	public $types = [
		"Administrator", "Registered User", "Organization"
	];

	public $statuses = ["active", "pending_activation", "inactive", "banned"];

	/**
     * AccountModel constructor.
     * Initializes the model with table name, primary key, alias and calls init method.
     */
    function __construct() {
		
		$this->alias = "a";
		$this->table = "accounts";
		$this->primary_key = "account_id";
		$this->init();

	}
	
	/**
     * Validates user credentials.
     * @param string $username The username to validate.
     * @param string $password The password to validate.
     * @return bool|mixed Returns false if validation fails, otherwise returns the user data.
     */
	function validateCredentials($credentials) {
		
		// Check data integrity
		if($response = $this->checkDataIntegrity(data: $credentials)) {
			// data integrity fail
			return $response;
		}

		$data = $this->getBy("username", $this->column['username']);

		if($data) {
			if(password_verify($credentials['password'], $this->column['password'])) {
				return $data;
			}
		}else {
			return false;
		}

	}

	function getEmail($email) {

		// Check data integrity
		if($response = $this->checkDataIntegrity(data: ["email" => $email])) {
			// data integrity fail
			return $response;
		}

		if($data = $this->getBy("email", $this->column['email'])) {
			return $data;
		}else { 
			return false;
		}

	}

	function getUsername($username) {

		// Check data integrity
		if($response = $this->checkDataIntegrity(data: ["username" => $username])) {
			// data integrity fail
			return $response;
		}

		if($data = $this->getBy("username", $this->column['username'])) {
			return $data;
		}else {
			return false;
		}

	}

}