<?php

namespace Main\Services;

use Main\Controller as Controller;
use Main\Interfaces\IService as IService;
use Main\Service as Service;
use Main\Model\PropertyModel as Property;

class PropertyService extends Service implements IService
{

    public Property $property;

    function __construct() {
        $this->property = $this->getModel("Property");
    }

    function list(array $request, string $target_url) {

		$this->property
			->select([
				"property_id", "properties.name", "properties.title", "thumb_img", "properties.address", "reservation", "price", "bedroom", "bathroom", "floor_area", "lot_area", "type", "category",
				"profile" => [
					"accounts_profile.name (names)",
					"accounts_profile.profile_image",
					"accounts_profile.contact_number",
					"accounts.email"
				]
            ])
			->join("accounts_profile", "account_id", "account_id")
			->join("accounts", "account_id", "account_id")
			// Apply filters based on the request parameters.
			->filter(request: $request)
			// Arrange the property based on the request parameters default to created_at in descending order.
			->sort(request: $request, sorting: ["property_id" => "ASC"])
			// Retrieve the paginated list of property.
			->getList(
				// Determine the page number from the request, default to 1 if not provided.
				page: ($request['page'] ?? 1),
				// Determine the limit of property per page from the request, default to 20 if not provided.
				limit: ($request['rows'] ?? 20),
				// The target URL for pagination links.
				url: $target_url
			);

		if($this->property->results) {
			for($i = 0; $i < $this->property->page['limit']; $i++) {
				$this->property->results[$i]['short_title'] = $this->helper("nice_trim", ["string" => $this->property->results[$i]['title'], "max_length" => 60]);
				$this->property->results[$i]['url'] = url("web.view.property", ["id" => $this->property->results[$i]['property_id'], "name" => $this->property->results[$i]['name'] ]);
				$this->property->results[$i]['profile']['full_name'] = $this->property->results[$i]['profile']['names']['firstname']." ".$this->property->results[$i]['profile']['names']['lastname'];
				$this->property->results[$i]['full_address']= $this->property->results[$i]['address']['municipality']." ".$this->property->results[$i]['address']['province'];
			}
		}

		return $this->property;

    }

    function get(int $id) {

		foreach($this->property->fields as $field) {
			$columns[] = "properties." . $field;
		}

        $this->property->select(array_merge($columns, [
				"profile" => [
					"accounts_profile.name (names)",
					"accounts_profile.profile_image",
					"accounts_profile.contact_number",
					"accounts.email"
				]
            ]))
			->join("accounts_profile", "account_id", "account_id")
			->join("accounts", "account_id", "account_id")
			->getId($id);

		$this->property->column['url'] = url("web.view.property", ["name" => $this->property->column['name'], "id" => $this->property->column['property_id']]);
		$this->property->column['short_description'] = $this->helper("nice_trim", ["string" => str_replace(["\r", PHP_EOL], [" ", " "], strip_tags($this->property->column['long_desc'])), "max_length" => 80]);
			
		return $this->property;
    }

    function create(array $data) {

		$result = $this->property->saveNew(data: $data);

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

        $response = $this->property->getId(id: $id);

        if($response) {

            $result = $this->property->where([
				"property_id" => $id
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