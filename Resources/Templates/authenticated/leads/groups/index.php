<?php

use EO\View;

View::setMasterTemplate(path: "/authenticated/template.php");

/** Document Header Configuration */
View::setDocumentHeader(
	data: [
		"title" => "Lead Groups",
		"description" => "List of all lead Groups",
		"scripts" => [
			CDN . "/js/main/app/leadGroups.js"
		]
	]
);

/** Document Top Configuration */
View::define(
	name: "document_top",
	path: "/authenticated/includes/document_top.php", 
	data: [
		"title" => "<i class='ti ti-users-group me-2  fs-32'></i> Lead Groups",
		"description" => "List of all lead Groups",
		"btn" => [
			"<a href='".url("leads.groups.add")."' class='btn btn-primary'><i class='ti ti-plus me-1'></i> <i class='ti ti-users fs-20 d-sm-block d-md-none'></i><span class='d-none d-md-block'>Create Lead Groups</span></a>",
			"<a href='".url("leads")."' class='btn btn-primary'><i class='ti ti-users me-1'></i> <span class='d-none d-md-block'>Leads List</span></a>"
		]
	]
);

View::define(
	name: "toolbar",
	path: "/authenticated/includes/toolbar.php",
	data: [
		"controller" => "LeadGroupsController",
		"toolbar" => [
			"actions" => "lead_groups_toolbar_actions",
			"filter" => "lead_groups_toolbar_filter",
			"components" => ["search", "limit"]
		],
		"url" => "LeadGroupsController@index",
		"rows" => [20, 50, 80, 100, 200, 500, 1000]
	]
);

	View::define(
		name: "lead_groups_toolbar_actions",
		path: "/authenticated/includes/toolbar_actions/lead_groups_toolbar_actions.php",
		data: []
	);

	View::define(
		name: "lead_groups_toolbar_filter",
		path: "/authenticated/includes/toolbar_filters/lead_groups_toolbar_filter.php",
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
								$html[] = "<th class='align-middle'>Name</th>";
								$html[] = "<th class='align-middle'>Created Date</th>";
								$html[] = "<th class='align-middle'>Actions</th>";
							$html[] = "</tr>";
						$html[] = "</thead>";
						$html[] = "<tbody class='data-container'>";
							if($data['lead_groups']) {
								$item_number = View::$collections['itemStartingNumber'] ?? 0;

								for($i=0; $i<count($data['lead_groups']); $i++) {
									$html[] = "<tr class='row_".$data['lead_groups'][$i]['lead_group_id']."'>";
										$html[] = "<td class='text-center w-1'>";
											$html[] = "<input type='checkbox' class='form-check-input form-check-input-selection m-0 align-middle cursor-pointer lead_group_id' value='".$data['lead_groups'][$i]['lead_group_id']."' />";
										$html[] = "</td>";
										$html[] = "<td class='text-center'>".$item_number."</td>";
										$html[] = "<td><a href='".url("leads", null, ["lead_group_id" => $data['lead_groups'][$i]['lead_group_id']])."' class=''>".$data['lead_groups'][$i]['name']." <i class='ti ti-link'></i></a></td>";
										$html[] = "<td><i class='ti ti-calendar'></i> ".$data['lead_groups'][$i]['created_date']."</td>";
										$html[] = "<td>";
											$html[] = "<span><a href='".url("leads.groups.edit", ["id" => $data['lead_groups'][$i]['lead_group_id']])."' class='btn btn-sm btn-outline-primary'><i class='ti ti-edit me-1'></i> <span class='d-none d-md-block'>Edit</span></a></span>";
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