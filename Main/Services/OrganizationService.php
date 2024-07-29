<?php

namespace Main\Services;

use Main\Interfaces\IService as IService;
use Main\Model\OrganizationModel as Organization;

class OrganizationService implements IService
{

    public Organization $organization;

    function __construct() {
        $this->organization = new Organization();
    }

    function list(array $request, string $target_url) {

		$this->organization
			// Apply filters based on the request parameters.
			->filter(request: $request)
			// Arrange the organization based on the request parameters default to created_at in descending order.
			->sort(request: $request, sorting: ["created_at" => "DESC"])
			// Retrieve the paginated list of organizations.
			->getList(
				// Determine the page number from the request, default to 1 if not provided.
				page: ($request['page'] ?? 1),
				// Determine the limit of organizations per page from the request, default to 20 if not provided.
				limit: ($request['rows'] ?? 20),
				// The target URL for pagination links.
				url: $target_url
			);

		return $this->organization;

    }

    function get(int $id) {

        $this->organization->getId($id);

		return $this->organization;

    }

    function create(array $data) {

        
		$result = $this->organization->saveNew(data: $data);

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

        $response = $this->organization->getId(id: $id);

        if($response) {

            $result = $this->organization->where([
				"organization_id" => $id
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
            "message" => "Organization not found!"
		];

    }

    function destroy($id) {

        $data = $this->organization->getId(id: $id);

        if($data) {

            $this->organization->where([
				"organization_id" => $id
			])->delete();

            return [
                "status" => 1,
                "type" => "success",
                "message" => "Organization deleted!"
            ];

        }

        return [
			"status" => 2,
			"type" => "error",
            "message" => "Organization not found!"
		];

    }

}