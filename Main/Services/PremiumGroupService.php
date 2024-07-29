<?php

namespace Main\Services;

use Main\Interfaces\IService as IService;
use Main\Model\PremiumGroupModel as PremiumGroup;

class PremiumGroupService implements IService
{

    public PremiumGroup $premiumGroup;

    function __construct() {
        $this->premiumGroup = new PremiumGroup();
    }

    function list(array $request, string $target_url) {

		$this->premiumGroup
			// Apply filters based on the request parameters.
			->filter(request: $request)
			// Arrange the premiumGroup based on the request parameters default to created_at in descending order.
			->sort(request: $request, sorting: ["created_at" => "DESC"])
			// Retrieve the paginated list of premiumGroup.
			->getList(
				// Determine the page number from the request, default to 1 if not provided.
				page: ($request['page'] ?? 1),
				// Determine the limit of premiumGroup per page from the request, default to 20 if not provided.
				limit: ($request['rows'] ?? 20),
				// The target URL for pagination links.
				url: $target_url
			);

		return $this->premiumGroup;

    }

    function get(int $id) {

        $this->premiumGroup->getId($id);
		return $this->premiumGroup;

    }

    function create(array $data) {

		$result = $this->premiumGroup->saveNew(data: $data);

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

        $response = $this->premiumGroup->getId(id: $id);

        if($response) {

            $result = $this->premiumGroup->where([
				"premium_group_id" => $id
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
            "message" => "Group not found!"
		];

    }

    function destroy($id) {

        $data = $this->premiumGroup->getId(id: $id);

        if($data) {

			$this->premiumGroup->where([
				"premium_group_id" => $id
			])->delete();

            return [
                "status" => 1,
                "type" => "success",
                "message" => "Group deleted!"
            ];

        }

        return [
			"status" => 2,
			"type" => "error",
            "message" => "Group not found!"
		];

    }

}