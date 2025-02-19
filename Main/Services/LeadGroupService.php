<?php

namespace EO\Services;

use Pecee\Http\Exceptions\MalformedUrlException;
use EO\Handlers\Exceptions\ValidationException;
use EO\Handlers\Exceptions\ResourceNotFoundException;
use EO\Handlers\Exceptions\MailerException;
use EO\Service as Service;
use EO\Interfaces\IModel;
use EO\Model\LeadGroupModel as LeadGroup;

class LeadGroupService extends Service
{
	function __construct() 
	{
		parent::__construct();
		
		$this->validator->setConstraints([
			"name" => [
				"required" => true,
				"restrictedWords" => true
			]
		]);
	}

	function getLeadGroups(array $request = []): array 
	{
		$this->buildFilters($request);
		try {
			self::$collections = LeadGroup::getCollections($request);
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

	function getLeadGroup(int $id): array 
	{
		self::$collections = LeadGroup::getId($id);
		$items = self::$collections->getItems();

		if($items->isNotEmpty()) {
			return $items->map(function($data, $key) {
					return $this->formatResultData($data);
				})->first()->toArray();
		}else {
			throw new ResourceNotFoundException("Resource Not Found! Lead ID: $id");
		}
		
		return $items->toArray();
	}

	function create(array $data): int 
	{
		$data['created_at'] = DATE_NOW;

		$validated_data = $this->validateInput($data);

		try {
			$id = LeadGroup::create(data: $validated_data);

			$this->log([
				'type' => 'info',
				'message' => "Lead Group creation with ID: $id succeeded",
				'data' => $validated_data
			]);
		} catch (\Exception $e) {
			$this->log([
				"type" => "warning",
				"message" => "Lead Group creation failed",
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
		$this->getLeadGroup(id: $id);

		$validated_data = $this->validateInput($data);

		try {
			LeadGroup::modify($validated_data, $id);
			$this->log([
				'type' => 'info',
				'message' => "Lead Group update with ID: $id succeeded",
				'data' => $validated_data
			]);
		} catch (\Exception $e) {
			$this->log([
				"type" => "warning",
				"message" => "Lead Group update with ID: $id failed",
				"data" => [
					"error" => $e->getMessage(),
					"data" => $validated_data
				]
			]);
			throw new \Exception($e->getMessage());
		}

		return $validated_data;
	}

	function destroy($id): void 
	{
		$data = $this->getLeadGroup(id: $id);

		LeadGroup::delete(["lead_group_id" => $id]);

		$this->log([
			"type" => "info", 
			"message" => "Lead Group deleted with ID: $id succeeded",
			"data" => $data
		]);
	}

	public function destroyLeadGroups(array $ids): void
	{
		self::$collections = LeadGroup::getCollections(["lead_group_id" => $ids]);
		$items = self::$collections->getItems();

		$deleted_lead_groups = [];
		
		foreach($items->toArray() as $result) {
			$deleted_lead_groups[] = $result;
		}

		LeadGroup::delete(["lead_group_id" => $ids]);

		$this->log([
			"type" => "info", 
			"message" => "Lead Groups deleted succeeded",
			"data" => [
				"ids" => $ids,
				"deleted" => $deleted_lead_groups
			]
		]);
	}

	private function buildFilters(array &$request): void 
	{
		if (isset($request['search'])) {
			$request["OR"] = [
				"name[~]" => $request['search'],
			];
			unset($request['search']);
		}

		if(isset($request['created_at'])) {
			if(isset($request['created_at']['from']) && !isset($request['created_at']['to'])) {
				$request['AND']['created_at[>=]'] = strtotime($request['created_at']['from']);
			}

			if(isset($request['created_at']['from']) &&  isset($request['created_at']['to'])) {
				$request['AND']['created_at[<>]'] = [strtotime($request['created_at']['from']), strtotime($request['created_at']['to'])];
			}

			unset($request['created_at']);
		}
	}

	private function formatResultData(IModel $data): IModel 
	{
		$data->created_date = date("d M Y", $data->created_at);
		return $data;
	}
}