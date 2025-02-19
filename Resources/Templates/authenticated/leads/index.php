<?php

use EO\View;

View::setMasterTemplate(path: "/authenticated/template.php");

/** Document Header Configuration */
View::setDocumentHeader(
	data: [
		"title" => "Leads",
		"description" => "List of all leads",
		"scripts" => [
			CDN . "/js/main/app/leads.js"
		]
	]
);

$btn = "";
if(can("download_leads")) {
	$btn = "<a href='".url("LeadsController@download")."' class='btn btn-dark'><i class='ti ti-download fs-20 me-1'></i><span class='d-none d-md-block'>Download CSV</span></a>";
}

/** Document Top Configuration */
View::define(
	name: "document_top",
	path: "/authenticated/includes/document_top.php", 
	data: [
		"title" => "<i class='ti ti-users-group me-2  fs-32'></i> Leads",
		"description" => "List of all leads",
		"btn" => [
			$btn,
			"<a href='".url("LeadsController@add")."' class='btn btn-primary'><i class='ti ti-plus me-1'></i> <i class='ti ti-user fs-20 d-sm-block d-md-none'></i><span class='d-none d-md-block'>Create Leads</span></a>"
		]
	]
);

View::define(
	name: "toolbar",
	path: "/authenticated/includes/toolbar.php",
	data: [
		"controller" => "LeadsController",
		"toolbar" => [
			"actions" => "leads_toolbar_actions",
			"filter" => "leads_toolbar_filter",
			"components" => ["search", "sort", "limit"]
		],
		"url" => "LeadsController@index",
		"sort_by" => ["name", "email", "source", "created_at"],
		"rows" => [20, 50, 80, 100, 200, 500, 1000]
	]
);

	View::define(
		name: "leads_toolbar_actions",
		path: "/authenticated/includes/toolbar_actions/leads_toolbar_actions.php",
		data: [
			"sources" => $data['sources'],
			"groups" => $data['groups']
		]
	);

	View::define(
		name: "leads_toolbar_filter",
		path: "/authenticated/includes/toolbar_filters/leads_toolbar_filter.php",
		data: [
			"sources" => $data['sources']
		]
	);

$html[] = View::include("document_top");

$html[] = "<div class='page-body'>";
    $html[] = "<div class='container-xl'>";

		$html[] = "<div class='row'>";
			$html[] = "<div class='col-lg-2 col-md-3 col-sm-12 col-12 d-none d-md-block'>";
				$html[] = "<div class='card'>";
					$html[] = "<div class='card-body border-bottom p-1'>";
						$html[] = "<h3 class='class-title px-2 py-3 mb-0 border-bottom'>";
							$html[] = "Groups";
							$html[] = "<a href='".url("leads.groups")."' class='float-end fs-16 fw-normal' title='Manage Groups'><i class='ti ti-settings'></i></a>";
						$html[] = "</h3>";

						$html[] = "<div class='vh-100 o-auto'>";
							$html[] = "<div class='list-group list-group-flush'>";
								if($data['groups']) {
									$html[] = "<a class='list-group-item p-3' href='".url("leads", null, ["lead_group_id" => 0])."'><i class='ti ti-folder'></i> Ungrouped</a>";

									for($i=0; $i<count($data['groups']); $i++) {
										$html[] = "<a class='list-group-item p-3' href='".url("leads", null, ["lead_group_id" => $data['groups'][$i]['lead_group_id']])."'><i class='ti ti-folder'></i> ".$data['groups'][$i]['name']."</a>";
									}
								}
							$html[] = "</div>";
						$html[] = "</div>";
					$html[] = "</div>";
				$html[] = "</div>";
			$html[] = "</div>";

			$html[] = "<div class='col-lg-10 col-md-9 col-sm-12 col-12'>";
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
										$html[] = "<th class='align-middle'>Name</th>";
										$html[] = "<th class='align-middle'>Contact Number</th>";
										$html[] = "<th class='align-middle'>Email</th>";
										$html[] = "<th class='align-middle'>Source</th>";
										$html[] = "<th class='align-middle'>Created Date</th>";
										$html[] = "<th class='align-middle'>Actions</th>";
									$html[] = "</tr>";
								$html[] = "</thead>";
								$html[] = "<tbody class='data-container'>";
									if($data['leads']) {

										$item_number = View::$collections['itemStartingNumber'] ?? 0;

										for($i=0; $i<count($data['leads']); $i++) {
											
											$html[] = "<tr class='row_".$data['leads'][$i]['lead_id']."'>";
												$html[] = "<td class='text-center'>";
													$html[] = "<input type='checkbox' class='form-check-input form-check-input-selection m-0 align-middle cursor-pointer lead_id' value='".$data['leads'][$i]['lead_id']."' />";
												$html[] = "</td>";
												$html[] = "<td class='text-center'>".$item_number."</td>";
												$html[] = "<td><a href='".url("leads.view", ["id" => $data['leads'][$i]['lead_id']])."' class=''>".$data['leads'][$i]['name']." <i class='ti ti-link'></i></a></td>";
												$html[] = "<td><i class='ti ti-phone'></i> ".$data['leads'][$i]['contact_number']."</td>";
												$html[] = "<td><i class='ti ti-mail'></i> ".$data['leads'][$i]['email']."</td>";
												$html[] = "<td class='source-text'>".$data['leads'][$i]['source']."</td>";
												$html[] = "<td><i class='ti ti-calendar'></i> ".$data['leads'][$i]['created_date']."</td>";
												$html[] = "<td>";
													$html[] = "<span><a href='".url("leads.edit", ["id" => $data['leads'][$i]['lead_id']])."' class='btn btn-sm btn-outline-primary'><i class='ti ti-edit me-1'></i> <span class='d-none d-md-block'>Edit</span></a></span>";
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
    
    $html[] = "</div>";
$html[] = "</div>";