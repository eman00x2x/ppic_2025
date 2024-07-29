<?php

namespace Main\Controllers;

use Main\Interfaces\IController as IController;
use Main\Services\PropertyService as PropertyService;
use Main\Services\PropertyImageService as PropertyImageService;
use Main\Services\ProfileService as ProfileService;
use Main\Services\AccountService as AccountService;
use Main\Services\ArticleService as ArticleService;

class WebController extends \Main\Controller
{

	protected PropertyService $PropertyService;
	protected PropertyImageService $PropertyImageService;
	protected ProfileService $ProfileService;
	protected AccountService $AccountService;
	protected ArticleService $ArticleService;
	
	function __construct() {
		parent::__construct();
	}

	function index() {

		$this->ArticleService = new ArticleService();
		$this->PropertyService = new PropertyService();
		
		$data['videos'] = false;

		$article = $this->ArticleService->list([], url());
		$data['articles'] = $article->results;

		$property = $this->PropertyService->list(request: [
			
			"offer" => 'for sale',
			"status" => 1,
			"rows" => 8
		], target_url: url());
		$data['properties'] = $property->results;

		$this->setTemplate("/website/home/home.php");
		return $this->render($data);

	}

	function contact() {
		$this->setTemplate("/website/contact/contact.php");
		return $this->render();
	}

	function about() {
		$this->setTemplate("/website/about/about.php");
		return $this->render();
	}

	function legal($name) {
		$this->setTemplate("/website/legal/legal.php");
		return $this->render();
	}

	function buy() {
		return $this->getAllProperties([
			"request" => [
				"offer" => "for sale"
			],
			"target_url" => url("WebController@buy")
		]);
	}

	function getAllProperties($params) {

		$this->PropertyService = new PropertyService();
		$this->PropertyImageService = new PropertyImageService();

		// Check if there are any request parameters
		$request = input()->all() ?? false;

		if(isset($request['search'])) {
			$request["OR"] = [
				"name[~]" => $request['search'],
				"description[~]" => $request['search']
			];
		}
		
		$request['rows'] = 2;

		if(isset($params['request'])) {
			foreach($params['request'] as $key => $value) {
				$request[$key] = $value;
			}
		}

		$property = $this->PropertyService->list(request: $request, target_url: $params['target_url']);

		if($property->results) {
			$this->setTemplate("/website/properties/properties.php");
			return $this->render(data: $property->results, model: $property, document_headers: [
				"title" => "Properties For Sale",
				"description" => "Properties For Sale"
			]);
		}

		// redirect to 404 page
		$this->response(404);

	}

	function relatedProperties() {
		$this->setTemplate("/website/properties/related_properties.php");
		return $this->getAllProperties();
	}

	function viewProperty($name, $id) {

		$this->PropertyService = new PropertyService();
		$this->PropertyImageService = new PropertyImageService();

		$property = $this->PropertyService->get($id);
		
		if(isset($property->column['property_id']) && $property->column['name'] == $name) {
			$property->column['images'] = $this->PropertyImageService->getByPropertyId(property_id: $property->column['property_id']);

			debug($property);

			// get the template and pass the data
			$this->setTemplate("/website/properties/property.php");
			return $this->render(data: $property->column, model: $property, document_headers: [
				"title" => $property->column['title'],
				"description" => $property->column['short_description'],
				"image" => $property->column['thumb_img'],
				"modified_at" => date("Y-m-d", $property->column['modified_at'])
			]);
		}

		// redirect to 404 page
		$this->response(404);

	}

}