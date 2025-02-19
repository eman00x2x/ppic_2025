<?php

use EO\View;

View::setMasterTemplate(path: "/authenticated/template.php");

/** Document Header Configuration */
View::setDocumentHeader(
	data: [
		'title' => 'Articles',
		'description' => 'List of all articles',
		'scripts' => [
			CDN . "/js/main/app/articles.js"
		]
	]
);

/** Document Top Configuration */
View::define(
	name: "document_top",
	path: "/authenticated/includes/document_top.php", 
	data: [
		"title" => "<i class='ti ti-book me-2  fs-32'></i> Articles",
		"description" => "List of all articles",
		"btn" => [
			"<a href='".url("ArticlesController@add")."' class='btn btn-primary'><i class='ti ti-plus me-1'></i> <i class='ti ti-book fs-20 d-sm-block d-md-none'></i><span class='d-none d-md-block'>Create Article</span></a>"
		]
	]
);

View::define(
	name: "toolbar",
	path: "/authenticated/includes/toolbar.php",
	data: [
		"controller" => "ArticlesController",
		"toolbar" => [
			"actions" => "articles_toolbar_actions",
			"filter" => "articles_toolbar_filter",
			"components" => ["search", "sort", "limit"]
		],
		"url" => "ArticlesController@index",
		"sort_by" => ["category", "created_by", "modified_by", "created_at", "modified_at"],
		"rows" => [20, 50, 80, 100, 200, 500, 1000]
	]
);

	View::define(
		name: "articles_toolbar_actions",
		path: "/authenticated/includes/toolbar_actions/articles_toolbar_actions.php",
		data: [
			"categories" => $data['categories']
		]
	);

	View::define(
		name: "articles_toolbar_filter",
		path: "/authenticated/includes/toolbar_filters/articles_toolbar_filter.php",
		data: []
	);

$html[] = View::include("document_top");

$html[] = "<div class='page-body'>";
    $html[] = "<div class='container-xl'>";

        $html[] = "<div class='card'>";
			
			$html[] = "<div class='card-body border-bottom py-3'>";

				$html[] = View::include("toolbar");

				$html[] = "<div class='table-responsive'>";
					$html[] = "<table class='table table-md table-hover card-table table-vcenter text-nowrap table-list'>";
						$html[] = "<thead>";
							$html[] = "<tr>";
								$html[] = "<th class='align-middle text-center'>";
									$html[] = "<input class='form-check-input check-input-selector m-0 align-middle cursor-pointer' type='checkbox' aria-label='select all' />";
								$html[] = "</th>";
								$html[] = "<th class='text-center align-middle'>#</th>";
								$html[] = "<th class='align-middle'>Title</th>";
								$html[] = "<th class='align-middle'>Category</th>";
								$html[] = "<th class='align-middle'>Published?</th>";
								$html[] = "<th class='align-middle'>Created By</th>";
								$html[] = "<th class='align-middle'>Created Date</th>";
								$html[] = "<th class='align-middle'>Modified By</th>";
								$html[] = "<th class='align-middle'>Modified Date</th>";
								$html[] = "<th class='align-middle'>Actions</th>";
							$html[] = "</tr>";
						$html[] = "</thead>";
						$html[] = "<tbody class='data-container'>";
							if($data['articles']) {

								$item_number = View::$collections['itemStartingNumber'] ?? 0;

								for($i=0; $i<count($data['articles']); $i++) {
									
									$html[] = "<tr class='row_".$data['articles'][$i]['article_id']."'>";
										$html[] = "<td class='text-center'>";
											$html[] = "<input type='checkbox' class='form-check-input form-check-input-selection m-0 align-middle cursor-pointer article_id' data-uuid='".$data['articles'][$i]['name']."' value='".$data['articles'][$i]['article_id']."' />";
										$html[] = "</td>";
										$html[] = "<td class='text-center'>".$item_number."</td>";
										$html[] = "<td>".$data['articles'][$i]['title']."</td>";
										$html[] = "<td class='category-text'>".$data['articles'][$i]['category']."</td>";
										$html[] = "<td class='status-text'><span class='badge bg-".($data['articles'][$i]['is_published'] ? "success" : "danger")." me-1'></span> ".($data['articles'][$i]['is_published'] ? "Yes" : "No")."</td>";
										$html[] = "<td>".$data['articles'][$i]['created_by']."</td>";
										$html[] = "<td>".$data['articles'][$i]['created_date']."</td>";
										$html[] = "<td>".$data['articles'][$i]['modified_by']."</td>";
										$html[] = "<td>".$data['articles'][$i]['modified_date']."</td>";
										$html[] = "<td>";
											$html[] = "<span><a href='".url("articles.edit", ["name" => $data['articles'][$i]['name']])."' class='btn btn-sm btn-outline-primary'><i class='ti ti-edit me-1'></i> <span class='d-none d-md-block'>Edit</span></a></a></span>";
										$html[] = "</td>";
									$html[] = "</tr>";

									$item_number++;

								}
							}
						$html[] = "</tbody>";
					$html[] = "</table>";
				$html[] = "</div>";

				$html[] = View::getPaginationTemplate();
				
			$html[] = "</div>";
        $html[] = "</div>";
    
    $html[] = "</div>";
$html[] = "</div>";