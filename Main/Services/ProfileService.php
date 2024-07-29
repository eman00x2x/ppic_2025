<?php

namespace Main\Services;

use Main\Model\ProfileModel as Profile;
use Main\Interfaces\IService as IService;

class ProfileService implements IService
{

    private Profile $profile;

    function __construct() {
        $this->profile = new Profile();
    }

    function list(array $request, string $target_url) {

		$this->profile
			// Apply filters based on the request parameters.
			->filter(request: $request)
			// Arrange the profiles based on the request parameters default to updated_at in descending order.
			->sort(request: $request, sorting: ["updated_at" => "DESC"])
			// Retrieve the paginated list of profiles.
			->getList(
				// Determine the page number from the request, default to 1 if not provided.
				page: ($request['page'] ?? 1),
				// Determine the limit of profiles per page from the request, default to 20 if not provided.
				limit: ($request['rows'] ?? 20),
				// The target URL for pagination links.
				url: $target_url
			);

		if($this->profile->results) {
			for($i = 0; $i < count($this->profile->results); $i++) {
				$this->profile->results[$i]['fullname'] = $this->profile->results[$i]['name']['firstname']." ".$this->profile->results[$i]['name']['lastname'];
			}
		}

		return $this->profile;

    }

	function getByAccountId($account_id) {
		$this->profile->getBy("account_id", $account_id);
		return $this->get($this->profile->column['profile_id']);
	}

    function get(int $id) {
		
		if(!isset($this->profile->column['profile_id'])) {
        	$this->profile->getId($id);
		}

		if(isset($this->profile->column['profile_id'])) {

			$this->profile->column['name'] = [
				"firstname" 	=> ($this->profile->column['name']['firstname'] ?? ""),
				"lastname" 		=> ($this->profile->column['name']['lastname'] ?? ""),
				"middlename" 	=> ($this->profile->column['name']['middlename'] ?? ""),
				"suffix" 		=> ($this->profile->column['name']['suffix'] ?? ""),
				"nickname" 		=> ($this->profile->column['name']['nickname'] ?? "")
			];

			$this->profile->column['fullname'] = $this->profile->column['name']['firstname']." ".$this->profile->column['name']['lastname'];

			$this->profile->column['birthdate'] = $this->profile->column['birthdate'] ?? date("Y-m-d", $this->profile->column['birthdate']);

			if(isset($this->profile->column['skills']) && $this->profile->column['skills'] == "") { $this->profile->column['skills'] = [""]; }
			if(isset($this->profile->column['socials']) && $this->profile->column['socials'] == "") { $this->profile->column['socials'] = [""]; }

			if($this->profile->column['affiliation'] == "") { 
				$this->profile->column['affiliation'] = [ 
					0 => [
						"organization" => "",
						"title" => "",
						"description" => "",
						"date" => [
							"from" => "",
							"to" => ""
						]
					]
				];
			}

			if($this->profile->column['education'] == "") { 
				$this->profile->column['education'] = [ 
					0 => [
						"school" => "",
						"degree" => "",
						"date" => [
							"from" => "",
							"to" => ""
						]
					]
				];
			}

		}

		return $this->profile;

    }

    function create(array $data) {

		$data["updated_at"] = DATE_NOW;
		$result = $this->profile->saveNew(data: $data);

        if($result['status'] == 2) {
			$response = [
				"status" => 2,
				"message" => $result['message']
			];
		}else {

			$response = [
				"status" => 1,
				"message" => "Successfully save!"
			];

		}

    }

    function update(int $id, array $data) {

		$data["updated_at"] = DATE_NOW;

		$data['name'] = [
			"firstname" 	=> ($data['firstname'] ?? ""),
			"lastname" 		=> ($data['lastname'] ?? ""),
			"middlename" 	=> ($data['middlename'] ?? ""),
			"suffix" 		=> ($data['suffix'] ?? ""),
			"nickname" 		=> ($data['nickname'] ?? "")
		];

        $response = $this->profile->getId(id: $id);

        if($response) {

            $result = $this->profile->where([
				"profile_id" => $id
			])->save(data: $data);

			return [
				"status" => $result['status'],
				"message" => $result['message']
			];

        }

        return [
			"status" => 2,
            "message" => "profile not found!"
		];

    }

    function destroy($id) {

        $data = $this->profile->getId(id: $id);

        if($data) {

            $this->profile->delete(id: $id, field: "profile_id");

            return [
                "status" => 1,
                "message" => "profile deleted!"
            ];

        }

        return [
			"status" => 2,
            "message" => "profile not found!"
		];

    }

}