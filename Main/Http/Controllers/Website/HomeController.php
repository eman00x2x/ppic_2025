<?php

namespace EO\Http\Controllers\Website;

use EO\View;
use EO\Http\BaseController;
use EO\Services\PropertyService as PropertyService;
use EO\Services\AccountService as AccountService;
use EO\Services\ArticleService as ArticleService;

class HomeController extends BaseController
{
	protected PropertyService $propertyService;
	protected AccountService $accountService;
	protected ArticleService $articleService;

	public function __construct()
	{
		View::setTemplateBasePath( ROOT . "/Resources/Templates");
	}
	
	public function index()
	{
		$this->articleService = new ArticleService();
		$this->propertyService = new PropertyService();
		
		$data['collections']['listing_type'] = $this->propertyService->listingTypeCollection();
		$data['collections']['categories'] = $this->propertyService->categoriesCollection();

		$data['articles'] = $this->articleService->getArticles([
			"sort" => "created_at|desc",
			"is_published" => 1,
			"rows" => 4
		]);
		
		$data['videos'] = false;

		$data['properties'] = $this->propertyService->getProperties(request: [
			"listing_type" => 'for sale',
			"status" => 1,
			"rows" => 8
		]);

		return View::set("/website/home/home.php")->bind(data: $data);
	}

}