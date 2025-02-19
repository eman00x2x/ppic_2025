<?php

use EO\View;

View::setMasterTemplate(path: "/authenticated/template.php");

/** Document Header Configuration */
View::setDocumentHeader(
	data: [
		'title' => 'Accounts',
		'description' => 'List of all accounts',
		'scripts' => [
			CDN . "/js/main//app/account.js"
		]
	]
);

/** Document Top Configuration */
View::define(
	name: "document_top",
	path: "/authenticated/includes/document_top.php", 
	data: [
		"title" => "<i class='ti ti-users me-2 fs-32'></i> Accounts",
		"description" => "List of all accounts",
		"btn" => [
			"<a href='".url("AccountsController@add")."' class='btn btn-primary'><i class='ti ti-plus me-1'></i> <i class='ti ti-user fs-20 d-sm-block d-md-none'></i><span class='d-none d-md-block'>Create Account</span></a>"
		]
	]
);

View::define(
	name: "toolbar",
	path: "/authenticated/includes/toolbar.php",
	data: [
		"controller" => "AccountsController",
		"toolbar" => [
			"actions" => "accounts_toolbar_actions",
			"filter" => "accounts_toolbar_filter",
			"components" => ["search", "sort", "limit"]
		],
		"url" => "AccountsController@index",
		"sort_by" => ["username", "email", "registered_at"],
		"rows" => [20, 50, 80, 100, 200, 500, 1000]
	]
);

	View::define(
		name: "accounts_toolbar_actions",
		path: "/authenticated/includes/toolbar_actions/accounts_toolbar_actions.php",
		data: []
	);

	View::define(
		name: "accounts_toolbar_filter",
		path: "/authenticated/includes/toolbar_filters/accounts_toolbar_filter.php",
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
								$html[] = "<th class='align-middle'>Photo</th>";
								$html[] = "<th class='align-middle'>Name</th>";
								$html[] = "<th class='align-middle'>Username</th>";
								$html[] = "<th class='align-middle'>Email Address</th>";
								$html[] = "<th class='align-middle'>Account Type</th>";
								$html[] = "<th class='align-middle'>Status</th>";
								$html[] = "<th class='align-middle'>Registered Date</th>";
								$html[] = "<th class='align-middle'>Actions</th>";
							$html[] = "</tr>";
						$html[] = "</thead>";
						$html[] = "<tbody class='data-container'>";
							if($data['accounts']) {

								$item_number = View::$collections['itemStartingNumber'] ?? 0;

								for($i=0; $i<count($data['accounts']); $i++) {
									
									$html[] = "<tr class='row_".$data['accounts'][$i]['account_id']."'>";
										$html[] = "<td class='text-center'>";
											$html[] = "<input type='checkbox' class='form-check-input form-check-input-selection m-0 align-middle cursor-pointer account_id' value='".$data['accounts'][$i]['account_id']."' />";
										$html[] = "</td>";
										$html[] = "<td class='text-center'>".$item_number."</td>";
										$html[] = "<td><span class='avatar avatar-sm' style='background-image: url(".$data['accounts'][$i]['photo'].")'></span></td>";
										$html[] = "<td><a href='".url("accounts.view", ["id" => $data['accounts'][$i]['account_id']])."'>".$data['accounts'][$i]['full_name']." <i class='ti ti-link'></i></a></td>";
										$html[] = "<td><i class='ti ti-user'></i> ".$data['accounts'][$i]['username']."</td>";
										$html[] = "<td><i class='ti ti-mail'></i> ".$data['accounts'][$i]['email']."</td>";
										$html[] = "<td>".$data['accounts'][$i]['account_type']."</td>";
										$html[] = "<td class='status-text'><span class='badge bg-".($data['accounts'][$i]['status'] == "active" ? "success" : "danger")." me-1'></span> ".$data['accounts'][$i]['status']."</td>";
										$html[] = "<td>".$data['accounts'][$i]['registered_date']."</td>";
										$html[] = "<td>";
											$html[] = "<span><a href='".url("accounts.edit", ["id" => $data['accounts'][$i]['account_id']])."' class='btn btn-sm btn-outline-primary'><i class='ti ti-edit me-1'></i> <span class='d-none d-md-block'>Edit</span></a></a></span>";
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