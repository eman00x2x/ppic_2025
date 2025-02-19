<?php

namespace EO\Services;

use Pecee\Http\Exceptions\MalformedUrlException;
use EO\Handlers\Exceptions\ValidationException;
use EO\Handlers\Exceptions\ResourceNotFoundException;
use EO\Service as Service;
use EO\Interfaces\IModel;
use EO\Facades\CacheFacade as Cache;
use EO\Model\VideoModel as Video;

class VideoService extends Service
{
	function __construct() 
	{
		parent::__construct();
	}

	function getVideos(array $request = []): array 
	{
		$this->buildFilters($request);
		try {
			self::$collections = Video::getCollections($request);
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

	function getVideo(int $id): array 
	{
		if ($_ENV['CACHE_ENABLE'] && ($video = Cache::getData("videos-$id"))) {
			return $video;
		}
		
		self::$collections = Video::getId($id);
		$items = self::$collections->getItems();

		if($items->isNotEmpty()) {
			$video = $items->map(function($data, $key) {
				return $this->formatResultData($data);
			})->first()->toArray();

			if ($_ENV['CACHE_ENABLE']) {
				Cache::setData("videos-$id", $video);
			}

			return $video;
		}else {
			throw new ResourceNotFoundException("Resource Not Found! Video ID: $id");
		}
		
		return $items->toArray();
	}

	function create(array $data)
	{
		$data['created_at'] = DATE_NOW;

		$validated_data = $this->validateInput($data);

		try {
			$id = Video::create(data: $validated_data);

			$this->log([
				'type' => 'info',
				'message' => "Video creation with ID: $id succeeded",
				'data' => $validated_data
			]);
		} catch (\Exception $e) {
			$this->log([
				"type" => "warning",
				"message" => "Video creation with ID: $id failed",
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
		$this->getVideo(id: $id);

		$validated_data = $this->validateInput($data);

		try {
			Video::modify($validated_data, $id);
			$this->log([
				'type' => 'info',
				'message' => "Video update with ID: $id succeeded",
				'data' => $validated_data
			]);
		} catch (\Exception $e) {
			$this->log([
				"type" => "warning",
				"message" => "Video update with ID: $id failed",
				"data" => [
					"error" => $e->getMessage(),
					"data" => $validated_data
				]
			]);
			throw new \Exception($e->getMessage());
		}

		if ($_ENV['CACHE_ENABLE']) {
			Cache::removeCache("videos-$id");
		}

		return $validated_data;
	}

	function destroy($id): void 
	{
		$data = $this->getVideo(id: $id);

		Video::delete(["video_id" => $id]);

		$this->log([
			"type" => "info", 
			"message" => "Video deleted with ID: $id succeeded",
			"data" => $data
		]);
		
		if ($_ENV['CACHE_ENABLE']) {
			Cache::removeCache("videos-$id");
		}
	}

	public function destroyVideos(array $ids): void
	{
		self::$collections = Video::select(["video_id",])->getCollections(["video_id" => $ids]);
		$items = self::$collections->getItems();

		$deletedVideos = [];
		
		foreach($items->toArray() as $result) {
			if ($_ENV['CACHE_ENABLE']) {
				Cache::removeCache("videos-" . $result['video_id']);
				$deletedVideos[] = $result;
			}
		}

		Video::delete(["video_id" => $ids]);

		$this->log([
			"type" => "info", 
			"message" => "Videos deleted succeeded",
			"data" => [
				"ids" => $ids,
				"deleted" => $deletedVideos
			]
		]);
	}

	public function changeCategory(array $ids, string $category): void
	{
		if(empty($ids)) {
			throw new InvalidArgumentException("No IDs provided for changeCategory, IDs should be array and not empty!");
		}

		Video::modify(['category' => $category], $ids);

		$this->log([
			'type' => 'info',
			'message' => "Video change status to $category succeeded",
			'data' => [
				'ids' => $ids,
				'status' => $category
			]
		]);
	}


	public function downloadData(int $account_id = null) 
	{
		$columns = [
			"fields" => [
				"video_id" => "videos.video_id",
				"unique_id" => "videos.unique_id",
				"category" => "videos.category",
				"thumbnail" => "videos.thumbnail",
				"url" => "videos.url",
				"embed" => "videos.embed",
				"created_at" => "videos.created_at",
				"created_date" => [
					"raw" => "FROM_UNIXTIME(videos.created_at)"
				]
			]
		];

		$header = array_map("strtoupper", array_keys($columns["fields"]));
		$request = [
			"rows" => 1000
		];
		
		self::$collections = Videos::load($columns)->getCollections($request);
		$items = self::$collections->getItems();

		FileSystem::downloadToCSV(data: $items->toArray(), header: $header, fileName: "videos-" . DATE_NOW);
	}

	private function buildFilters(array &$request): void 
	{
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

	function categories() 
	{
		return ["Technology", "Economy", "Finance"];
	}

}