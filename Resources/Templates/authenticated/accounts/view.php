<?php

use EO\View;

View::setMasterTemplate(path: "/authenticated/template.php");

/** Document Header Configuration */
View::setDocumentHeader(
	data: [
		"title" => "Account",
		"description" => "",
		"scripts" => [
			CDN . "/js/vendor/google/chart.js",
			CDN . "/js/main/app/charts.js",
			CDN . "/js/main/app/account.js",
			CDN . "/js/main/app/login.js"
		]
	]
);

/** Document Top Configuration */
View::define(
	name: "document_top",
	path: "/authenticated/includes/document_top.php", 
	data: [
		"title" => "Account",
		"description" => $data['full_name'] . " " . $data['account_type'],
		"btn" => [
			"<a href='".url("AccountsController@add")."' class='btn btn-primary'><i class='ti ti-plus me-1'></i> <i class='ti ti-user fs-20 d-sm-block d-md-none'></i><span class='d-none d-md-block'>Create Account</span></a>",
			"<a href='".url("AccountsController@index")."' class='btn btn-primary'><i class='ti ti-list me-1'></i> <i class='ti ti-user fs-20 d-sm-block d-md-none'></i><span class='d-none d-md-block'>Accounts</span></a>",
			"<a href='".url("AccountsController@edit", ["id" => $data["account_id"]])."' class='btn btn-dark'><i class='ti ti-user-edit me-1'></i> <i class='ti ti-user fs-20 d-sm-block d-md-none'></i><span class='d-none d-md-block'>Update Details</span></a>"
		]
	]
);

View::define(
	name: "toolbar",
	path: "/authenticated/includes/toolbar.php",
	data: [
		"controller" => "LoginController",
		"toolbar" => [
			"actions" => "login_toolbar_actions",
			"components" => ["delete"]
		],
		"url" => "LoginController@index"
	]
);

View::define(
	name: "login_toolbar_actions",
	path: "/authenticated/includes/toolbar_actions/login_toolbar_actions.php",
	data: []
);

$html[] = View::include("document_top");

$html[] = "<div class='page-body'>";
    $html[] = "<div class='container mb-5'>";

        $html[] = "<div class='row'>";
			$html[] = "<div class='col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12'>";
				$html[] = "<div class='card'>";
					$html[] = "<div class='card-body border-bottom'>";

						$html[] = "<div class='d-flex flex-wrap flex-md-nowrap gap-3'>";
							$html[] = "<div class=''>";

								$html[] = "<div class='text-center'>";
									$html[] = "<span class='avatar avatar-xxl' style='background-image: url(".$data['photo'].");'></span>";
								$html[] = "</div>";

							$html[] = "</div>";

							$html[] = "<div class='flex-fill'>";
								
								$html[] = "<div class='d-flex align-items-top justify-content-between'>";
									$html[] = "<div class='ms-2 mb-3'>";
										$html[] = "<label class='text-muted fs-12'>Name</label>";
										$html[] = "<p class='fs-18 mb-2'>".$data['full_name']."</p>";

										if($data['status'] == "banned") {
											$html[] = "<span class='badge bg-red text-red-fg'><i class='ti ti-lock me-1'></i>".ucwords($data['status'])."</span>";
										}else if($data['status'] == "pending_activation") {
											$html[] = "<span class='badge bg-orange text-orange-fg'><i class='ti ti-square-rounded-minus me-1'></i>".ucwords($data['status'])."</span>";
										}else if($data['status'] == "inactive") {
											$html[] = "<span class='badge bg-purple text-purple-fg'><i class='ti ti-circle-letter-x me-1'></i>".ucwords($data['status'])."</span>";
										}else if($data['status'] == "expired_subscription") {
											$html[] = "<span class='badge bg-yellow text-yellow-fg'><i class='ti ti-cancel me-1'></i>".ucwords($data['status'])."</span>";
										}else {
											$html[] = "<span class='badge bg-green text-green-fg'><i class='ti ti-bulb me-1'></i>".ucwords($data['status'])."</span>";
										}

										$html[] = "<span class='badge bg-azure text-azure-fg ms-2'><i class='ti ti-user me-1'></i>".$data['username']."</span>";
										$html[] = "<span class='badge bg-cyan text-cyan-fg ms-2'><i class='ti ti-brand-supernova me-1'></i>".$data['account_type']."</span>";
									$html[] = "</div>";
								$html[] = "</div>";

								$html[] = "<div class='row'>";
									if($data['mobile_number'] != "") {
										$html[] = "<div class='col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12'>";
											$html[] = "<div class='ms-2'>";
												$html[] = "<label class='text-muted fs-12'>Mobile Number</label>";
												$html[] = "<p class='mb-0'><i class='ti ti-phone me-1'></i>".$data['mobile_number']."</p>";
											$html[] = "</div>";
										$html[] = "</div>";
									}
									
									if($data['email'] != "") {
										$html[] = "<div class='col-xl-5 col-lg-5 col-md-5 col-sm-12 col-12'>";
											$html[] = "<div class='ms-2'>";
												$html[] = "<label class='text-muted fs-12'>Email Address</label>";
												$html[] = "<p class='mb-0'><i class='ti ti-mail me-1'></i>".$data['email']."</p>";
											$html[] = "</div>";
										$html[] = "</div>";
									}

									$html[] = "<div class='col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12'>";
										$html[] = "<div class='ms-2'>";
											$html[] = "<label class='text-muted fs-12'> Registered Since</label>";
											$html[] = "<p class='mb-0'><i class='ti ti-calendar me-1'></i>".$data['registered_date']."</p>";
										$html[] = "</div>";
									$html[] = "</div>";
								$html[] = "</div>";

							$html[] = "</div>";
						$html[] = "</div>";

					$html[] = "</div>";
				$html[] = "</div>";

				$html[] = "<div class='card mt-3'>";
					$html[] = "<div class='card-body border-bottom'>";
					
						$html[] = "<div class='properties-postings-overview-chart '>";
							$html[] = "<div class='d-flex justify-content-between'>";
								$html[] = "<div class=''>";
									$html[] = "<h3 class='card-title m-0'>Daily Postings</h3>";
									$html[] = "<p class='p-0 text-muted'>Total postings per day</p>";
								$html[] = "</div>";
							
								$html[] = "<div class=''>";
									$html[] = "<select class='form-select select-filter' data-target='getMonthlyPostings'>";
										$html[] = "<option value='last-7-days'>Last 7 days</option>";
										$html[] = "<option value='last-30-days'>Last 30 days</option>";
										$html[] = "<option value='last-60-days'>Last 60 days</option>";
										$html[] = "<option value='last-90-days'>Last 90 days</option>";
									$html[] = "</select>";
								$html[] = "</div>";
							$html[] = "</div>";

							$html[] = "<div class='getMonthlyPostingsLoader'></div>";
							$html[] = "<div id='getMonthlyPostings' class='w-100' data-url='".url("ChartsController@getMonthlyPostings", ["accountId" => $data['account_id']])."'></div>";
						$html[] = "</div>";

					$html[] = "</div>";
				$html[] = "</div>";
				
				$html[] = "<div class='d-flex flex-wrap flex-md-nowrap gap-2'>";
					$html[] = "<div class='card mt-3 flex-fill'>";
						$html[] = "<div class='card-body'>";
							$html[] = "<div class='properties-status-overview-chart '>";
								$html[] = "<div class='d-flex justify-content-between'>";
									$html[] = "<div class=''>";
										$html[] = "<h3 class='card-title m-0'>Properties Per Status</h3>";
										$html[] = "<p class='p-0 text-muted'>Total properties posted per status</p>";
									$html[] = "</div>";
								
									$html[] = "<div class=''>";
										$html[] = "<select class='form-select select-filter' data-target='totalPropertiesPerStatus'>";
											$html[] = "<option value='last-7-days'>Last 7 days</option>";
											$html[] = "<option value='last-30-days'>Last 30 days</option>";
											$html[] = "<option value='last-60-days'>Last 60 days</option>";
											$html[] = "<option value='last-90-days'>Last 90 days</option>";
										$html[] = "</select>";
									$html[] = "</div>";
								$html[] = "</div>";

								$html[] = "<div class='totalPropertiesPerStatusLoader'></div>";
								$html[] = "<div id='totalPropertiesPerStatus' class='w-100' data-url='".url("ChartsController@getTotalPropertiesPerStatus", ["accountId" => $data['account_id']])."'></div>";
							$html[] = "</div>";
						$html[] = "</div>";
					$html[] = "</div>";

					$html[] = "<div class='card mt-3 flex-fill'>";
						$html[] = "<div class='card-body border-bottom'>";
							
							$html[] = "<div class='properties-category-overview-chart'>";

								$html[] = "<div class='d-flex justify-content-between'>";
									$html[] = "<div class=''>";
										$html[] = "<h3 class='card-title m-0'>Properties Per Category</h3>";
										$html[] = "<p class='p-0 text-muted'>Total properties posted per category</p>";
									$html[] = "</div>";
								
									$html[] = "<div class=''>";
										$html[] = "<select class='form-select select-filter' data-target='totalPropertiesPerCategory'>";
											$html[] = "<option value='last-7-days'>Last 7 days</option>";
											$html[] = "<option value='last-30-days'>Last 30 days</option>";
											$html[] = "<option value='last-60-days'>Last 60 days</option>";
											$html[] = "<option value='last-90-days'>Last 90 days</option>";
										$html[] = "</select>";
									$html[] = "</div>";
								$html[] = "</div>";

								$html[] = "<div class='totalPropertiesPerCategoryLoader'></div>";
								$html[] = "<div id='totalPropertiesPerCategory' class='w-100' data-url='".url("ChartsController@getTotalPropertiesPerCategory", ["accountId" => $data['account_id']])."'></div>";
							$html[] = "</div>";

						$html[] = "</div>";
					$html[] = "</div>";

				$html[] = "</div>";
			$html[] = "</div>";

			$html[] = "<div class='col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12'>";
				$html[] = "<div class='card'>";
					$html[] = "<div class='card-header'>";
						$html[] = "<ul class='nav nav-tabs card-header-tabs' data-bs-toggle='tabs' role='tablist'>";
							$html[] = "<li class='nav-item' role='presentation'>";
								$html[] = "<a href='#permissions' class='nav-link active' data-bs-toggle='tab' role='tab' aria-selected='true'><i class='ti ti-shield-lock me-1'></i> Permissions</a>";
							$html[] = "</li>";
							$html[] = "<li class='nav-item' role='presentation'>";
								$html[] = "<a href='#login_history' class='nav-link' data-bs-toggle='tab' role='tab' aria-selected='false'><i class='ti ti-square-key me-1'></i> Login History</a>";
							$html[] = "</li>";
						$html[] = "</ul>";
					$html[] = "</div>";
					$html[] = "<div class='card-body border-bottom'>";
						
						$html[] = "<div class='tab-content'>";
							/*** PERMISSIONS */
							$html[] = "<div class='tab-pane active show' id='permissions' role='tabpanel'>";
								$html[] = "<div class=''>";
									$html[] = "<h3 class=''><i class='ti ti-shield-lock fs-20 me-1'></i> Permissions</h3>";
									$html[] = "<dl>";
									foreach($data['permissions'] as $app => $collections) {
										$html[] = "<dt class='mb-1'>".ucwords($app)."</dt>";
										foreach($collections as $permission) {
											$html[] = "<dd class='ms-2'><i class='ti ti-key fs-18'></i> ".ucwords(str_replace("_", " ", $permission))."</dd>";
										}
									}
									$html[] = "</dl>";
								$html[] = "</div>";
							$html[] = "</div>";
							/*** END PERMISSIONS */

							/*** LOGIN HISTORY */
							$html[] = "<div class='tab-pane' id='login_history' role='tabpanel'>";
								$html[] = "<div class=''>";
									$html[] = "<div class='d-flex justify-content-between'>";
										$html[] = "<h3><i class='ti ti-square-key fs-20 me-1'></i> Login History</h3>";
										$html[] = View::include("login_toolbar_actions");
									$html[] = "</div>";
									$html[] = "<div class='' style='max-height:130vh;  overflow: auto;'>";
										if($data['logins']) {
											$html[] = "<div class='accordion accordion-flush' id='logins-accordion'>";
												foreach($data['logins'] as $logins) {
													$html[] = "<div class='accordion-item row_".$logins['login_id']."'>";
														$html[] = "<h2 class='accordion-header' id='".$logins['login_id']."'>";
															$html[] = "<div class='d-flex align-items-center'>";
																$html[] = "<div class=''>";
																	$html[] = "<input type='checkbox' class='form-check-input form-check-input-selection m-0 align-middle cursor-pointer login_id' data-uuid='".$logins['login_id']."' value='".$logins['login_id']."' />";
																$html[] = "</div>";
																$html[] = "<div class='flex-grow-1'>";
																	$html[] = "<button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#logins-".$logins['login_id']."' aria-expanded='false'>";
																		$html[] = "<div class=''>".$logins['login_browser']." > ".$logins['login_location']."</div>";
																	$html[] = "</button>";
																$html[] = "</div>";
															$html[] = "</div>";
														$html[] = "</h2>";
														$html[] = "<div class='accordion-collapse collapse ".(($logins['login_id'] == $data['logins'][0]['login_id']) ? "show" : "")."' data-bs-parent='#logins-accordion'  id='logins-".$logins['login_id']."' aria-labelledby='".$logins['login_id']."'>";
															$html[] = "<div class='accordion-body pt-0'>";
																$html[] = "<div class='d-flex'>";
																	$html[] = "<div class=''>";
																		$html[] = "";
																	$html[] = "</div>";
																	$html[] = "<div class='p-3 bg-light flex-fill border'>";
																		$html[] = "<table class='table table-sm '>";
																		$html[] = "<tr>";
																			$html[] = "<td><span class='text-muted fs-11 d-block'>Browser</span>".$logins['login_browser']."</td>";
																		$html[] = "</tr>";
																		$html[] = "<tr>";
																			$html[] = "<td><span class='text-muted fs-11 d-block'>IP Address</span>".$logins['login_ip']."</td>";
																		$html[] = "</tr>";
																		$html[] = "<tr>";
																			$html[] = "<td><span class='text-muted fs-11 d-block'>Location</span> ".$logins['login_location']."</td>";
																		$html[] = "</tr>";
																		$html[] = "<tr>";
																			$html[] = "<td><span class='text-muted fs-11 d-block'>Timezone</span> ".$logins['login_timezone']."</td>";
																		$html[] = "</tr>";
																		$html[] = "<tr>";
																			$html[] = "<td><span class='text-muted fs-11 d-block'>Provider</span> ".$logins['login_provider']."</td>";
																		$html[] = "</tr>";
																		$html[] = "</table>";
																	$html[] = "</div>";
																$html[] = "</div>";
																
															$html[] = "</div>";
														$html[] = "</div>";
													$html[] = "</div>";
												}

											$html[] = "</div>";
										}else {
											$html[] = "<p>Never login.</p>";
										}
									$html[] = "</div>";
								$html[] = "</div>";
							$html[] = "</div>";
							/*** END LOGIN HISTORY */
						$html[] = "</div>";

					$html[] = "</div>";
				$html[] = "</div>";
			$html[] = "</div>";
		$html[] = "</div>";
    
    $html[] = "</div>";
$html[] = "</div>";