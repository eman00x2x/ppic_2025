<?php

namespace EO\Http\Controllers\Website;

use EO\View;
use EO\Http\BaseController;
use EO\Services\VideoService as VideoService;

class VideosController extends BaseController
{
	protected VideoService $videoService;

	public function __construct()
	{
		View::setTemplateBasePath( ROOT . "/Resources/Templates");
		$this->videoService = new VideoService();
	}

	function index()
	{
		$request = input()->all() ?? [];
		$request['rows'] = 21;
		
		$data['videos'] = $this->videoService->getVideos(request: $request);
		return View::set("/website/videos/videos.php")->bind(data: $data);
	}

}