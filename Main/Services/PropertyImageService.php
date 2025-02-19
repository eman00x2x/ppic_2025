<?php

namespace EO\Services;

use Pecee\Http\Exceptions\MalformedUrlException;
use EO\Handlers\Exceptions\ValidationException;
use EO\Handlers\Exceptions\ResourceNotFoundException;
use EO\Service as Service;
use EO\Facades\FileSystemFacade as FileSystem;
use EO\Facades\CacheFacade as Cache;
use EO\Model\PropertyImageModel as PropertyImage;
use EO\Services\PropertyService;

class PropertyImageService extends Service
{
	private $rootDirectory = ROOT . "/Public/global_assets/images";

	function __construct()
	{
		parent::__construct();
	}

	function getPropertyImages(array $request): array
	{
		try {
			self::$collections = PropertyImage::getCollections($request);
			$items = self::$collections->getItems();
			
			return $items->toArray();
		}
		// Catch any exceptions of type MalformedUrlException that are thrown
		catch (MalformedUrlException $e) {
			// Throw a new exception of type ResourceNotFoundException with a message that includes the message from the caught exception
			throw new ResourceNotFoundException("Resource Not Found! " . $e->getMessage());
		}

		return $items->toArray();
	}

	function getPropertyImage($id): array 
	{
		self::$collections = PropertyImage::getId($id);
		$items = self::$collections->getItems();

		if($items->isNotEmpty()) {

			$images = $items->first()->toArray();

			// If caching is enabled, cache the retrieved account data
			if ($_ENV['CACHE_ENABLE']) {
				Cache::setData("propertyImage-$id", $images);
			}

			return $images;
		}else {
			throw new ResourceNotFoundException("Resource Not Found! Image ID: $id");
		}
		
		return $items->toArray();
	}

	function create(array $data)
	{
		$final_url = FileSystem::move(
			$this->rootDirectory . "/temporary/" . $data['filename'],
			$this->rootDirectory . "/properties/" . $data['filename']
		);

		FileSystem::move(
			$this->rootDirectory . "/temporary/thumb-" . $data['filename'],
			$this->rootDirectory . "/properties/thumb-" . $data['filename']
		);

		$data['filename'] = basename($final_url);
		$data['url'] = CDN . "/images/properties/" . $final_url;
		$data['created_at'] = DATE_NOW;
		
		PropertyImage::create(data: $data);
	}

	function update(int $id, array $data): void 
	{
		$this->getPropertyImage(id: $id);
		PropertyImage::modify($data, $id);
	}

	function destroy($id, $property_id): void 
	{
		$images = $this->getPropertyImage(id: $id);
		$image_path = $this->rootDirectory . "/properties/" . $images['filename'];
		$thumb_image_path = $this->rootDirectory . "/properties/thumb-" . $images['filename'];

		if(FileSystem::exists($image_path)) {
			FileSystem::remove($image_path);
		}

		if(FileSystem::exists($thumb_image_path)) {
			FileSystem::remove($thumb_image_path);
		}

		/** FOR PHILPROPERTIES IMAGE FOLDER */
		$image_path = $this->rootDirectory . "/listings/" . $images['filename'];
		if(FileSystem::exists($image_path)) {
			FileSystem::remove($image_path);
		}

		PropertyImage::delete(["image_id" => $id]);

		if ($_ENV['CACHE_ENABLE']) {
			$property_service = new PropertyService();
			$data = $property_service->getProperty($property_id);

			Cache::removeCache("property-" . $data['name']);
			Cache::removeCache("property-" . $property_id);
			Cache::removeCache("propertyImage-$id");
		}
	}

	function destroyByPropertyId($property_id): void
	{
		$images = $this->getByPropertyId($property_id);
		
		foreach($images as $result) {
			if(isset($result['image_id'])) {
				FileSystem::remove($this->rootDirectory . "/properties/" . $result['filename']);
				/** FOR PHILPROPERTIES IMAGE FOLDER */
				FileSystem::remove($this->rootDirectory . "/listings/" . $result['filename']);
			}
			Cache::removeCache("propertyImage-" . $result['image_id']);
		}

		PropertyImage::delete(["property_id" => $property_id]);

		if ($_ENV['CACHE_ENABLE']) {
			$property_service = new PropertyService();
			$data = $property_service->getProperty($property_id);

			Cache::removeCache("property-" . $data['name']);
			Cache::removeCache("property-" . $property_id);
		}
	}

	function getTotalImages(int $property_id) 
	{
		self::$collections = PropertyImage::select([
			"total" => PropertyImage::raw("COUNT(property_id)")
		])->where([
			"property_id" => $property_id
		])->limit(10000)->get();

		$items = self::$collections->getItems();

		return $items->toArray();
	}


	function removeTemporaryImageFile($filename): void 
	{
		$temporary_image_path = $this->rootDirectory . "/temporary/{$filename}";
		FileSystem::remove($temporary_image_path);
	}

	function processUploadedImages(array $uploaded_images, int $property_id): void 
	{
		foreach ($uploaded_images as $uploaded_image) {
			$temporary_image_path = $this->rootDirectory . "/temporary/{$uploaded_image['filename']}";
			
			if (file_exists($temporary_image_path)) {
				$uploaded_image['property_id'] = $property_id;
				$this->create($uploaded_image);
			} else {
				$final_image_path = $this->rootDirectory . "/properties/{$uploaded_image['filename']}";
				if (!file_exists($final_image_path) && isset($uploaded_image['image_id'])) {
					$this->destroy($uploaded_image['image_id'], $property_id);
				}
			}
		}
	}

	function getCurrentImageScore($property_id): float
	{
		$data = $this->getPropertyImages(["property_id" => $property_id]);

		if(!empty($data)) {
			return $this->computeImageScore($data);
		}
		return 0;
	}

	function computeImageScore(array $data): float
	{
		$image_score = 0;
		if(!empty($data)) {
			foreach($data as $upload) {
				$image_score += ($upload['width'] / 1024) + ($upload['height'] / 1024);
			}
		}
		return $image_score;
	}
	
}