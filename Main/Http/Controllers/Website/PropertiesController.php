<?php

namespace EO\Http\Controllers\Website;

use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;
use EO\View;
use EO\Http\BaseController;
use EO\Services\PropertyService as PropertyService;

class PropertiesController extends BaseController
{
	protected PropertyService $propertyService;
	
	function __construct()
	{
		View::setTemplateBasePath( ROOT . "/Resources/Templates");
		
		$this->propertyService = new PropertyService();
	}

	function index() {
		return trim(url()->getPath(), "/") == "buy" ? $this->propertyLists("for sale") :  $this->propertyLists("for rent");
	}
	

	function propertyLists($listing_type = null)
	{
		$request = input()->all() ?? [];

		$data['properties'] = $this->propertyService->getProperties(request: array_merge($request, [
			"listing_type" => ($listing_type !== null ? $listing_type : "for sale"),
			"status" => 1,
			"sort" => (isset($request['sort']) ? $request['sort'] : "modified_at|desc"),
		]));

		$data['collections'] = [
			"listing_types" => $this->propertyService->listingTypeCollection(),
			"categories" => $this->propertyService->categoriesCollection(),
			"amenities" => $this->propertyService->amenitiesCollection(),
			"sorting_fields" => []
		];

		if(isset($request['page'])) {
			$page = $request['page'];
			unset($request['page']);
		}

		foreach(["title", "category", "price", "lot_area", "floor_area", "bedroom"] as $field) {

			$data['collections']["sorting_fields"][$field] = [
				"field" => ucwords(str_replace("_", " ", $field)),
				"direction" => "ASC",
				"uri" => array_merge($request, [
					"sort" => $field . "|ASC"
				])
			];

			if(isset($request['sort'])) {
				[$sort, $direction] = explode("|", $request['sort']);

				if($field === $sort) {
					if($direction === "ASC") {
						$data['collections']["sorting_fields"][$field]["direction"] = "DESC";
						$data['collections']["sorting_fields"][$field]["uri"]["sort"] = $field . "|DESC";
					}else {
						$data['collections']["sorting_fields"][$field]["direction"] = "ASC";
						$data['collections']["sorting_fields"][$field]["uri"]["sort"] = $field . "|ASC";
					}
				}
			}

			if(isset($page)) {
				$data['collections']["sorting_fields"][$field]["uri"]["page"] = $page;
			}

		}

		return View::set("/website/properties/properties.php")->bind(data: $data);
	}

	function relatedProperties()
	{
		$request = input()->all() ?? false;

		$request["AND"] = [
			"property_id[!]" => $request["property_id"]
		];

		unset($request["property_id"]);

		$request['sort'] = "post_score|desc";

		$data['properties'] = $this->propertyService->getProperties(request: $request);
		return View::set("/website/properties/relatedProperties.php")->bind(data: $data);
	}

	function viewProperty($name, $id)
	{
		$data = $this->propertyService->getProperty($id);

		if($data['name'] !== $name) {
			throw new NotFoundHttpException("Resource Not Found!");
		}

		unset($data['account']['username']);
		unset($data['account']['account_type']);
		unset($data['account']['registered_at']);
		unset($data['account']['permissions']);

		$data['reference'] = json_encode([
			"property_id" => $data['property_id'],
			"thumb_img" => $data['thumb_img'],
			"url" => $data['url'],
			"title" => $data['title'],
			"price" => $data['price_tag'],
			"account" => $data['account']
		]);

		return View::set("/website/properties/property.php")->bind(data: $data);
	}

}