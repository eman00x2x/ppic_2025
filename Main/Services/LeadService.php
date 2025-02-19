<?php

namespace EO\Services;

use Pecee\Http\Exceptions\MalformedUrlException;
use EO\Handlers\Exceptions\ValidationException;
use EO\Handlers\Exceptions\ResourceNotFoundException;
use EO\Handlers\Exceptions\MailerException;
use EO\Service as Service;
use EO\Interfaces\IModel;
use EO\Facades\FileSystemFacade as FileSystem;
use EO\Facades\LoggerFacade as Logger;
use EO\Facades\MailerFacade as Mailer;
use EO\Facades\CacheFacade as Cache;
use EO\Model\LeadModel as Lead;

class LeadService extends Service
{
	function __construct() 
	{
		parent::__construct();
		
		$this->validator->setConstraints([
			"name" => [
				"required" => true,
				"restrictedWords" => true
			],
			"contact_number" => [
				"required" => true
			],
			"email" => [
				"required" => true,
				"email" => true
			]
		]);
		
	}

	function getLeads(array $request = []): array 
	{
		$this->buildFilters($request);
		try {
			self::$collections = Lead::load( Lead::columns() )->getCollections($request);
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

	function getLead(int $id): array 
	{
		if ($_ENV['CACHE_ENABLE'] && ($lead = Cache::getData("leads-$id"))) {
			return $lead;
		}
		
		self::$collections = Lead::load( Lead::columns() )->getId($id);
		$items = self::$collections->getItems();

		if($items->isNotEmpty()) {
			$lead = $items->map(function($data, $key) {
				return $this->formatResultData($data);
			})->first()->toArray();

			if ($_ENV['CACHE_ENABLE']) {
				Cache::setData("leads-$id", $lead);
			}

			return $lead;
		}else {
			throw new ResourceNotFoundException("Resource Not Found! Lead ID: $id");
		}
		
		return $items->toArray();
	}

	function create(array $data)
	{
		$data['created_at'] = DATE_NOW;

		$validated_data = $this->validateInput($data);

		try {
			$id = Lead::create(data: $validated_data);

			$this->log([
				'type' => 'info',
				'message' => "Leads creation with ID: $id succeeded",
				'data' => $validated_data
			]);
		} catch (\Exception $e) {
			$this->log([
				"type" => "warning",
				"message" => "Leads creation with ID: $id failed",
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
		$this->getLead(id: $id);

		$validated_data = $this->validateInput($data);

		try {
			Lead::modify($validated_data, $id);
			$this->log([
				'type' => 'info',
				'message' => "Leads update with ID: $id succeeded",
				'data' => $validated_data
			]);
		} catch (\Exception $e) {
			$this->log([
				"type" => "warning",
				"message" => "Leads update with ID: $id failed",
				"data" => [
					"error" => $e->getMessage(),
					"data" => $validated_data
				]
			]);
			throw new \Exception($e->getMessage());
		}

		if ($_ENV['CACHE_ENABLE']) {
			Cache::removeCache("leads-$id");
		}

		return $validated_data;
	}

	public function updateSource(array $ids, string $source): void
	{
		if(empty($ids)) {
			throw new InvalidArgumentException("No IDs provided for changeSource, IDs should be array and not empty!");
		}

		Lead::modify(['source' => $source], $ids);

		$this->log([
			"type" => "info", 
			"message" => "Leads change source to $source succeeded", 
			"data" => [
				"ids" => $ids,
				"status" => $source
			]
		]);

		if ($_ENV['CACHE_ENABLE']) {
			foreach($ids as $id) {
				Cache::removeCache("leads-$id");
			}
		}
	}

	public function updateGroup(array $ids, string $groupId): void
	{
		if(empty($ids)) {
			throw new InvalidArgumentException("No IDs provided for moveToGroup, IDs should be array and not empty!");
		}

		Lead::modify(['lead_group_id' => $groupId], $ids);

		$this->log([
			"type" => "info", 
			"message" => "Leads move to id: $groupId succeeded", 
			"data" => [
				"ids" => $ids,
				"lead_group_id" => $groupId
			]
		]);

		if ($_ENV['CACHE_ENABLE']) {
			foreach($ids as $id) {
				Cache::removeCache("leads-$id");
			}
		}
	}

	function destroy($id): void 
	{
		$data = $this->getLead(id: $id);

		Lead::delete(["lead_id" => $id]);

		$this->log([
			"type" => "info", 
			"message" => "Leads deleted with ID: $id succeeded",
			"data" => $data
		]);
		
		if ($_ENV['CACHE_ENABLE']) {
			Cache::removeCache("leads-$id");
		}
	}

	public function destroyLeads(array $ids): void
	{
		self::$collections = Lead::select(["lead_id",])->getCollections(["lead_id" => $ids]);
		$items = self::$collections->getItems();

		$deleted_leads = [];
		
		foreach($items->toArray() as $result) {
			if ($_ENV['CACHE_ENABLE']) {
				Cache::removeCache("leads-" . $result['lead_id']);
				$deleted_leads[] = $result;
			}
		}

		Lead::delete(["lead_id" => $ids]);

		$this->log([
			"type" => "info", 
			"message" => "Leads deleted succeeded",
			"data" => [
				"ids" => $ids,
				"deleted" => $deleted_leads
			]
		]);
	}

	function getTotalLeadsPerDay($filter)
	{
		$this->buildFilters($filter);

		self::$collections = Lead::select([
			"total" => Lead::raw("COUNT(lead_id)"),
			"date" => Lead::raw("DATE_FORMAT(DATE(FROM_UNIXTIME(created_at)),'%Y-%m-%d')")
		])->groupBy([ "date" ])
		->getCollections($filter);

		$items = self::$collections->getItems();

		if($items->isNotEmpty()) {
			return $items->toArray();
		}

		return false;
	}

	function getTotalLeadsMonthly($filter) 
	{
		$this->buildFilters($filter);

		self::$collections = Lead::select([
			"total" => Lead::raw("COUNT(lead_id)"),
			"date" => Lead::raw("DATE_FORMAT(DATE(FROM_UNIXTIME(created_at)),'%Y-%m')")
		])->groupBy([ "date" ])
		->getCollections($filter);
		
		$items = self::$collections->getItems();
		
		if($items->isNotEmpty()) {
			return $items->toArray();
		}

		return false;
	}

	public function downloadData(int $account_id = null) 
	{
		$columns = [
			"fields" => [
				"lead_id" => "leads.lead_id",
				"contact_number" => "leads.contact_number",
				"email" => "leads.email",
				"created_at" => [
					"raw" => "FROM_UNIXTIME(created_at)"
				]
			]
		];

		$header = array_map("strtoupper", array_keys($columns["fields"]));
		$request = [
			"rows" => 1000
		];
		
		if($account_id != null) {
			$request["account_id"] = $account_id;
		}

		self::$collections = Lead::load($columns)->getCollections($request);
		$items = self::$collections->getItems();

		FileSystem::downloadToCSV(data: $items->toArray(), header: $header, fileName: "leads-" . DATE_NOW);
	}

	public function sendLeadToEmail($data) 
	{
		try {
			Mailer::template('InquiryEmail', $data)
				->to([$data['send_to']])
					->send(subject: 'New Inquiry!');
		} catch(MailerException $e) {
			$this->log([
				"type" => "critical", 
				"message" => "InquiryEmail Mailer sending failed",
				"data" => [
					"error" => $e->getMessage(),
					"data" => $validated_data
				]
			]);
			throw new MailerException("Error sending email, please contact administrator! ");
		}
	}

	private function buildFilters(array &$request): void 
	{
		if (isset($request['search'])) {
			$request["OR"] = [
				"name[~]" => $request['search'],
				"email[~]" => $request['search'],
				"contact_number[~]" => $request['search'],
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

		if(isset($request['account_id']) && $request['account_id'] == "null") {
			unset($request['account_id']);
		}
	}

	private function formatResultData(IModel $data): IModel 
	{
		$data->created_date = date("d M Y", $data->created_at);
		return $data;
	}

	function sources() 
	{
		return ["Website", "Phone", "Email", "Social Media"];
	}

}