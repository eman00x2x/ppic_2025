<?php

namespace EO\Services;

use Pecee\Http\Exceptions\MalformedUrlException;
use EO\Handlers\Exceptions\ValidationException;
use EO\Handlers\Exceptions\ResourceNotFoundException;
use EO\Model;
use EO\Service as Service;
use EO\Facades\CacheFacade as Cache;
use EO\Database\DataModel;
use EO\Model\ProfileModel as Profile;

class ProfileService extends Service
{
	function __construct() {
		parent::__construct();
		Model::get(Profile::class);
		
		$this->validator->setConstraints([
			"firstname" => [
				"length" => [ "min" => 2, "max" => 100 ],
				"required" => true,
				"restrictedWords" => true
			],
			"lastname" => [
				"length" => [ "min" => 2, "max" => 100 ],
				"required" => false,
				"restrictedWords" => true
			],
			"birthdate" => [ 
				"required" => true,
				"date" => true
			],
			"mobileNumber" => [
				"required" => false,
				"mobileNumber" => true
			]
		]);
	}

	function getProfiles(array $request): DataModel {
		try {
			// Call the getAll method on the $this->account object, passing the $request array as an argument
			Profile::$model->getCollections($request);

			// Check if the $this->account object has more than 0 rows
			if (Profile::$model->getNumRows() > 0) {
				// Iterate over the results property of the $this->account object
				foreach (Profile::$model->getResults() as &$result) {
					// Call the formatResultData method on each result and assign the returned value back to the result
					$data[] = $this->formatResultData($result);
				}
				Profile::$model->setResults($data);
			}
		} catch (MalformedUrlException $e) {
			// Throw a new exception of type ResourceNotFoundException with a message that includes the message from the caught exception
			throw new ResourceNotFoundException("Resource Not Found! " . $e->getMessage());
		}

		return Profile::$model;
	}

	function getByAccountId($account_id): DataModel {
		$results = Profile::$model->getBy("account_id", $account_id);
		return $this->getProfile($results['profile_id']);
	}

	function getProfile(int $id): DataModel {
		if ($_ENV['CACHE_ENABLE'] && ($data = Cache::getData("profile-$id"))) {
			Profile::$model->setNumRows(1);
			Profile::$model->setResults($data);
			return Profile::$model;
		}
		
		$results = Profile::$model->getId($id);
		
		if(Profile::$model->getNumRows() > 0) {
			$data = $this->formatResultData($results);
			Profile::$model->setResults($data);
			
			if ($_ENV['CACHE_ENABLE']) {
				Cache::setData("profile-$id", $data);
			}
		}else {
			throw new ResourceNotFoundException("Resource Not Found! Profile ID: $id");
		}

		return Profile::$model;
	}

	function create(array $data): DataModel {

		$data["updated_at"] = DATE_NOW;
		$data['name'] = [
			"firstname" 	=> ($data['firstname'] ?? ""),
			"lastname" 		=> ($data['lastname'] ?? ""),
			"middlename" 	=> ($data['middlename'] ?? ""),
			"suffix" 		=> ($data['suffix'] ?? ""),
			"nickname" 		=> ($data['nickname'] ?? "")
		];

		if (isset($data['profile_image'])) {
			$data['profile_image'] = $this->moveFile(basename($data['profile_image']), "/images/temporary", "/images/profiles");
		}

		$id = Profile::$model->new($data);
		return Profile::$model;
		
	}

	function update(int $id, array $profileData): DataModel {
		$this->getProfile($id);

		$updatedAt = DATE_NOW;
		$nameData = [
			'firstname' => $profileData['firstname'] ?? '',
			'lastname' => $profileData['lastname'] ?? '',
			'middlename' => $profileData['middlename'] ?? '',
			'suffix' => $profileData['suffix'] ?? '',
			'nickname' => $profileData['nickname'] ?? '',
		];

		Profile::$model->renew(array_merge($profileData, ['updated_at' => $updatedAt, 'name' => $nameData]), $id);
		return Profile::$model;
	}
	
	function destroy($id): DataModel {
		$this->getProfile(id: $id);

		Profile::$model->delete(["profile_id" => $id]);

		if ($_ENV['CACHE_ENABLE']) {
			Cache::removeCache("profile-$id");
		}

		return Profile::$model;
	}

	private function formatResultData(array $data): array  {
		$data['name'] = [
			"firstname" => $data['name']['firstname'] ?? "",
			"lastname" => $data['name']['lastname'] ?? "",
			"middlename" => $data['name']['middlename'] ?? "",
			"suffix" => $data['name']['suffix'] ?? "",
			"nickname" => $data['name']['nickname'] ?? "",
		];

		$data['birthdate'] = $data['birthdate'] ?? "";

		if(isset($data['skills']) && $data['skills'] == "") { $data['skills'] = [""]; }
		if(isset($data['socials']) && $data['socials'] == "") { $data['socials'] = [""]; }

		if($data['affiliation'] == "") { 
			$data['affiliation'] = [ 
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

		if($data['education'] == "") { 
			$data['education'] = [ 
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

		return $data;
	}

}