<?php

namespace Main\Services;

use Main\Controller as Controller;
use Main\Interfaces\IService as IService;
use Main\Service as Service;
use Main\Model\ArticleModel as Article;

class ArticleService extends Service implements IService
{

    public Article $article;

    function __construct() {
        $this->article = $this->getModel("Article");
    }

    function list(array $request, string $target_url) {

		$this->article
			// Apply filters based on the request parameters.
			->filter(request: $request)
			// Arrange the property based on the request parameters default to created_at in descending order.
			->sort(request: $request, sorting: ["created_at" => "DESC"])
			// Retrieve the paginated list of property.
			->getList(
				// Determine the page number from the request, default to 1 if not provided.
				page: ($request['page'] ?? 1),
				// Determine the limit of property per page from the request, default to 20 if not provided.
				limit: ($request['rows'] ?? 20),
				// The target URL for pagination links.
				url: $target_url
			);

		if($this->article->results) {
			for($i = 0; $i < count($this->article->results); $i++) {
				foreach($this->article->results[$i] as $key => $val) {
					if($key == "created_at") {
						$this->article->results[$i]['created_at'] = date("d M Y", $val);
						$this->article->results[$i]['url'] = url("web.articles", ["name" => $this->article->results[$i]['name']]);
					}
				}
			}
		}
		
		return $this->article;

    }

    function get(int $id) {
        $this->article->getId($id);
		return $this->article;
    }

    function create(array $data) {

		$result = $this->article->saveNew(data: $data);

        if($result['status'] == 2) {
			return [
				"status" => 2,
				"type" => "error",
				"message" => $result['message']
			];
		}else {
			return [
				"status" => 1,
				"type" => "success",
				"message" => "Successfully save!"
			];
		}

    }

    function update(int $id, array $data) {

        $response = $this->article->getId(id: $id);

        if($response) {

            $result = $this->article->where([
				"article_id" => $id
			])->save(data: $data);

			return [
				"status" => $result['status'],
				"type" => "success",
				"message" => $result['message']
			];

        }

        return [
			"status" => 2,
			"type" => "error",
            "message" => "Property not found!"
		];

    }

    function destroy($id) {

        $data = $this->article->getId(id: $id);

        if($data) {

            $this->article->where([
				"article_id" => $id
			])->delete();

            return [
                "status" => 1,
                "type" => "success",
                "message" => "Property deleted!"
            ];

        }

        return [
			"status" => 2,
			"type" => "error",
            "message" => "Property not found!"
		];

    }

}