<?php

namespace EO\Services;

use Pecee\Http\Exceptions\MalformedUrlException;
use EO\Handlers\Exceptions\ValidationException;
use EO\Handlers\Exceptions\ResourceNotFoundException;
use EO\Service as Service;
use EO\Interfaces\IModel;
use EO\Facades\FileSystemFacade as FileSystem;
use EO\Facades\CacheFacade as Cache;
use EO\Model\PropertyModel as Property;
use EO\Services\PropertyImageService as PropertyImageService;

class PropertyService extends Service
{
	private $rootDirectory = ROOT . "/Public/global_assets";
	private PropertyImageService $propertyImageService;

	function __construct()
	{
		parent::__construct();
		$this->propertyImageService = new PropertyImageService();
		
		$this->validator->setConstraints([
			"title" => [
				"length" => [ "min" => 4, "max" => 100 ],
				"required" => true,
				"restrictedWords" => true
			],
			"category" => ["required" => true],
			"thumb_img" => ["required" => true],
			"lot_area" => ["required" => true],
			"listing_type" => ["required" => true],
			"price" => [
				"required" => true,
				"number" => true
			]
		]);
	}

	function getProperties(array $request): array
	{
		$this->buildFilters($request);
		try {
			self::$collections = Property::load( Property::columns() )->getCollections($request);
			$items = self::$collections->getItems();

			if ($items->isNotEmpty()) {
				return $items->map(function($data, $key) {
					return $this->formatResultData($data);
				})->toArray();
			}
		} catch (MalformedUrlException $e) {
			throw new ResourceNotFoundException("Resource Not Found! " . $e->getMessage());
		}

		return $items->toArray();
	}

	function getProperty(int $id): array
	{
		if ($_ENV['CACHE_ENABLE'] && ($data = Cache::getData("property-$id"))) {
			return $data;
		}

		self::$collections = Property::load( Property::columnsFull() )->getId($id);
		$items = self::$collections->getItems();
 
		if($items->isNotEmpty()) {
			$property = $items->map(function($data, $key) {
				return $this->formatResultData($data);
			})->first()->toArray();

			$property['images'] = $this->propertyImageService->getPropertyImages(["property_id" => $property['property_id']]);
			$property['total_images'] = count($property['images']);

			if ($_ENV['CACHE_ENABLE']) {
				Cache::setData("property-" . $id, $property);
			}

			return $property;
		}else {
			throw new ResourceNotFoundException("Resource Not Found! Property ID: $id");
		}

		return $items->toArray();
	}

	function getPropertyByName(string $name)
	{
		if ($_ENV['CACHE_ENABLE'] && ($data = Cache::getData("property-$name"))) {
			return $data;
		}

		self::$collections = Property::load( Property::columnsFull() )->getBy("name", $name);
		$items = self::$collections->getItems();

		if($items->isNotEmpty()) {
			$property = $items->map(function($data, $key) {
				return $this->formatResultData($data);
			})->first()->toArray();
			
			$property['images'] = $this->propertyImageService->getPropertyImages(["property_id" => $property['property_id']]);
			$property['total_images'] = count($property['images']);

			if ($_ENV['CACHE_ENABLE']) {
				Cache::setData("property-$name", $property);
			}

			return $property;
		}else {
			throw new ResourceNotFoundException("Resource Not Found! Property ID: $name");
		}

		return $items->toArray();
	}

	function getByAccountId(int $account_id): array
	{
		self::$collections = Property::load( Property::columnsFull() )->getBy("account_id", $account_id);
		$items = self::$collections->getItems();
		
		if($items->isNotEmpty()) {
			return $items->map(function($data, $key) {
				return $this->formatResultData($data);
			})->toArray();
		}else {
			throw new ResourceNotFoundException("Resource Not Found! Properties by Account ID: $account_id");
		}

		return $items->toArray();
	}

	function create(array $data): int
	{
		// Set timestamps and sanitize title
		$data["created_at"] = DATE_NOW;
		$data["modefied_at"] = DATE_NOW;
		$data['name'] = $this->helper("sanitize", ["string" => $data["title"]]) . "-" . $this->helper("generateRandomString", 10);

		if (isset($data['upload'])) {
			$data['image_score'] = $this->propertyImageService->computeImageScore($data['upload']);
		}

		$data['post_score'] = $this->calculateScore($data);
		$validated_data = $this->validateInput($data);

		try {
			// Create new property
			$id = Property::create($validated_data);

			if(isset($validated_data['documents'])) {
				$this->processUploadedDocuments($validated_data['documents']);
			}

			$this->log([
				'type' => 'info',
				'message' => "Property posting creation with ID: $id succeeded",
				'data' => $validated_data
			]);
		} catch (\Exception $e) {
			$this->log([
				"type" => "warning",
				"message" => "Property posting creation with ID: $id failed",
				"data" => [
					"error" => $e->getMessage(),
					"data" => $validated_data
				]
			]);
			throw new \Exception($e->getMessage());
		}

		return $id;
	}

	function update(int $id, array $data): array
	{
		$data['image_score'] = $this->propertyImageService->getCurrentImageScore($id);
			
		if (isset($data['upload'])) {
			$data['image_score'] = $this->propertyImageService->computeImageScore($data['upload']);
		}

		$data['modified_at'] = DATE_NOW;
		$data['post_score'] = $this->calculateScore($data);

		$validated_data = $this->validateInput($data);

		$old_data = $this->getProperty($id);

		try {
			Property::modify($validated_data, $id);

			$this->log([
				'type' => 'info',
				'message' => "Property posting with ID: $id succeeded",
				'data' => $validated_data
			]);
		} catch (\Exception $e) {
			$this->log([
				"type" => "warning",
				"message" => "Property updating failed",
				"data" => [
					"error" => $e->getMessage(),
					"data" => $validated_data
				]
			]);
		}

		if(isset($validated_data['documents'])) {
			$this->processUploadedDocuments($validated_data['documents'], ($old_data['documents'] ?? []));
		}

		if ($_ENV['CACHE_ENABLE']) {
			Cache::removeCache("property-" . $old_data['name']);
			Cache::removeCache("property-" . $old_data['property_id']);
		}
		
		return $data;
	}

	public function updateStatus(array $ids, string $status): void
	{
		if(!in_array($status, [1, 2, 3])) {
			throw new InvalidArgumentException("Invalid status provided for updateStatus, status should be available or sold!");
		}

		if(empty($ids)) {
			throw new InvalidArgumentException("No IDs provided for updateStatus, IDs should be array and not empty!");
		}

		Property::modify(['status' => $status, 'modified_at' => DATE_NOW], $ids);

		$this->log([
			'type' => 'info',
			'message' => "Property change status to $status succeeded",
			'data' => [
				'ids' => $ids,
				'status' => $status
			]
		]);

		if ($_ENV['CACHE_ENABLE']) {
			self::$collections = Property::getCollections(["property_id" => $ids]);
			$items = self::$collections->getItems();

			$names = $items->pluck("name")->all();
			Cache::removeMultipleCache($names);
		}
	}

	public function changeCategory(array $ids, string $category): void
	{
		if(empty($ids)) {
			throw new InvalidArgumentException("No IDs provided for changeCategory, IDs should be array and not empty!");
		}

		Property::modify(['category' => $category, 'modified_at' => DATE_NOW], $ids);

		$this->log([
			'type' => 'info',
			'message' => "Property change status to $category succeeded",
			'data' => [
				'ids' => $ids,
				'status' => $category
			]
		]);
	}

	function destroy($id): void
	{
		$data = $this->getProperty($id);

		$this->propertyImageService->destroyByPropertyId($id);
		Property::delete(["property_id" => $id]);

		$this->log([
			"type" => "info", 
			"message" => "Property deleted with ID: $id succeeded",
			"data" => $data
		]);

		if ($_ENV['CACHE_ENABLE']) {
			Cache::removeCache("property-$data[name]");
		}
	}

	function destroyByAccountId($account_id): void
	{
		$data = $this->getByAccountId($account_id);

		foreach($data as $result) {
			$this->propertyImageService->destroyByPropertyId($result['property_id']);
			$this->destroy($result['property_id']);
		}
	}

	function processUploadedDocuments(array $uploaded_documents, array $current_documents = []): void
	{
		foreach($uploaded_documents as $upload) {
			FileSystem::move(
				$this->rootDirectory . "/images/temporary/" . $upload['filename'],
				$this->rootDirectory . "/documents/" . $upload['filename']
			);
		}
		
		if($current_documents) {
			foreach($current_documents as $doc) {
				if(!isset($uploaded_documents[$doc['id']])) {
					FileSystem::remove($this->rootDirectory . "/images/temporary/" . $doc['filename']);
					FileSystem::remove($this->rootDirectory . "/documents\/" . $doc['filename']);
				}
			}
		}
	}

	function getTotalPropertiesPerListingType($filter)
	{
		$filter["status"] = 1;
		$filter["sort"] = "modified_at|desc";

		$this->buildFilters($filter);

		self::$collections = Property::select([
			"listing_type",
			"total" => Property::raw("COUNT(listing_type)")
		])->groupBy( [ "listing_type" ] )
		->getCollections($filter);

		$items = self::$collections->getItems();

		if($items->isNotEmpty()) {
			return $items->toArray();
		}
		
		return false;
	}

	function getTotalPropertiesPerCategory($filter)
	{
		$filter["status"] = 1;
		$filter["sort"] = "modified_at|desc";

		$this->buildFilters($filter);

		self::$collections = Property::select([
			"total" => Property::raw("COUNT(category)"),
			"category",
		])
		->groupBy( [ "category" ] )
		->getCollections($filter);
		
		$items = self::$collections->getItems();

		if($items->isNotEmpty()) {
			return $items->toArray();
		}
		
		return false;
	}

	function getTotalPropertiesPosted(array $filter): array
	{
		unset($filter['modified_at']);
		$filter["status"] = 1;
		$filter["rows"] = 10000;
		$filter["sort"] = "created_at|desc";

		$this->buildFilters($filter);

		self::$collections = Property::select([
			"total" => Property::raw("COUNT(property_id)"),
			"date" => Property::raw("DATE_FORMAT(DATE(FROM_UNIXTIME(created_at)),'%Y-%m-%d')")
		])
		->groupBy([ "date" ])
		->getCollections($filter);

		$items = self::$collections->getItems();

		if($items->isNotEmpty()) {
			return $items->toArray();
		}
		
		return [];
	}

	function getTotalPropertiesUpdated(array $filter): array
	{
		unset($filter['created_at']);
		$filter["status"] = 1;
		$filter["rows"] = 10000;
		$filter["sort"] = "modified_at|desc";

		$this->buildFilters($filter);

		self::$collections = Property::select([
			"total" => Property::raw("COUNT(property_id)"),
			"date" => Property::raw("DATE_FORMAT(DATE(FROM_UNIXTIME(modified_at)),'%Y-%m-%d')")
		])
		->groupBy(["date"])
		->getCollections($filter);
		
		$items = self::$collections->getItems();

		if($items->isNotEmpty()) {
			return $items->toArray();
		}
		
		return false;
	}

	public function getTotalPropertiesPerStatus($filter)
	{
		$this->buildFilters($filter);

		self::$collections = Property::select([
			"available" => Property::raw("CAST(SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as UNSIGNED)"),
			"sold" => Property::raw("CAST(SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as UNSIGNED)"),
			"remove" => Property::raw("CAST(SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) as UNSIGNED)")
		])->getCollections($filter);

		$items = self::$collections->getItems();

		if($items->isNotEmpty()) {
			return $items->first()->toArray();
		}
		
		return false;
	}

	private function formatResultData(IModel &$data): IModel
	{
		if (!file_exists(ROOT . "/Public/global_assets/images/properties/thumb-" . basename($data->thumb_img))) {
			$thumb_img_parts = explode("/", $data->thumb_img);
			$thumb_img_filename = array_pop($thumb_img_parts);
			$data->thumb_img = implode("/", $thumb_img_parts) . "/thumb-" . $thumb_img_filename;
		} else if (!file_exists(ROOT . "/Public/global_assets/images/listings/thumb-" . basename($data->thumb_img))) {
			$thumb_img_parts = explode("/", $data->thumb_img);
			$thumb_img_filename = array_pop($thumb_img_parts);
			$data->thumb_img = implode("/", $thumb_img_parts) . "/thumb-" . $thumb_img_filename;
		}

		if(isset($data->long_desc)) {
			$patterns['empty_tags'] = "/<[^\/>]*>([\s]?)*<\/[^>]*>/";
			$patterns['white_space'] = "/\s+/";

			$replacements['empty_tags'] = '';
			$replacements['white_space'] = '';

			$data->long_desc = preg_replace($patterns, $replacements, strip_tags(trim($data->long_desc, chr(0xC2).chr(0xA0)), "<p><ul><li><ol><h1><h2><h3><h4><h5><h6>"));
		}

		$data->listing_type = ucwords($data->listing_type);

		if(is_array($data->amenities)) {
			$data->amenities = array_map("ucwords", $data->amenities);
		}

		$data->status = $data->availability;
		$data->price_tag = number_format($data->price, 0);
		$data->reservation = number_format($data->reservation, 0);
		$data->created_date = date("d M Y", $data->created_at);
		$data->modified_date = date("d M Y", $data->modified_at);
		$data->short_title = $this->helper("niceTrim", ["string" => $data->title, "max_length" => 60]);
		$data->url = DOMAIN . url("web.view.property", ["name" => $data->name, "id" => $data->property_id]);
		$data->account = $data->account->toArray();

		/* if($data->images != "") {
			$images = [];
			$counter = 0;
			for($i=0; $i<count($data->images); $i++) {
				if($this->helper("checkRemoteFile", $data->images[$i]['url'])) {
					$images[$i] = $data->images[$i];
					$images[$i]['url'] = $data->images[$i]['url'];
					$counter++;
				}
			}
			$data->total_images = $counter;
			$data->images = array_values($images);
		} */
		
		$data->related_properties_search = [
			"property_id" => $data->property_id,
			"category" => $data->category,
			"address" => [
				"region" => isset($data->address['region']) ? $data->address['region'] : "",
				"province" => isset($data->address['province']) ? $data->address['province'] : "",
				"municipality" => isset($data->address['municipality']) ? $data->address['municipality'] : ""
			],
		];

		return $data;
	}

	private function buildFilters(array &$request): void
	{
		unset($request['filter']);
		if(isset($request['search'])) {
			$request["MATCH"] = [
				"columns" => ["property_type", "title", "tags", "long_desc", "category", "properties.address", "amenities"],
				"keyword" => $request['search'],
				"mode" => "natural"
			];
			unset($request['search']);
		}

		if(isset($request['address'])) {
			$request["address[~]"] = [
				"AND" => [
					$request['address']['region'], 
					$request['address']['province'],
					$request['address']['municipality']
				]
			];
			unset($request['address']);
		}

		if(isset($request['priceTo'])) {
			if($request['priceTo'] >= 300000000) {
				$request["price[>=]"] = $request['priceFrom'];
			}else {
				$request["price[<>]"] = [$request['priceFrom'], $request['priceTo']];
			}
			unset($request['priceTo'], $request['priceFrom']);
		}

		if(isset($request['created_at'])) {
			if(isset($request['created_at']['from']) && !isset($request['created_at']['to'])) {
				$request['created_at[>=]'] = strtotime($request['created_at']['from']);
			}

			if(isset($request['created_at']['from']) &&  isset($request['created_at']['to'])) {
				$request['created_at[<>]'] = [strtotime($request['created_at']['from']), strtotime($request['created_at']['to'])];
			}

			unset($request['created_at']);
		}

		if(isset($request['modified_at'])) {
			if(isset($request['modified_at']['from']) && !isset($request['modified_at']['to'])) {
				$request['modified_at[>=]'] = strtotime($request['modified_at']['from']);
			}

			if(isset($request['modified_at']['from']) &&  isset($request['modified_at']['to'])) {
				$request['modified_at[<>]'] = [strtotime($request['modified_at']['from']), strtotime($request['modified_at']['to'])];
			}

			unset($request['modified_at']);
		}

		if(isset($_REQUEST['lot_area'])) {
			[$from, $to] = explode("-", $_REQUEST['lot_area']);
			$request['lot_area[<>]'] = [$from, $to];
			unset($request['lot_area']);
		}

		if(isset($request['account_id']) && $request['account_id'] == "null") {
			unset($request['account_id']);
		}

	}

	function getUniqueId()
	{
		$unique_id = $this->helper("generateRandomString", 10);

		self::$collections = Property::getCollections(["name" => $unique_id]);
		$items = self::$collections->getItems();

		if($items->isNotEmpty()) {
			$this->getUniqueId();
		}else {
			return $unique_id;
		}
	}

	function categoriesCollection()
	{
		return [
			"Residential" => ["House and Lot", "Apartment", "Townhouse", "Condominium", "Condotel", "Residential Lot"],
			"Commercial" => ["Office/Building", "Retail Space", "Hotel", "Commercial Lot"],
			"Industrial" => ["Warehouse", "Factory (Plant)", "Commisary"],
			"Agricultural" => ["Agriculture Lot"],
			"Leisure" => ["Island", "Resort"],
			"Shares" => ["Golf Shares", "Club/Resort Shares"],
			"Others" => ["Memorial Lot", "Columbarium", "Parking Space", "Storage Units"]
		];
		
	}

	function amenitiesCollection()
	{
		return [
			"Lap Pool","Swimming Pool","Jaccuzi","Tennis Court","Bowling Room","Basket Ball Court","Pet-Friendly Residences",
			"Movie Rooms","Game rooms","Libraries and study rooms","Chapels","Clinics","Day care centers","Lobby","Childrens Play Area",
			"Club House","Function Halls","Fitness Center","Spas",
			"Perimeter Fence","Centralized Water System","Eco-friendly and Energy-efficient Homes","24 Hours Security","Guard House","Gated Community","Working Waste Disposal System","Power Backup","Fire Alarms and Suppression System","CCTV Cameras",
			"Proximity to Public Transport","Near Malls","Near Hospitals","Near Public Markets","Near in Churches","Near in Schools","Shuttles"
		];
	}

	function listingTypeCollection()
	{
		return [
			"For Sale", "For Rent"
		];
	}

	function tagsCollection()
	{
		return [
			"New", "Pre-Owned", "Renovated", "Fully Furnished", "Bare Unit", "Pre-Sale", "Ready for Occupancy"
		];
	}

	public function downloadData(int $account_id = null)
	{
		$columns = Property::columns();
		unset($columns['join']);
		unset($columns['fields']['account']);
		unset($columns['fields']['address']);
		unset($columns['fields']['modified_at']);
		unset($columns['fields']['created_at']);
		unset($columns['fields']['name']);
		unset($columns['fields']['status']);

		$header = array_map("strtoupper", array_keys($columns["fields"]));
		$request = [
			"rows" => 1000
		];
		
		if($account_id != null) {
			$request["account_id"] = $account_id;
		}

		self::$collections = Property::load($columns)->getCollections($request);
		$items = self::$collections->getItems();

		FileSystem::downloadToCSV(data: $items->toArray(), header: $header, file_name: "properties-" . DATE_NOW);
	}

	/**
	 * Calculates the score of a property based on the completeness of its fields.
	 *
	 * The score is calculated by checking if the fields in the $score_fields array
	 * are not empty. If a field is not empty, a score of 1 is added to the total
	 * score. If the field is empty, no score is added.
	 *
	 * The score is then divided by the total number of fields in the $score_fields
	 * array to get the average score.
	 *
	 * @param array $data The data of the property.
	 *
	 * @return float The score of the property.
	 */
	private function calculateScore(array $data): float
	{
		$score_fields = [
			'title', 'tags', 'long_desc', 'category', 'address', 'price', 'reservation',
			'payment_details', 'lot_area', 'thumb_img', 'videos', 'amenities', 'other_details', 'modified_at'
		];

		$score = isset($data['image_score']) ? $data['image_score'] : 0;
		$field_score = 0;

		foreach ($data as $field => $value) {
			if (in_array($field, $score_fields, true)) {
				switch ($field) {
					case 'amenities':
						$field_score += count($value) / 10;
						break;

					case 'address':
						$field_score += (
							(isset($value['municipality']) && $value['municipality'] !== '' ? 1 : 0) +
							(isset($value['barangay']) && $value['barangay'] !== '' ? 1 : 0) +
							(isset($value['street']) && $value['street'] !== '' ? 1 : 0) +
							(isset($value['village']) && $value['village'] !== '' ? 1 : 0)
						) / 6;
						break;

					case 'payment_details':
						$field_score += (
							($value['option_money_duration'] !== '' || $value['option_money_duration'] > 0 ? 1 : 0) +
							($value['payment_mode'] !== '' ? 1 : 0) +
							($value['tax_allocation'] !== '' ? 1 : 0)
						) / 3;
						break;

					case 'other_details':
						$field_score += (
							($value['authority_type'] !== '' ? 1 : 0) +
							($value['authority_to_sell_expiration'] !== '' ? 1 : 0) +
							($value['com_share'] !== '' ? 1 : 0)
						) / 3;
						break;

					default:
						if ($value !== '') {
							$field_score += (1 / count($score_fields));
						}
						break;
				}
			}
		}

		return $score + $field_score;
	}

	function removeFile($file_name, $temp_path, $property_id = 0): void 
	{
		$final_file_path = $this->rootDirectory . "/documents/" . $file_name;
		$temp_file_path = $this->rootDirectory . "$temp_path/" . $file_name;

		if(FileSystem::exists($final_file_path)) {
			FileSystem::remove($final_file_path);
		}else {
			/*
			 * If the file is not found, check if it's a temporary file.
			 * If it is, delete it.
			 */
			FileSystem::remove($temp_file_path);
		}

		if ($_ENV['CACHE_ENABLE']) {
			$property_service = new PropertyService();
			$data = $property_service->getProperty($property_id);

			Cache::removeCache("property-" . $data['name']);
			Cache::removeCache("property-" . $property_id);
		}

	}

}