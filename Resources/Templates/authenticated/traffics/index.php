<?php

use EO\View;
use EO\Auth\Auth;

View::setMasterTemplate(path: "/authenticated/template.php");

/** Document Header Configuration */
View::setDocumentHeader(
	data: [
		"title" => "Traffics",
		"description" => "List of all Traffics",
		"scripts" => [
			CDN . "/js/vendor/google/chart.js",
			CDN . "/js/main/app/charts.js",
			CDN . "/js/main/app/traffics.js"
		]
	]
);

/** Document Top Configuration */
View::define(
	name: "document_top",
	path: "/authenticated/includes/document_top.php", 
	data: [
		"title" => "<i class='ti ti-users-group me-2  fs-32'></i> Top 100 Traffics",
		"description" => "Most 100 popular pages on the site"
	]
);

View::define(
	name: "toolbar",
	path: "/authenticated/includes/toolbar.php",
	data: [
		"controller" => "TrafficsController",
		"toolbar" => [
			"filter" => "traffics_toolbar_filter"
		],
		"url" => "TrafficsController@index",
		"sort_by" => ["created_at"],
		"rows" => [20, 50, 80, 100, 200, 500, 1000]
	]
);

	View::define(
		name: "traffics_toolbar_actions",
		path: "/authenticated/includes/toolbar_actions/traffics_toolbar_actions.php",
		data: []
	);

	View::define(
		name: "traffics_toolbar_filter",
		path: "/authenticated/includes/toolbar_filters/traffics_toolbar_filter.php",
		data: []
	);

$html[] = View::include("document_top");

$html[] = "<div class='page-body'>";
    $html[] = "<div class='container-xl'>";

		$html[] = "<div class='card mb-3'>";
			$html[] = "<div class='card-body'>";
				$html[] = "<div class='daily-traffics-overview-chart'>";

					$html[] = "<div class='d-flex justify-content-between'>";
						$html[] = "<div class=''>";
							$html[] = "<h3 class='card-title m-0'>Daily Traffics</h3>";
							$html[] = "<p class='p-0 text-muted'>Total Traffics Per Day</p>";
						$html[] = "</div>";
					
						$html[] = View::include("toolbar");

					$html[] = "</div>";

					$html[] = "<div class='totalTrafficsPerDayLoader'></div>";
					$html[] = "<div id='totalTrafficsPerDay' height='180' data-url='".url("ChartsController@getTotalTrafficsPerDay", ["accountId" => (Auth::isAdmin() ? "null" : Auth::user()->id)], $data['filters'])."'></div>";
				$html[] = "</div>";
			$html[] = "</div>";
		$html[] = "</div>";

        $html[] = "<div class='card'>";
			
			$html[] = "<div class='card-body border-bottom py-3'>";

				$html[] = "<div class='table-responsive'>";
					$html[] = "<table class='table table-sm table-hover card-table table-vcenter text-nowrap table-list'>";
						$html[] = "<thead>";
							$html[] = "<tr>";
								$html[] = "<th class='text-center align-middle'>#</th>";
								$html[] = "<th class='align-middle'>Page</th>";
								$html[] = "<th class='align-middle text-center'>Total Views</th>";
							$html[] = "</tr>";
						$html[] = "</thead>";
						$html[] = "<tbody class='data-container'>";
							if($data['traffics']) {

								$item_number = View::$collections['itemStartingNumber'] ?? 0;

								for($i=0; $i<count($data['traffics']); $i++) {
									
									$html[] = "<tr class=''>";
										$html[] = "<td class='text-center'>".$item_number."</td>";
										$html[] = "<td>";
											$html[] = "<span>".$data['traffics'][$i]['page']."</span>";
											$html[] = "<span class='d-block fs-12 text-muted'>".$data['traffics'][$i]['url']."</span>";
										$html[] = "</td>";
										$html[] = "<td class='text-center'>".$data['traffics'][$i]['count']."</td>";
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