<?php

use EO\View;
use EO\Auth\Auth as Auth;

View::setMasterTemplate(path: "/authenticated/template.php");

/** Document Header Configuration */
View::setDocumentHeader(
	data: [
		"title" => "Properties",
		"description" => "List of all properties",
		"scripts" => [
			CDN . "/js/vendor/wnumb-1.2.0/wNumb.min.js",
			CDN . "/js/vendor/tabler/dist/libs/nouislider/dist/nouislider.min.js?1724846371",
			CDN . "/js/main/app/properties.js",
		]
	]
);

$btn = "";
if(can("download_properties")) {
	$btn = "<a href='".url("PropertiesController@download")."' class='btn btn-dark'><i class='ti ti-download fs-20 me-1'></i><span class='d-none d-md-block'>Download CSV</span></a>";
}

/** Document Top Configuration */
View::define(
	name: "document_top",
	path: "/authenticated/includes/document_top.php", 
	data: [
		"title" => "<i class='ti ti-building me-2 fs-32'></i> Properties",
		"description" => "List of all properties",
		"btn" => [
			$btn,
			"<a href='".url("PropertiesController@add")."' class='btn btn-primary'><i class='ti ti-plus me-1'></i> <i class='ti ti-building-estate fs-20 d-sm-block d-md-none'></i><span class='d-none d-md-block'>Post Property</span></a>"
		]
	]
);

View::define(
	name: "toolbar",
	path: "/authenticated/includes/toolbar.php",
	data: [
		"controller" => "PropertiesController",
		"toolbar" => [
			"actions" => "properties_toolbar_actions",
			"filter" => "properties_toolbar_filter",
			"components" => ["search", "sort", "limit"]
		],
		"url" => "PropertiesController@index",
		"sort_by" => ["title", "price", "category", "modified_at", "created_at"],
		"rows" => [20, 50, 80, 100, 200, 500, 1000]
	]
);

	View::define(
		name: "properties_toolbar_actions",
		path: "/authenticated/includes/toolbar_actions/properties_toolbar_actions.php",
		data: [
			"categories" => $data['collections']['categories']
		]
	);

	View::define(
		name: "properties_toolbar_filter",
		path: "/authenticated/includes/toolbar_filters/properties_toolbar_filter.php",
		data: [
			"categories" => $data['collections']['categories'],
			"listing_types" => $data['collections']['listing_types']
		]
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
								$html[] = "<th class='align-middle'>Image</th>";
								$html[] = "<th class='align-middle'>Title</th>";
								$html[] = "<th class='align-middle text-end'>Price</th>";

								if(Auth::isAdmin()) { $html[] = "<th class='align-middle'>Posted By</th>"; }

								$html[] = "<th class='align-middle'>Modified Date</th>";
								$html[] = "<th class='align-middle'>Actions</th>";
							$html[] = "</tr>";
						$html[] = "</thead>";
						$html[] = "<tbody class='data-container'>";
							if($data['properties']) {

								$item_number = View::$collections['itemStartingNumber'] ?? 0;

								for($i=0; $i<count($data['properties']); $i++) {
									
									$html[] = "<tr class='row_".$data['properties'][$i]['property_id']." ".($data['properties'][$i]['status'] == "Removed" ? "opacity-20" : "")."'>";
										$html[] = "<td class='text-center check-box-wrapper'>";
											if($data['properties'][$i]['status'] != 2 || Auth::isAdmin()) {
												$html[] = "<input type='checkbox' class='form-check-input form-check-input-selection m-0 align-middle cursor-pointer property_id' value='".$data['properties'][$i]['property_id']."' />";
											}
										$html[] = "</td>";
										$html[] = "<td class='text-center'>".$item_number."</td>";
										$html[] = "<td><span class='avatar avatar-md' style='background-image: url(".$data['properties'][$i]['thumb_img'].")'></span></td>";
										$html[] = "<td>";
											$html[] = "".$data['properties'][$i]['short_title']."";
											$html[] = "<div class='mt-2 fs-13 d-flex gap-2'>";
												$html[] = "<span class='badge bg-azure text-azure-fg'><i class='ti ti-category-2'></i> <span class='category-text'>".$data['properties'][$i]['category']."</span></span>";
												
												switch($data['properties'][$i]['listing_type']) {
													case "For Sale": $html[] = "<span class='badge bg-teal text-teal-fg'><i class='ti ti-report-money'></i> <span class='listing-type-text'>".$data['properties'][$i]['listing_type']."</span></span>"; break;
													case "For Rent": $html[] = "<span class='badge bg-cyan text-cyan-fg'><i class='ti ti-report-money'></i> <span class='listing-type-text'>".$data['properties'][$i]['listing_type']."</span></span>"; break;
												}

												$html[] = "<span class='status-text'>";
												switch($data['properties'][$i]['status']) {
													case "Available": $html[] = "<span class='badge badge-outline text-green'><i class='ti ti-home-dollar'></i> ".$data['properties'][$i]['availability']."</span>"; break;
													case "Sold": $html[] = "<span class='badge bg-red text-red-fg'><i class='ti ti-home-dollar'></i> ".$data['properties'][$i]['availability']."</span>"; break;
													case "Removed": $html[] = "<span class='badge bg-secondary text-white'><i class='ti ti-circle-letter-x'></i> ".$data['properties'][$i]['availability']."</span>"; break;
												}
												$html[] = "</span>";
							
											$html[] = "</div>";
										$html[] = "</td>";
										$html[] = "<td class='text-end'><i class='ti ti-currency-peso'></i> ".$data['properties'][$i]['price_tag']."</td>";

										if(Auth::isAdmin()) {
											$html[] = "<td class='align-middle'><a href='".url("accounts.view", ["id" => $data['properties'][$i]['account']['account_id']])."' class=''>";
												$html[] = "<div class='d-flex lh-1 text-reset p-0 cursor-pointer'>";
													$html[] = "<span class='avatar avatar-md' style='background-image: url(".$data['properties'][$i]['account']['photo'].")'></span>";
													$html[] = "<span class='d-block ps-2 mt-2'>".$data['properties'][$i]['account']['full_name']." <i class='ti ti-link'></i></span>";
												$html[] = "</div>";
											$html[] = "</a></td>";
										}

										$html[] = "<td><i class='ti ti-calendar'></i> ".$data['properties'][$i]['modified_date']."</td>";
										$html[] = "<td class='btn-options'>";
												if($data['properties'][$i]['status'] != 2 || Auth::isAdmin()) {
													$html[] = "<span class=''><a href='".url("properties.edit", ["id" => $data['properties'][$i]['property_id']])."' class='btn btn-sm btn-outline-primary'><i class='ti ti-edit me-1'></i> <span class='d-none d-md-block'>Edit</span></a></span>";
												}
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