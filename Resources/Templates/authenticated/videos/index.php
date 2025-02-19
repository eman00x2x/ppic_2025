<?php

use EO\View;

View::setMasterTemplate(path: "/authenticated/template.php");

/** Document Header Configuration */
View::setDocumentHeader(
	data: [
		'title' => 'Videos',
		'description' => 'List of added videos',
		'scripts' => [
			CDN . "/js/main/app/videos.js"
		]
	]
);

/** Document Top Configuration */
View::define(
	name: "document_top",
	path: "/authenticated/includes/document_top.php", 
	data: [
		"title" => "<i class='ti ti-brand-youtube me-2  fs-32'></i> Videos",
		"description" => "List of added videos",
		"btn" => [
			"<a href='".url("VideosController@add")."' class='btn btn-primary'><i class='ti ti-plus me-1'></i> <i class='ti ti-brand-youtube fs-20 d-sm-block d-md-none'></i><span class='d-none d-md-block'>Add Video</span></a>"
		]
	],
	
);

View::define(
	name: "toolbar",
	path: "/authenticated/includes/toolbar.php",
	data: [
		"controller" => "VideosController",
		"toolbar" => [
			"actions" => "videos_toolbar_actions",
			"filter" => "videos_toolbar_filter",
			"components" => ["sort", "limit"]
		],
		"url" => "VideosController@index",
		"sort_by" => ["category", "created_at"],
		"rows" => [20, 50, 80, 100, 200, 500, 1000]
	]
);

	View::define(
		name: "videos_toolbar_actions",
		path: "/authenticated/includes/toolbar_actions/videos_toolbar_actions.php",
		data: [
			"categories" => $data['categories']
		]
	);

	View::define(
		name: "videos_toolbar_filter",
		path: "/authenticated/includes/toolbar_filters/videos_toolbar_filter.php",
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
								$html[] = "<th class='align-middle'></th>";
								$html[] = "<th class='align-middle'>ID</th>";
								$html[] = "<th class='align-middle'>Category</th>";
								$html[] = "<th class='align-middle'>Url</th>";
								$html[] = "<th class='align-middle'>Created Date</th>";
							$html[] = "</tr>";
						$html[] = "</thead>";
						$html[] = "<tbody class='data-container'>";
							if($data['videos']) {

								$item_number = View::$collections['itemStartingNumber'] ?? 0;

								for($i=0; $i<count($data['videos']); $i++) {
									
									$html[] = "<tr class='row_".$data['videos'][$i]['video_id']."'>";
										$html[] = "<td class='text-center'>";
											$html[] = "<input type='checkbox' class='form-check-input form-check-input-selection m-0 align-middle cursor-pointer video_id' value='".$data['videos'][$i]['video_id']."' />";
										$html[] = "</td>";
										$html[] = "<td class='text-center'>".$item_number."</td>";
										$html[] = "<td><span class='avatar avatar-lg btn-playback cursor-pointer' style='background-image: url(".$data['videos'][$i]['thumbnail']['default'].")' data-embed='".$data['videos'][$i]['embed']."' data-url='".$data['videos'][$i]['url']."' data-id='".$data['videos'][$i]['unique_id']."'></span></td>";
										$html[] = "<td>".$data['videos'][$i]['unique_id']."</td>";
										$html[] = "<td class='category-text'>".$data['videos'][$i]['category']."</td>";
										$html[] = "<td>".$data['videos'][$i]['url']."</td>";
										$html[] = "<td>".$data['videos'][$i]['created_date']."</td>";
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