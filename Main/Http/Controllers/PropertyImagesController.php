<?php

namespace EO\Http\Controllers;

use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;
use EO\Handlers\Exceptions\ResourceNotFoundException;
use EO\Handlers\Exceptions\ValidationException;
use EO\Interfaces\IController as IController;
use EO\Services\PropertyImageService as PropertyImageService;

class PropertyImagesController extends \EO\Http\BaseController
{
	protected PropertyImageService $propertyImageService;

	function __construct() 
	{
		$this->propertyImageService = new PropertyImageService();
	}

	function saveNew($property_id) 
	{
		// Collects all input data from the request and sets the current date and time as the creation date.
		$request = input()->all();
		
		try {
			$this->propertyImageService->create($request);
		} catch (\Exception $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		}

		return $this->handleMessageResponse("Property image created successfully");
	}

	function save(int $id)
	{
		$request_data = input()->all();

		try {
			$this->propertyImageService->update($id, $request_data);
		} catch (\Exception $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		}

		return $this->handleMessageResponse("Property image updated successfully");
	}

	/**
	 * Deletes a property image record from the database and the associated file from the server.
	 *
	 * This function will first check if the request contains a filename in the "filename" key. If it does,
	 * it will attempt to delete the file from the server. If the deletion is successful, it will return
	 * a JSON response with the status and message of the deletion.
	 *
	 * If the request does not contain a filename, it will attempt to delete the property image record
	 * from the database based on the provided id. It will authorize the deletion using the authorize
	 * method from the base controller, and then call the destroy method from the propertyImageService
	 * to perform the actual deletion.
	 *
	 * The function will return a JSON response with the status and message of the deletion.
	 *
	 * @param int $id The ID of the property image to delete
	 * @return JSON A JSON response containing the status and message of the deletion
	 */
	public function delete($id = null)
	{
		$request = input()->all();

		$this->propertyImageService->removeTemporaryImageFile($request['filename']);
		
		$filename = null;
		if(stripos($request['filename'], ".") !== false) {
			$filename = explode(".", $request['filename'])[0];
		}

		if($id == $filename) {
			// File was uploaded recently and cannot proceed to the deletion process.
			return $this->handleMessageResponse("Reload your browser before you can delete saved images!", "error", 2);
		}

		$data = $this->propertyImageService->getPropertyImage($id);
		
		$this->authorize("delete_property_image", $data);

		$this->propertyImageService->destroy($id, $data['property_id']);
		
		return $this->handleMessageResponse("Property image deleted successfully");
	}

	/**
	 * Uploads a new property image file to the server.
	 *
	 * This function handles the file upload process for property images. It uses the propertyImageService
	 * to perform the actual file upload and returns a JSON response containing the upload status and details.
	 *
	 * @return JSON A JSON response containing the upload status and details.
	 */
	function upload() 
	{
		if(!isset($_FILES['browseImage'])) {
			return \EO\View::set("JSON")->bind(
				data: [
					"status" => 2,
					"message" => "No file was uploaded."
				]
			);
		}

		$upload_result = $this->propertyImageService->upload(
			data: $_FILES['browseImage'],
			params: [
				"destination_folder" => "/Public/global_assets/images/properties",
				"temp_url" => CDN . "/images/temporary",
				"final_url" => CDN . "/images/properties",
				"file_type" => "image",
				"file_max_size" => "2MB",
				"multiple" => true
			]
		);

		return \EO\View::set("JSON")->bind(data: $upload_result);
	}

}