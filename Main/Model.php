<?php

namespace Main;

use Pecee\SimpleRouter\Route\RouteUrl;

class Model extends \Library\Table
{

	function __construct() {

		unset($this->db_host);
		unset($this->db_name);
		unset($this->db_user);
		unset($this->db_pass);
		unset($this->db_prefix);

	}

	/**
	 * Saves a new record in the database.
	 *
	 * @param array $data The data to be saved.
	 * @return array Returns an array with status, type, id, and message.
	 * @throws Exception If data integrity check fails.
	 */
	function saveNew($data) {

		// Check data integrity before saving.
		if($result = $this->checkDataIntegrity(data: $data)) {
			// If data integrity check fails, return the result.
			return $result;
		}

		// Insert the new record into the database.
		$id = $this->insert();

		// Return success message with the inserted id.
		return array(
			"status" => 1,
			"type" => "success",
			"id" => $id,
			"message" => "Successfully saved"
		);

	}

	/**
	 * Saves the current model instance data to the database.
	 *
	 * @param array $data The data to be saved.
	 * @return array Returns an array with status, type, and message.
	 * @throws Exception If data integrity check fails.
	 */
	function save($data): array {
		
		// Check data integrity before saving.
		if($result = $this->checkDataIntegrity(data: $data)) {
			// If data integrity check fails, return the result.
			return $result;
		}

		// Update the current model instance in the database.
		$this->update();

		// Return success message.
		return array(
			"status" => 1,
			"type" => "success",
			"message" => "Successfully saved"
		);

	}

	/**
	 * Validates and processes input data to ensure data integrity.
	 *
	 * @param array $data The input data to be checked and processed.
	 * @return mixed Returns false if data is valid and processed, otherwise returns an array with status and error message.
	 */
	function checkDataIntegrity(array $data): mixed {

		// Process data.
		foreach($data as $key => $val) {
			if(in_array($key, $this->fields)) {
				if(is_array($val)) { $val = json_encode($val);}
				$this->column[$key] = $val;
			}
		}

		// Return false if data is valid and processed.
		return false;

	}

	/**
	 * Filters the model data based on the provided request parameters.
	 *
	 * @param array|bool $request The request parameters. If false, no filtering is performed.
	 * @return $this Returns the current instance of the model for method chaining.
	 */
	function filter($request = false) {

		if($request !== false) {
			$filters = [];
			
			// Loop through the request parameters
			foreach($request as $key => $value) {
				// Check pagination parameters from the filter conditions
				if(in_array($key, ["page", "rows", "sort"])) {
					// Checks and adjusts the value of a pagination parameter if it's less than or equal to zero.
					if($value == "" || $value <= 0) {
						$value = 0;
					}

					switch($key) {
						case "page":
							$this->page['current'] = $value;
							break;
						case "rows":
							$this->page['limit'] = $value;
							break;
						default;
					}

				}else {

					if(stripos($key, "AND") !== false || stripos($key, "OR") !== false) {
						foreach($value as $field_conditions => $filter_value) {
							if($this->validateField($field_conditions)) {
								// If the field is valid, add it to the filter conditions.
								$filters[$key][$field_conditions] = $filter_value;
							}
						}
					}else {
						// Validates a field for filtering.
						if($this->validateField($key)) {
							// If the field is valid, add it to the filter conditions.
							$filters[$key] = $value;
						}
					}
				}

				if(stripos($key, "AND") === false && stripos($key, "OR") === false) {
					$this->page['uri'][$key] = $value;
				}

			}

			// Apply the filter conditions to the model
			$this->where( $filters );

		}
		
		return $this;

	}

	function validateField($field) {

		if($field == "search") {
			return false;
		}

		$field = str_replace([
			"[~]", 	/** LIKE */ 					
			"[>]", 	/** GREATER THAN */ 			
			"[<]", 	/** LESS THAN */ 				
			"[<>]",	/** BETWEEN */ 					
			"[><]",	/** NOT BETWEEN */ 				 
			"[!]", 	/** NOT */ 						
			"[!=]",	/** NOT EQUAL */ 				 
			"[>=]",	/** GREATER THAN OR EQUAL */	 
			"[<=]" 	/** LESS THAN OR EQUAL */ 		
		], "", $field);

		if(in_array($field, $this->fields)) {
			return true;
		}else {
			// Field is invalid, request is invalid, show a 404 response.
			request()->setRewriteRoute((new RouteUrl(url(null, null, []), "ErrorsController@notFound")));
		}

	}

	/**
	 * Handles the ordering of the list based on the request parameters.
	 *
	 * @param array $request The request parameters.
	 * @param string $sorting The initial sorting clause.
	 * @return $this Returns the current instance of the model for method chaining.
	 */
	function sort($request, $sorting) {

		// Check if the 'sort' parameter is set and not empty, and contains a '|' character.
		if(isset($request['sort']) && $request['sort']!== "" && strpos($request['sort'], "|")!== false) {
			// Explode the 'sort' parameter into an array.
			$o = explode("|", $request['sort']);

			// Check if the second element of the array is a valid sort direction ('ASC' or 'DESC').
			if(in_array(strtoupper($o[1]), ["ASC", "DESC"])) {

				// Escape the first element of the array to prevent SQL injection.
				$field_name = $o[0];

				// Check if the field is valid for sorting.
				if($this->validateField($field_name)) {
					// If valid, set the order by clause.  
					$sorting[$field_name] = strtoupper($o[1]);
				}

				// Store the 'sort' parameter in the 'uri' array of the 'page' property.
				$this->page['uri']['sort'] = $request['sort'];
			} else {
				// If the second element of the array is not a valid sort direction ('ASC' or 'DESC'), request is invalid, show a 404 response.
				request()->setRewriteRoute((new RouteUrl(url(null, null, []), "ErrorsController@notFound")));
			}
		}
 
		// Apply the order by clause to the model.
		$this->orderBy($sorting);

		// Return the current instance of the model for method chaining.
		return $this;
	}

	/**
	 * Encrypts the given data using the specified encryption type.
	 *
	 * @param string $data The data to be encrypted.
	 * @param string $type The type of encryption to use. Default is "md5".
	 *
	 * @return string The encrypted data.
	 * @throws Exception If an unsupported encryption type is provided.
	 */
	function encrypt(string $data, $type = "bcrypt"): string {
		switch($type) {
			case "bcrypt":
				return password_hash($data, PASSWORD_BCRYPT, [
					'cost' => 11
				]);
			case "sha1":
				return sha1($data);
			case "md5":
				return md5($data);
			default:
				throw new Exception("Unsupported encryption type: ". $type);
		}
	}

}