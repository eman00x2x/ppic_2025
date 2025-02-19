<?php

namespace EO\Services;

use Pecee\Http\Exceptions\MalformedUrlException;
use EO\Handlers\Exceptions\ValidationException;
use EO\Handlers\Exceptions\ResourceNotFoundException;
use EO\Service as Service;
use EO\Facades\FileSystemFacade as FileSystem;
use EO\Facades\CacheFacade as Cache;
use EO\Model\TrafficModel as Traffic;

class TrafficService extends Service
{
	function __construct() 
	{
		parent::__construct();
	}

	function getTraffics(array $request = []): array
	{		
		$this->buildFilters($request);
		try {
			self::$collections = Traffic::load( Traffic::columns() )->getCollections($request);
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

	function getTraffic(int $id): array
	{
		if ($_ENV['CACHE_ENABLE'] && ($data = Cache::getData("traffics-$id"))) {
			return $data;
		}
		
		self::$collections = Traffic::load( Traffic::columns() )->getId($id);
		$items = self::$collections->getItems();
 
		if ($items->isNotEmpty()) {
			$traffic = $items->map(function($data, $key) {
				return $this->formatResultData($data);
			})->first()->toArray();
			
			if ($_ENV['CACHE_ENABLE']) {
				Cache::setData("traffics-$id", $traffic);
			}

			return $traffic;
		}else {
			throw new ResourceNotFoundException("Resource Not Found! Traffic ID: $id");
		}

		return $items->toArray();
	}

	function getByAccountId(int $account_id): array
	{
		self::$collections = Traffic::getBy("account_id", $account_id);
		$items = self::$collections->getItems();
		
		if ($items->isNotEmpty()) {
			$traffic = $items->map(function($data, $key) {
				return $this->formatResultData($data);
			})->toArray();
		}else {
			throw new ResourceNotFoundException("Resource Not Found! Traffics by Account ID: $account_id");
		}

		return $items->toArray();
	}

	function create(array $data)
	{
		$traffic_collections = Traffic::getCollections([
			'session_id' => $data['session_id'],
			'account_id' => $data['account_id'] ?? 0
		]);
		$traffic_items = $traffic_collections->getItems();

		if ($traffic_items->isNotEmpty()) {
			[$base_url, $uri] = explode("?", $data['traffic']['url']);
			if ($traffic_items->contains('url', '==', $base_url) == false) {
				Traffic::create($data);
			}
		}else {
			Traffic::create($data);
		}
	}

	function update(int $id, array $data): array
	{
		$this->getTraffic($id);

		$data['modified_at'] = DATE_NOW;
		
		Traffic::modify($data, $id);

		if ($_ENV['CACHE_ENABLE']) {
			Cache::removeCache("traffics-$id");
		}
		
		return $data;
	}

	function destroy(int $id): void
	{
		$this->getTraffic($id);
		Traffic::delete(["traffic_id" => $id]);

		if ($_ENV['CACHE_ENABLE']) {
			Cache::removeCache("traffics-$id");
		}
	}

	function destroySelected(array $ids): void
	{
		foreach($ids as $traffic_id) {
			$this->destroy($traffic_id);
		}
	}

	function destroyByAccountId($account_id): void
	{
		$this->getByAccountId($account_id);
		Traffic::delete(["account_id" => $account_id]);
	}

	function totalTrafficsPerUrl(array $filter)
	{
		$this->buildFilters($filter);

		self::$collections = Traffic::select([
			"count" => Traffic::raw("COUNT(*)"),
			"page" => Traffic::raw("JSON_UNQUOTE(<traffics.traffic>->'$.name')"),
			"url" => Traffic::raw("JSON_UNQUOTE(<traffics.traffic>->'$.url')")
		])->groupBy(["url"])
		->getCollections($filter);

		$items = self::$collections->getItems();

		if($items->isNotEmpty()) {
			return $items->sortByDesc("count")->toArray();
		}

		return false;
	}

	function totalTrafficsPerDay(array $filter)
	{
		$this->buildFilters($filter);

		self::$collections = Traffic::select([
			"total" => Traffic::raw("COUNT(*)"),
			"date" => Traffic::raw("DATE_FORMAT(DATE(FROM_UNIXTIME(created_at)),'%Y-%m-%d')")
		])->groupBy(["date"])
		->getCollections($filter);

		$items = self::$collections->getItems();

		if($items->isNotEmpty()) {
			return $items->sortByDesc("date")->toArray();
		}

		return false;
	}

	/**
	 * Saves the traffic data to a file.
	 *
	 * @param array $traffic_data The traffic data to be saved.
	 * @return void
	 */
	public function saveToFile(array $traffic_data): void
	{
		$file_path = ROOT . '/Storage/Traffics/traffics.json';
		$file_contents = json_decode(FileSystem::get($file_path), true);

		if (!isset($file_contents[$traffic_data['session_id']][$traffic_data['traffic']['url']])) {
			$traffic_data['created_at'] = DATE_NOW;
			$file_contents[$traffic_data['session_id']][$traffic_data['traffic']['url']] = $traffic_data;

			FileSystem::write($file_path, json_encode($file_contents));
		}
	}
	
	private function formatResultData($data)  
	{
		$data->created_date = date("d M Y", $data->created_at);
		$data->url_cutted = $this->helper("niceTrim", ["string" => $data->url, "max_length" => 60]);
		return $data;
	}

	private function buildFilters(array &$request): void 
	{
		if(isset($request['search'])) {
			$request["OR"] = [
				"traffic[~]" => $request['search']
			];
			unset($request['search']);
		}

		if(isset($request['created_at']['from'])) {
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

	public function downloadData(int $account_id = null)
	{
		$columns = Traffic::columns();
		$header = [array_map("strtoupper", array_keys($columns["fields"]))];
		$request = $account_id != null ? ["account_id" => $account_id] : [];

		self::$collections = Traffic::load($columns)->limit(10000)->getCollections($request);
		$items = self::$collections->getItems();

		$this->downloadToCSV(data: array_merge($header, $items->toArray()), fileName: "traffics-" . DATE_NOW);
	}

}