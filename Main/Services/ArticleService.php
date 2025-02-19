<?php

namespace EO\Services;

use Pecee\Exceptions\InvalidArgumentException;
use Pecee\Http\Exceptions\MalformedUrlException;
use EO\Handlers\Exceptions\ValidationException;
use EO\Handlers\Exceptions\ResourceNotFoundException;
use EO\Service as Service;
use EO\Interfaces\IModel;
use EO\Facades\FileSystemFacade as FileSystem;
use EO\Facades\CacheFacade as Cache;
use EO\Model\ArticleModel as Article;

class ArticleService extends Service
{
	private $rootDirectory = ROOT . "/Public/global_assets/images";

	function __construct() 
	{
		parent::__construct();

		$this->validator->setConstraints([
			"title" => [
				"length" => [ "min" => 4, "max" => 100 ],
				"required" => true,
				"restrictedWords" => true
			]
		]);

	}

	function getArticles(array $request): array 
	{
		$this->buildFilters($request);
		try {
			self::$collections = Article::getCollections($request);
			$items = self::$collections->getItems();

			if ($items->isNotEmpty()) {
				return $items->map(function($data, $key) {
					return $this->formatResultData($data);
				})->toArray();
			}
		} 
		// Catch any exceptions of type MalformedUrlException that are thrown
		catch (MalformedUrlException $e) {
			// Throw a new exception of type ResourceNotFoundException with a message that includes the message from the caught exception
			throw new ResourceNotFoundException("Resource Not Found! " . $e->getMessage());
		}

		return $items->toArray();

	}

	function getArticle(int $id): array 
	{
		if ($_ENV['CACHE_ENABLE'] && ($article = Cache::getData("article-$id"))) {
			return $article;
		}

		self::$collections = Article::getId($id);
		$items = self::$collections->getItems();

		if ($items->isNotEmpty()) {
			$article = $items->map(function($data, $key) {
				return $this->formatResultData($data);
			})->first()->toArray();
			
			if ($_ENV['CACHE_ENABLE']) {
				Cache::setData("article-$id", $article);
			}

			return $article;
		}else {
			throw new ResourceNotFoundException("Resource Not Found! Article ID: $id");
		}
		
		return $items->toArray();
	}

	function getArticleyByName(string $name) 
	{
		if ($_ENV['CACHE_ENABLE'] && ($article = Cache::getData("article-$name"))) {
			return $article;
		}

		self::$collections = Article::getBy("name", $name);
		$items = self::$collections->getItems();

		if($items->isNotEmpty()) {
			$article = $items->map(function($data, $key) {
				return $this->formatResultData($data);
			})->first()->toArray();
			
			if ($_ENV['CACHE_ENABLE']) {
				Cache::setData("article-$name", $article);
			}

			return $article;
		}else {
			throw new ResourceNotFoundException("Resource Not Found! Article Unique ID: $name");
		}

		return $items->toArray();
	}


	function create(array $data): int
	{
		$data["created_at"] = DATE_NOW;
		$data["modified_at"] = DATE_NOW;
		$data['name'] = $this->helper("sanitize", ["string" => $data["title"]]) . "-" . $this->helper("generateRandomString", 10);

		$validated_data = $this->validateInput($data);

		try {
			$id = Article::create($validated_data);
			$this->log([
				'type' => 'info',
				'message' => "Article creation with ID: $id succeeded",
				'data' => $validated_data
			]);
		} catch (\Exception $e) {
			$this->log([
				"type" => "warning",
				"message" => "Article creation with ID: $id failed",
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
		$article = $this->getArticle($id);

		$data["modified_at"] = DATE_NOW;

		$validated_data = $this->validateInput($data);

		$old_data = $this->getArticle($id);

		try {
			Article::modify($validated_data, $id);

			$this->log([
				'type' => 'info',
				'message' => "Article update with ID: $id succeeded",
				'data' => $validated_data
			]);
		}catch (\Exception $e) {
			$this->log([
				"type" => "warning",
				"message" => "Article update with ID: $id failed",
				"data" => [
					"error" => $e->getMessage(),
					"data" => $validated_data
				]
			]);
			throw new \Exception($e->getMessage());
		}

		if ($_ENV['CACHE_ENABLE']) {
			Cache::removeCache("article-$old_data[name]");
			Cache::removeCache("article-$old_data[article_id]");
		}

		return $data;
	}

	public function updatePublishedStatus(array $ids, string $status) 
	{
		if(empty($ids)) {
			throw new InvalidArgumentException("No IDs provided for updatePublishedStatus, IDs should be array and not empty!");
		}

		Article::modify(['is_published' => $status, 'modified_at' => DATE_NOW], $ids);

		$this->log([
			"type" => "info", 
			"message" => "Article change status to $status succeeded", 
			"data" => [
				"ids" => $ids,
				"status" => $status
			]
		]);

		if ($_ENV['CACHE_ENABLE']) {
			self::$collections = Article::getCollections(["article_id" => $ids]);
			$items = self::$collections->getItems();

			$names = $items->pluck("name")->all();
			Cache::removeMultipleCache($names);
		}
	}

	public function changeCategory(array $ids, string $category) 
	{
		if(empty($ids)) {
			throw new InvalidArgumentException("No IDs provided for changeCategory, IDs should be array and not empty!");
		}

		Article::modify(['category' => $category, 'modified_at' => DATE_NOW], $ids);
		
		$this->log([
			"type" => "info", 
			"message" => "Article change category to $category succeeded", 
			"data" => [
				"ids" => $ids,
				"category" => $category
			]
		]);

		if ($_ENV['CACHE_ENABLE']) {
			self::$collections = Article::getCollections(["article_id" => $ids]);
			$items = self::$collections->getItems();

			$names = $items->pluck("name")->all();
			Cache::removeMultipleCache($names);
		}
	}

	function destroy($id): void 
	{
		$data = $this->getArticle(id: $id);

		Article::delete(["article_id" => $id]);

		$this->log([
			"type" => "info", 
			"message" => "Article deleted with ID: $id succeeded",
			"data" => $data
		]);

		if ($_ENV['CACHE_ENABLE']) {
			Cache::removeCache("article-" . $data['name']);
		}
	}

	public function destroyArticles(array $ids): array 
	{
		self::$collections = Article::select(["article_id", "name"])->getCollections(["article_id" => $ids]);
		$items = self::$collections->getItems();

		$deleted_articles = [];
		foreach ($items->toArray() as $article) {
			if ($_ENV['CACHE_ENABLE']) {
				Cache::removeCache("article-" . $article['name']);
			}

			$deleted_articles[] = $article;
		}

		Article::delete(["article_id" => $ids]);

		$this->log([
			"type" => "info", 
			"message" => "Article deletion succeeded",
			"data" => [
				"ids" => $ids,
				"deleted" => $deleted_articles
			]
		]);

		return $deleted_articles;
	}

	function getTotalArticlePerCategory(): array 
	{
		$filter["is_published"] = 1;
		$filter["sort"] = "modified_at|desc";

		$this->buildFilters($filter);

		try {
			self::$collections = Article::select([
				"total" => Article::raw("COUNT(category)"),
				"category",
			])
			->groupBy( [ "category" ] )
			->getCollections($filter);

			$items = self::$collections->getItems();

		} 
		// Catch any exceptions of type MalformedUrlException that are thrown
		catch (MalformedUrlException $e) {
			// Throw a new exception of type ResourceNotFoundException with a message that includes the message from the caught exception
			throw new ResourceNotFoundException("Resource Not Found! " . $e->getMessage());
		}

		return $items->toArray();
	}

	private function buildFilters(array &$request): void 
	{
		if (isset($request['search'])) {
			$request["OR"] = [
				"title[~]" => $request['search'],
				"created_by[~]" => $request['search'],
				"modified_by[~]" => $request['search'],
				"category[~]" => $request['search']
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

		if(isset($request['modified_at'])) {
			if(isset($request['modified_at']['from']) && !isset($request['modified_at']['to'])) {
				$request['AND']['modified_at[>=]'] = strtotime($request['modified_at']['from']);
			}

			if(isset($request['modified_at']['from']) && isset($request['modified_at']['to'])) {
				$request['AND']['modified_at[<>]'] = [strtotime($request['modified_at']['from']), strtotime($request['modified_at']['to'])];
			}

			unset($request['modified_at']);
		}

		if(isset($request['is_published'])) {
			$request['AND']['is_published'] = $request['is_published'];
			unset($request['is_published']);
		}

	}

	private function formatResultData(IModel $data): IModel
	{
		$data->short_title = $this->helper("niceTrim", ["string" => $data->title, "max_length" => 40]);
		$data->short_desc = $this->helper("niceTrim", ["string" => strip_tags($data->content), "max_length" => 70]);
		$data->url = DOMAIN . url("web.view.article", ["name" => $data->name, "id" => $data->article_id]);
		$data->is_published = $data->is_published == 1 ? true : false;
		$data->created_date = date("d M Y", $data->created_at);

		if($data->content != null) {
			$data->banner = $this->helper("getImageSrcFirstOccurrence", $data->content);
		}
		
		if($data->modified_at == 0) {
			$data->modified_date = null;
		}else {
			$data->modified_date = date("d M Y", $data->modified_at);
		}

		return $data;
	}

	function getUniqueId() 
	{
		$uniqueId = $this->helper("generateRandomString", 10);

		self::$collections = Article::getCollections(["name" => $uniqueId]);
		$items = self::$collections->getItems();

		if($items->isNotEmpty()) {
			$this->getUniqueId();
		}else {
			return $uniqueId;
		}
	}

	public function categories() 
	{
		return [
			"News", "Tips", "Finance", "Blog", "Investments"
		];
	}

}