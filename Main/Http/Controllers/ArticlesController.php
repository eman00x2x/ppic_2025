<?php

namespace EO\Http\Controllers;

use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;
use EO\Handlers\Exceptions\ValidationException;
use EO\Handlers\Exceptions\ResourceNotFoundException;
use EO\Auth\Auth;
use EO\View;
use EO\Factories\Factory;
use EO\Interfaces\IController;
use EO\Services\ArticleService as ArticleService;

class ArticlesController extends \EO\Http\BaseController implements IController
{
	protected ArticleService $articleService;

	function __construct() 
	{
		$this->articleService = new ArticleService();
	}

	function index() 
	{
		$this->authorize("view_articles", Auth::user()->account);

		$request = input()->all() ?? [];

		$data['articles'] = $this->articleService->getArticles($request);
		$data['categories'] =  $this->articleService->categories();

		return View::set(path: "/authenticated/articles/index.php")->bind(data: $data);
	}

	function add() 
	{
		$this->authorize("add_articles", Auth::user()->account);

		$data['categories'] = $this->articleService->categories();
		$data['banner'] = CDN . "/images/item_default.jpg";

		return View::set(path: "/authenticated/articles/add.php")->bind(data: $data);
	}

	/**
	 * Returns the data for an article based on its ID
	 * 
	 * @param int $name The name of the article to retrieve
	 * @return array The article data, or false if the article could not be found
	 */
	function edit($name) 
	{
		$this->authorize("edit_articles", Auth::user()->account);
		
		$data = $this->articleService->getArticleyByName($name);
		$data['categories'] = $this->articleService->categories();

		return View::set(path: "/authenticated/articles/edit.php")->bind(data: $data);
	}

	function confirmSelection() 
	{
		$request = input()->all();

		$article_ids = $request['ids'];
		$action = $request['action'];
		$action_value = $request['action_value'];
		
		$this->authorize($action, Auth::user()->account);
		
		$options = [
			"set_category" => [
				"url" =>url("ArticlesController@changeCategory"),
				"message" => "You are about to move " . count($article_ids) . " article(s) to category $action_value . Are you sure do you want to continue the action?"
			],
			"set_status" => [
				"url" => url("ArticlesController@setPublishStatus"),
				"message" => "You are about to " . ($action_value == 1 ? "Publish" : "Unpublished") . " " . count($article_ids) . " article(s). Are you sure do you want to continue the action?"
			],
			"delete" => [
				"url" => url("ArticlesController@delete"),
				"message" => "You are about to Delete (Permanent) " . count($article_ids) . " article(s). All data related to this article(s) will be permanently deleted and this action is ireversible, Are you sure do you want to continue the deletion of these article(s)?"
			]
		];

		$articles = $this->articleService->getArticles(["article_id" => $article_ids]);

		$data = [
			"articles" => $articles,
			"ids" => implode(",", $article_ids),
			"action" => $action,
			"action_value" => $action_value,
			"url" => $options[$action]['url'],
			"message" => $options[$action]['message']
		];

		return View::set("/authenticated/articles/confirmSelection.php")->bind(data: $data);
	}

	/**
	 * Update the publish status of an article
	 * 
	 * @return \Pecee\Http\JsonResponse A JSON containing the status and message of the operation
	 */
	public function setPublishStatus()
	{
		$request = input()->all();
		$article_ids = explode(',', $request['ids']);
		$is_published = $request['action_value'];
		$publish_status = $is_published ? "Published" : "Unpublished";

		try {
			$this->articleService->updatePublishedStatus($article_ids, $is_published);
		} catch (\Exception $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		}

		return $this->handleMessageResponse("Successfully $publish_status articles!");
	}

	public function changeCategory()
	{
		$request = input()->all();
		$category = $request['action_value'];
		$article_ids = explode(",", $request['ids']);

		try {
			$this->articleService->changeCategory($article_ids, $category);
		} catch (\Exception $e) {
			return $this->handleMessageResponse($e->getMessage(), 'error', 2);
		}

		return $this->handleMessageResponse("Successfully moved articles to category $category!");
	}


	/**
	 * Save new article record in the database
	 * 
	 * @return JSON A JSON containing the status and message of the operation
	 */
	public function saveNew()
	{
		$request = input()->all();
		
		try {
			$this->articleService->create($request);
		} catch (ValidationException $e) {
			return $this->handleMessageResponse($e->getMessage(), 'error', 2);
		} catch (\Exception $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		}

		return $this->handleMessageResponse('Article created successfully');
	}

	/**
	 * Updates an existing article record in the database
	 *
	 * @param int $id The ID of the article to update
	 * @return \Pecee\Http\JsonResponse A JSON containing the status and message of the operation
	 */
	public function save($id)
	{
		$request = input()->all();
		$request['updated_by'] = Auth::user()->account['full_name'];

		try {
			$this->articleService->update($id, $request);
		} catch (ValidationException $e) {
			return $this->handleMessageResponse($e->getMessage(), 'error', 2);
		} catch (\Exception $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		}

		return $this->handleMessageResponse('Article updated successfully');
	}

	function delete($id = null)
	{
		$request = input()->all();
		$article_ids = explode(",", $request['ids']);

		try {
			$deletedArticles = $this->articleService->destroyArticles($article_ids);
		} catch (\Exception $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		}

		return $this->handleMessageResponse('Articles permanently deleted successfully');
	}

	function upload() 
	{
		return View::set("JSON")->bind(
			data: $this->articleService->upload(
				data: $_FILES['browseFile'], 
				params: [
					"destination_folder" => "/Public/global_assets/images/articles",
					"temp_url" => CDN . "/images/temporary",
					"final_url" => CDN . "/images/articles",
					"file_type" => "image",
					"file_max_size" => "2MB"
				]
			)
		);
	}


}