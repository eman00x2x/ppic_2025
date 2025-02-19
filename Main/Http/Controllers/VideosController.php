<?php

namespace EO\Http\Controllers;

use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;
use EO\Handlers\Exceptions\ResourceNotFoundException;
use EO\Handlers\Exceptions\ValidationException;
use EO\Auth\Auth;
use EO\Interfaces\IController;
use EO\Services\VideoService;
use EO\View;

class VideosController extends \EO\Http\BaseController implements IController
{
	protected VideoService $videoService;

	/**
	 * AccountsController constructor.
	 */
	public function __construct() 
	{
		$this->videoService = new VideoService();
	}

	/**
	 * Returns a list of accounts.
	 * 
	 * @return array The list of accounts.
	 */
	public function index() 
	{
		$request = input()->all() ?? [];

		$data = [];

		$data['videos'] = $this->videoService->getVideos($request);
		$data['categories'] = $this->videoService->categories();

		return \EO\View::set("/authenticated/videos/index.php")->bind(data: $data);
		
	}

	public function add() 
	{
		$data['categories'] = $this->videoService->categories();
		return \EO\View::set("/authenticated/videos/add.php")->bind(data: $data);
	}

	public function edit($id) 
	{
		$data = $this->videoService->getVideo($id);
		$data['categories'] = $this->videoService->categories();
		return \EO\View::set("/authenticated/videos/edit.php")->bind(data: $data);
	}

	function confirmSelection() 
	{
		$request = input()->all();

		$video_ids = $request['ids'];
		$action = $request['action'];
		$action_value = $request['action_value'];
		
		$options = [
			"set_category" => [
				"url" =>url("VideosController@changeCategory"),
				"message" => "You are about to move " . count($video_ids) . " video(s) to category $action_value . Are you sure do you want to continue the action?"
			],
			"delete" => [
				"url" => url("VideosController@delete"),
				"message" => "You are about to Delete (Permanent) " . count($video_ids) . " video(s). All data related to this video(s) will be permanently deleted and this action is ireversible, Are you sure do you want to continue the deletion of these video(s)?"
			]
		];

		$videos = $this->videoService->getVideos(["video_id" => $video_ids]);

		$data = [
			"videos" => $videos,
			"ids" => implode(",", $video_ids),
			"action" => $action,
			"action_value" => $action_value,
			"url" => $options[$action]['url'],
			"message" => $options[$action]['message']
		];

		return View::set("/authenticated/videos/confirmSelection.php")->bind(data: $data);
	}

	public function saveNew() 
	{
		$data = input()->all();

		try {
			foreach($data['videos'] as $key => $video) {
				$data['videos'][$key]['unique_id'] = $video['id'];
				$this->videoService->create($data['videos'][$key]);
			}
		} catch (ValidationException $e) {
			return $this->handleMessageResponse($e->getMessage(), 'error', 2);
		} catch (\Exception $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		}

		return $this->handleMessageResponse("Successfully added new video!");
	}

	public function save($id) 
	{
		$request = input()->all();

		try {
			$this->videoService->update($id, $request);
		} catch (ValidationException $e) {
			return $this->handleMessageResponse($e->getMessage(), 'error', 2);
		} catch (\Exception $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		}

		return $this->handleMessageResponse("Successfully update video!");
	}

	public function delete($ids = null) 
	{
		$request = input()->all();
		$video_ids = explode(",", $request['ids']);

		try {
			$this->videoService->destroyVideos($video_ids);
		} catch (\Exception $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		}

		return $this->handleMessageResponse("Videos permanently deleted successfully");
	}

	function changeCategory()
	{
		$request = input()->all();
		$video_ids = explode(",", $request['ids']);
		$category = $request['action_value'];

		try {
			$this->videoService->changeCategory($video_ids, $category);
		} catch (InvalidArgumentException $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		} catch (\Exception $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		}

		return $this->handleMessageResponse("Successfully moved videos to category $category!");
	}

	public function download(): void
	{
		$this->videoService->downloadData($accountId);
	}

}