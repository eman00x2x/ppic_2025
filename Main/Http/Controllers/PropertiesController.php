<?php

namespace EO\Http\Controllers;

use Pecee\Exceptions\InvalidArgumentException;
use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;
use EO\Handlers\Exceptions\ResourceNotFoundException;
use EO\Handlers\Exceptions\ValidationException;
use EO\Interfaces\IController as IController;
use EO\Services\PropertyService as PropertyService;
use EO\Services\PropertyImageService as PropertyImageService;
use EO\Auth\Auth;
use EO\View;

class PropertiesController extends \EO\Http\BaseController implements IController
{
	protected PropertyService $propertyService;
	protected PropertyImageService $propertyImageService;

	function __construct() 
	{
		$this->propertyService = new PropertyService();
		$this->propertyImageService = new PropertyImageService();
	}

	function index() 
	{
		// Check if there are any request parameters
		$request = input()->all() ?? [];

		if(!Auth::isAdmin()) {
			$request["account_id"] = Auth::user()->id;
		}

		$data['collections'] = [
			"listing_types" => $this->propertyService->listingTypeCollection(),
			"categories" => $this->propertyService->categoriesCollection(),
			"amenities" => $this->propertyService->amenitiesCollection()
		];
		
		$data['properties'] = $this->propertyService->getProperties(request: $request);

		return View::set(path: "/authenticated/properties/index.php")->bind(data: $data);
	}

	function add() 
	{
		$data['collections'] = [
			"listing_types" => $this->propertyService->listingTypeCollection(),
			"tags" => $this->propertyService->tagsCollection(),
			"amenities" => $this->propertyService->amenitiesCollection(),
			"categories" => $this->propertyService->categoriesCollection(),
			"commission_sharing" => ["25", "50", "75"],
			"authority_types" => ["N/A","Non-Exclusive Authority To Sell", "Exclusive Authority To Sell"]
		];

		$data['thumb_img'] = CDN . "/images/item_default.jpg";

		return View::set(path: "/authenticated/properties/add.php")->bind(data: $data);
	}

	function edit($id) 
	{
		$data = $this->propertyService->getProperty($id);

		$this->authorize("edit_properties", $data);
		
		$data['collections'] = [
			"listing_types" => $this->propertyService->listingTypeCollection(),
			"tags" => $this->propertyService->tagsCollection(),
			"amenities" => $this->propertyService->amenitiesCollection(),
			"categories" => $this->propertyService->categoriesCollection(),
			"commission_sharing" => ["25", "50", "75"],
			"authority_types" => ["N/A","Non-Exclusive Authority To Sell", "Exclusive Authority To Sell"]
		];

		return View::set(path: "/authenticated/properties/edit.php")->bind(data: $data);
	}

	function confirmSelection()
	{
		$request = input()->all();

		$property_ids = $request['ids'];
		$action = $request['action'];
		$action_value = $request['action_value'];

		$this->authorize($action, Auth::user()->account);

		$options = [
			"set_category" => [
				"url" =>url("PropertiesController@updateCategory"),
				"message" => "You are about to move " . count($property_ids) . " property(ies) to category $action_value . Are you sure do you want to continue the action?"
			],
			"set_status" => [
				"url" => url("PropertiesController@updateStatus"),
				"message" => "You are about to set " . ($action_value == 1 ? "Available" : "Sold") . " " . count($property_ids) . " property(ies). Are you sure do you want to continue the action?"
			],
			"delete" => [
				"url" => url("PropertiesController@updateStatus"),
				"message" => "You are about to Delete (Permanent) " . count($property_ids) . " property(ies). All data related to these property(ies) will be permanently deleted and this action is ireversible, Are you sure do you want to continue the deletion of these property(ies)?"
			]
		];

		$filter['property_id'] = $property_ids;
		if(!Auth::isAdmin()) {
			$filter["account_id"] = Auth::user()->id;
		}

		$property = $this->propertyService->getProperties($filter);

		$data = [
			"properties" => $property,
			"ids" => implode(",", $property_ids),
			"action" => $action,
			"action_value" => $action_value,
			"url" => $options[$action]['url'],
			"message" => $options[$action]['message']
		];

		return View::set(path: "/authenticated/properties/confirmSelection.php")->bind(data: $data);
	}


	function updateStatus()
	{
		$request = input()->all();
		$property_ids = explode(",", $request['ids']);
		$status = (int)$request['action_value'];

		try {
			$this->propertyService->updateStatus($property_ids, $status);
		} catch (InvalidArgumentException $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		} catch (\Exception $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		}

		$statuses = [
			1 => "Available",
			2 => "Sold",
			3 => "Removed"
		];

		return $this->handleMessageResponse("Successfully " . $statuses[$status] . " properties!");
	}


	function updateCategory()
	{
		$request = input()->all();
		$property_ids = explode(",", $request['ids']);
		$category = $request['action_value'];

		try {
			$this->propertyService->changeCategory($property_ids, $category);
		} catch (InvalidArgumentException $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		} catch (\Exception $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		}

		return $this->handleMessageResponse("Successfully moved properties to category $category!");
	}

	/**
	 * Save new property record in the database
	 * 
	 * @return JSON A JSON containing the status and message of the operation
	 */
	public function saveNew()
	{
		$data = input()->all();
		
		try {
			if (isset($data['upload'])) {
				$data['image_score'] = $this->propertyImageService->computeImageScore($data['upload']);
			}

			if (isset($data['amenities'])) {
				$data['amenities'] = array_values($data['amenities']);
			}

			if (!isset($data['documents'])) {
				$data['documents'] = [];
			}

			$property_id = $this->propertyService->create($data);
		} catch (ValidationException $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		} catch (\Exception $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		}

		if (isset($data['upload'])) {
			$this->propertyImageService->processUploadedImages($data['upload'], $property_id);
		}

		return $this->handleMessageResponse("Property created successfully");
	}

	/**
	 * Updates an existing property record in the database
	 * @param mixed $id The ID of the property to update
	 * @return JSON A JSON containing the status and message of the operation
	 */
	public function save($id)
	{
		$data = input()->all();

		if (isset($data['upload'])) {
			$this->propertyImageService->processUploadedImages($data['upload'], $id);
		}
		
		if(!isset($data['documents'])) {
			$data['documents'] = [];
		}

		if(isset($data['amenities'])) {
			$data['amenities'] = array_values($data['amenities']);
		}

		try {
			$this->propertyService->update($id, $data);
		} catch (ValidationException $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		} catch (\Exception $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		}

		return $this->handleMessageResponse("Property updated successfully");
	}

	/**
	 * Deletes an existing property record in the database.
	 * @param mixed $property_id The ID of the property to delete
	 * @return JSON A JSON containing the status and message of the operation
	 */
	public function delete($property_id = null)
	{
		$data = $this->propertyService->getProperty($property_id);
		$this->authorize("delete_property", $data);

		$requestData = input()->all();

		if (isset($requestData['delete'])) {
			$this->propertyService->destroy($property_id);
			return $this->handleMessageResponse("Property deleted successfully");
		}

		return View::set(path: "/authenticated/properties/delete.php")->bind(data: $data);
	}

	public function download(): void
	{
		$account_id = Auth::isAdmin() ? null : Auth::user()->id;
		$this->propertyService->downloadData($account_id);
	}

	/** FOR DOCUMENTS ONLY */
	function upload()
	{
		$upload_result = $this->propertyService->upload(
			data: $_FILES['browseFile'],
			params: [
				"destination_folder" => "/Public/global_assets/documents",
				"temp_url" => CDN."/images/temporary",
				"final_url" => CDN."/documents",
				"file_type" => "pdf",
				"file_max_size" => "2MB",
				"multiple" => true
			]
		);

		return View::set("JSON")->bind(data: $upload_result);
	}

	function removeDocument($property_id, $filename)
	{
		$this->propertyService->removeFile($filename, "/images/temporary", $property_id);
		return $this->handleMessageResponse("Document removed successfully!");
	}
	/** END DOCUMENTS */


	/* function createPropertiesThumbnail()
	{
		$rootDirectory = ROOT . "/Public/global_assets/images/listings";
		$thumbnailDirectory = $rootDirectory . "/thumbnail";

		$data['properties'] = $this->propertyService->getProperties(["rows" => 1000]);

		foreach($data['properties'] as $property) {
			$thumb_img = explode("/", $property['thumb_img']);
			$filename = end($thumb_img);

			$file = $rootDirectory . "/" . $filename;

			if (file_exists($file)) {

				$w = 300;
				$h = 300;

				list($width, $height, $type) = getimagesize($file);
				$r = $width / $height;

				if ($w / $h > $r) {
					$newwidth = $h * $r;
					$newheight = $h;
				} else {
					$newheight = $w / $r;
					$newwidth = $w;
				}

				switch ($type) {
					case IMAGETYPE_JPEG:
						$src = imagecreatefromjpeg($file);
						break;
					case IMAGETYPE_PNG:
						$src = imagecreatefrompng($file);
						// Handle transparency for PNG images
						imagealphablending($src, false);
						imagesavealpha($src, true);
						break;
					default:
						continue; // Skip unsupported image types
				}

				$dst = imagecreatetruecolor($newwidth, $newheight);

				if ($type == IMAGETYPE_PNG) {
					// Handle transparency for PNG images
					imagealphablending($dst, false);
					imagesavealpha($dst, true);
					$transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
					imagefilledrectangle($dst, 0, 0, $newwidth, $newheight, $transparent);
				}

				imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

				// Save the resized image to the thumbnail directory
				$thumbnailFile = $thumbnailDirectory . "/" . $filename;

				switch ($type) {
					case IMAGETYPE_JPEG:
						imagejpeg($dst, $thumbnailFile);
						break;
					case IMAGETYPE_PNG:
						imagepng($dst, $thumbnailFile);
						break;
				}

				imagedestroy($src);
				imagedestroy($dst);


			}

		}

	}

	function updatePropertiesThumbnail()
	{
		$rootDirectory = ROOT . "/Public/global_assets/images/listings";
		$thumbnailDirectory = $rootDirectory . "/thumbnail";

		$data['properties'] = $this->propertyService->getProperties(["rows" => 1000]);

		foreach ($data['properties'] as $property) {
			$thumb_img = explode("/", $property['thumb_img']);
			$filename = end($thumb_img);

			$thumbnail = $thumbnailDirectory . "/" . $filename;

			if (file_exists($thumbnail)) {
				$property_id = $property['property_id'];
				$new_data = $property;
				$new_data['thumb_img'] = "https://images.philproperties.ph/listings/thumbnail/" . $filename;
				$this->propertyService->update($property_id, $new_data);
			}

		}
	} */
}