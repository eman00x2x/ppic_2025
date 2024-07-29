<?php

namespace Main\Controllers;

use Main\Interfaces\IController as IController;
use Main\Services\ArticleService as ArticleService;

class ArticlesController extends \Main\Controller
{

	protected ArticleService $ArticleService;

	function __construct() {
		parent::__construct();
		$this->ArticleService = new ArticleService();
	}

	function index() {

		$request = input()->all();
		$request["created_at"] = DATE_NOW;

		$article = $this->ArticleService->list(request: $request, target_url: url("ArticlesController@index"));

		// set the template
		$this->setTemplate("/admin/articles/index.php");
		return $this->render(data: $article->results, model: $article);

	}

	function add() {
		// set the template
		$this->setTemplate("/admin/articles/add.php");
		return $this->render($data);
	}

	/**
	 * Returns the data for an article based on its ID
	 * 
	 * @param int $id The ID of the article to retrieve
	 * @return array The article data, or false if the article could not be found
	 */
	function edit($id) {

		// ensure that the ID is a valid integer
		if(!is_numeric($id)) {
			// invalid data type, redirect to 404 page
			$this->response(404);
		}

		// retrieve the article data
		$article = $this->ArticleService->get($id);

		if(isset($article->column['article_id'])) {

			$this->setTemplate("/admin/articles/edit.php");
			// get the template and pass the data
			return $this->render(data: $article->column, model: $article);
		}
		
		// account not found, redirect to 404 page
		$this->response(404);

	}

	/**
	 * Save new organization record in the database
	 * 
	 * @return JSON A JSON containing the status and message of the operation
	 */
	function saveNew() {

		$this->setResponseType("JSON");

		// Collects all input data from the request and sets the current date and time as the registration date.
		$request = input()->all();
		$request["created_at"] = DATE_NOW;

		$validation = $this->validateInput($request, [
			"title" => [
				"length" => [ "min" => 4, "max" => 100 ],
				"required" => true,
				"restrictedWords" => true
			],
			"content" => [
				"required" => true
			]
		]);

		if($validation['status'] == 2) {
			$this->getLibrary("Factory")->setMsg($validation['message'], "error");
			return $this->render(data: [
				"status" => 2,
				"message" => $this->helper(function: "get_message")
			]);
		}

		$response = $this->ArticleService->create($validation['validated']);

		$this->getLibrary("Factory")->setMsg($response['message'], $response['type']);

		return $this->render(data: [
			"status" => $response['status'],
			"message" => $this->helper(function: "get_message")
		]);

	}

	/**
	 * Deletes an account
	 * @param int $id The ID of the account to delete
	 * @return JSON A JSON containing the status and message of the operation
	 */
	function delete($id) {

		if($this->AuthService->userHasPermission('delete_content') === false) {
			$this->response(403);
		}

		// ensure that the ID is a valid integer
		if(!is_numeric($id)) {
			$this->getLibrary("Factory")->setMsg("Invalid account!", "error");
			$this->setResponseType("JSON");

			$response = [
				"status" => 2,
				"message" => $this->helper(function: "get_message")
			];

			return $this->render(data: $response);
		}

		$request = input()->all();

		if(isset($request['delete'])) {

			$result = $this->ArticleService->destroy($id);
			
			$this->getLibrary("Factory")->setMsg($result['message'], $result['type']);
			
			$this->setResponseType("JSON");
			// return the response
			return $this->render(data: [
				"status" => $result['status'],
				"message" => $this->helper(function: "get_message")
			]);
			
		}

		$property = $this->ArticleService->get($id);

		if(isset($property->column['property_id'])) {
			$this->setTemplate("/admin/articles/delete.php");
			// get the template and pass the data
			return $this->render(data: $property->column, model: $property);
		}

		$this->setResponseType("JSON");
		return $this->render(data: $property);

	}

	function upload() {

        $response = $this->uploadFile($_FILES['browseFile'], [
			"destination_folder" => "/Cdn/images/articles",
			"temp_url" => CDN."images/temporary",
			"final_url" => CDN."images/articles",
            "file_type" => "image",
            "file_max_size" => "2MB"
		]);

		$this->setResponseType("JSON");
		return $this->render(data: $response);

    }


}