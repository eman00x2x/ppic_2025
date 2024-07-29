<?php

namespace Main\Services;

use Main\Interfaces\IService as IService;
use Main\Model\PropertyImageModel as PropertyImage;

class PropertyImageService implements IService
{

    public PropertyImage $propertyImage;

    function __construct() {
        $this->propertyImage = new PropertyImage();
    }

    function list(array $request, string $target_url) {

		$this->propertyImage
			// Apply filters based on the request parameters.
			->filter(request: $request)
			// Arrange the propertyImage based on the request parameters default to created_at in descending order.
			->sort(request: $request, sorting: ["created_at" => "DESC"])
			// Retrieve the paginated list of propertyImage.
			->getList(
				// Determine the page number from the request, default to 1 if not provided.
				page: ($request['page'] ?? 1),
				// Determine the limit of propertyImage per page from the request, default to 20 if not provided.
				limit: ($request['rows'] ?? 20),
				// The target URL for pagination links.
				url: $target_url
			);

		return $this->propertyImage;

    }

    function get(int $id) {
        $this->propertyImage->getId($id);
		return $this->propertyImage;
    }

	function getByPropertyId($property_id) {
		return $this->propertyImage->getByPropertyId($property_id);
    }

    function create(array $data) {

		$result = $this->propertyImage->saveNew(data: $data);

        if($result['status'] == 2) {
			return [
				"status" => 2,
				"type" => "error",
				"message" => $result['message']
			];
		}else {
			return [
				"status" => 1,
				"type" => "success",
				"message" => "Successfully save!"
			];
		}

    }

    function update(int $id, array $data) {

        $response = $this->propertyImage->getId(id: $id);

        if($response) {

            $result = $this->propertyImage->where([
				"image_id" => $id
			])->save(data: $data);

			return [
				"status" => $result['status'],
				"type" => "success",
				"message" => $result['message']
			];

        }

        return [
			"status" => 2,
			"type" => "error",
            "message" => "Property not found!"
		];

    }

    function destroy($id) {

        $data = $this->property->getId(id: $id);

        if($data) {

            $this->property->where([
				"property_id" => $id
			])->delete();

            return [
                "status" => 1,
                "type" => "success",
                "message" => "Property deleted!"
            ];

        }

        return [
			"status" => 2,
			"type" => "error",
            "message" => "Property not found!"
		];

    }

}