<?php

namespace EO\Http\Controllers\Website;

use EO\View;
use EO\Http\BaseController;
use EO\Services\ArticleService as ArticleService;

class ArticlesController extends BaseController
{
	protected ArticleService $articleService;

	public function __construct()
	{
		View::setTemplateBasePath( ROOT . "/Resources/Templates");
		$this->articleService = new ArticleService();
	}

	function articles()
	{
		$request = input()->all() ?? [];

		$request['is_published'] = 1;

		$data['articles'] = $this->articleService->getArticles(request: $request);
		$data['total_article_per_category'] = $this->articleService->getTotalArticlePerCategory();
		
		return View::set("/website/articles/articles.php")->bind(data: $data);
	}

	function getArticle($name, $id)
	{
		$data = $this->articleService->getArticle(id: $id);

		if($data['name'] !== $name) {
			throw new ResourceNotFoundException("Resource Not Found! Article: ".$data['title']."");
		}

		return View::set("/website/articles/view.php")->bind(data: $data);
	}

}