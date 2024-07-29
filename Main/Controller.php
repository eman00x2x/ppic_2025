<?php

namespace Main;

use Pecee\SimpleRouter\Route\RouteUrl;
use Main\Services\AuthorizationService as AuthorizationService;

class Controller {

	private $template;
	private $basePath;
	protected $document;
	protected $logged;
	protected $responseType = "HTML";

	protected AuthorizationService $AuthService;

	/**
     * @var array
     */
	protected Array $validationRules = [];

	function __construct() {
		$this->AuthService = new AuthorizationService(request());
	}

	function response($error) {
		switch($error) {
			case 404: return request()->setRewriteRoute((new RouteUrl(url(null, null, []), "ErrorsController@notFound")));
			case 403: return request()->setRewriteRoute((new RouteUrl(url(null, null, []), "ErrorsController@forbidden")));
			case 500: return request()->setRewriteRoute((new RouteUrl(url(null, null, []), "ErrorsController@serverError")));
		}
	}

	function getLibrary($library, $data=null) {
		$class = "\\Library\\".$library;
		return new $class($data);
	}

	function setHttpHeaders($statusCode, $description=null) {
		$this->getLibrary("Factory")->getHeaderStatus($statusCode,$description);
	}

	function setResponseType($type) {

		$type = strtoupper($type);

		if(in_array($type, ["JSON", "HTML"])) {
			$this->responseType = $type;
		}else {
			throw new \Exception("Controller response type should be JSON or HTML only. ");
		}
		
	}

	function setTemplateBasePath($path) {
		$this->basePath = $path;
	}

	function setTemplate($path) {
		$this->template = $path;
	}

	function render($data = null, $model = null, $document_headers = null) {
		View::setBasePath($this->basePath);

		switch($this->responseType) {
			case "JSON":
				View::renderJSON($data);
				break;
			
			default:
				return View::getTemplate($this->template, $data, $model, $document_headers);
				break;
		}

	}

	/**
	 * Adds validation rules.
	 *
	 * @param array $rules The validation rules to be added. The array should be structured like $validationRules property:
	 * The available constraints and their values are in model /Main/Model/[model].php.
	 *
	 * @return $this
	 */
	function addValidationRule($rules) {
		foreach($rules as $field_name => $constraints) {
			foreach($constraints as $constraint => $value) {
				$this->validationRules[$field_name][$constraint] = $value;
			}
		}
		return $this;
	}

	/**
	 * Validates input data against defined validation rules.
	 *
	 * @param array $data The input data to be validated.
	 * @param array $constraints The validation rules to apply.
	 * @return mixed Returns an array with status, errors or validated data.
	 * @throws \Exception If validation rules are not set.
	 */
	function validateInput(array $data, array $validation_rules = null): mixed {

		try {

			// Create a validator instance.
			$validator = \Library\Factory::getValidator();

			// Capture the input data and apply the validation rules.
			$validator->capture($data, $validation_rules);

			// Check if any errors were found.
			if($validator->foundErrors()) {
				// Return an array with status and errors.
				return [
					"status" => 2,
					"message" => $validator->listErrors(", ")
				];
			}

			$validated_data = $validator->getValidatedData();

			foreach($data as $key => $val) {
				$data[$key] = isset($validated_data[$key]) ? $validated_data[$key] : $data[$key];
			}

			// Return an array with status and validated data.
			return [
				"status" => 1,
				"validated" => $data
			];

		} catch (\Exception $e) {
			// Catch any exceptions and print the error message.
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}

	}

	function moveFile($filename, $current_path = "/images/accounts", $destination_path = "/images/accounts", $rename = true) {

		$old_file_directory = ROOT . "/Cdn". $current_path;

		if(!file_exists($old_file_directory . "/" . $filename)) {
			throw new \Exceptions("File " . $filename . " not found!");
		}

		$new_file_directory = ROOT."/Cdn". $destination_path;

		/* $name = explode(".",$filename);
		$extension = array_pop($name);

		if($rename) {
			$random_chararacters = bin2hex(random_bytes(15));
			$new_filename = $random_chararacters . "." . $extension;
		}else { $new_filename = $filename; } */

		if(!is_dir($new_file_directory)) {
			mkdir($new_file_directory, 0775, true);
		}

		rename($old_file_directory . "/" . $filename, $new_file_directory . "/" . $new_filename);

		return $new_filename;
		
	}

	function uploadFile($data = null, $params = ["file_type" => "image"]) {

		$upload = $this->getLibrary("FileUpload");

		if(isset($params['multiple']) && $params['multiple'] === true) {
			$upload->multipleFiles($data, $params);
		}else {
			$upload->file($data, $params);
		}
		
		return $upload->getResults();

	}

	function removeFile($filename, $path = "/images/accounts") {

		$file = ROOT."/Cdn".$path."/".$filename;
		
		/* check file if exists in folder */
		if(file_exists($file)) {
			@unlink($file);
			return true;
		}else {
			return false;
		}
		
	}

}
