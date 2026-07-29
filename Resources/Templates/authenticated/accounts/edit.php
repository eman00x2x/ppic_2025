<?php

use EO\View;

View::setMasterTemplate(path: "/authenticated/template.php");

/** Document Header Configuration */
View::setDocumentHeader(
	data: [
		"title" => "Edit Account",
		"description" => "",
		"scripts" => [
			CDN . "/js/vendor/validatejs-0.13.1/validate.min.js",
			CDN . "/js/main/app/account.js"
		]
	]
);

/** Document Top Configuration */
View::define(
	name: "document_top",
	path: "/authenticated/includes/document_top.php", 
	data: [
		"title" => "Edit Account",
		"description" => "",
		"btn" => [
			"<a href='".url("AccountsController@index")."' class='btn btn-primary'><i class='ti ti-list me-1'></i> <i class='ti ti-user fs-20 d-sm-block d-md-none'></i><span class='d-none d-md-block'>Accounts</span></a>"
		]
	]
);

$html[] = View::include("document_top");

$html[] = "<div class='page-body'>";
	$html[] = "<div class='container-xl'>";

		$html[] = "<form id='form' action='".url("accounts.save.update", ["id" => $data['account']['account_id']])."' method='POST'>";

			$html[] = "<input type='hidden' name='photo' id='photo' value='".$data['account']['photo']."' />";

			$html[] = "<div class='card mb-3'>";
				$html[] = "<div class='card-body'>";
					$html[] = "<h3 class='card-title'>Personal Details</h3>";

					$html[] = "<div class='row justify-content-center'>";
						$html[] = "<div class='col-xl-2 col-lg-4 col-md-4 col-sm-12 col-12'>";
							$html[] = "<div class='mb-3 text-center'>";
								$html[] = "<span class='avatar avatar-xxxl photo-preview cursor-pointer' style='background-image:url(".$data['account']['photo'].")'></span>";
								$html[] = "<div class='photo-container mt-2' data-url='".url("AccountsController@upload")."'></div>";
							$html[] = "</div>";
						$html[] = "</div>";
						$html[] = "<div class='col-xl-10 col-lg-8 col-md-8 col-sm-12 col-12'>";
							$html[] = "<div class='d-flex gap-2'>";
								$html[] = "<div class='form-floating mb-3'>";
									$html[] = "<input type='text' name='names[firstname]' id='firstname' value='".$data['account']['names']['firstname']."' class='form-control' />";
									$html[] = "<label for='firstname'>Firstname</label>";
								$html[] = "</div>";

								$html[] = "<div class='form-floating mb-3'>";
									$html[] = "<input type='text' name='names[lastname]' id='lastname' value='".$data['account']['names']['lastname']."' class='form-control' />";
									$html[] = "<label for='lastname'>Lastname</label>";
								$html[] = "</div>";
							$html[] = "</div>";

							$html[] = "<div class='form-floating mb-3'>";
								$html[] = "<input type='text' name='mobile_number' id='mobileNumber' value='".$data['account']['mobile_number']."' class='form-control' />";
								$html[] = "<label for='mobileNumber'>Mobile Number</label>";
							$html[] = "</div>";
							
						$html[] = "</div>";
					$html[] = "</div>";

					

				$html[] = "</div>";
			$html[] = "</div>";

			$html[] = "<div class='card mb-3'>";
				$html[] = "<div class='card-body'>";
					
					$html[] = "<h3 class='card-title'>Account Details</h3>";

					if($data['account']['account_type'] != "Super Administrator") {
						$html[] = "<div class='form-floating mb-3'>";
							$html[] = "<select name='account_type' id='account_type' class='form-select'>";
								foreach($data['collection']['types'] as $type) {
									$sel = $type == $data['account']['account_type'] ? "select" : "";
									$html[] = "<option value='$type' $sel>$type</option>";
								}
							$html[] = "</select>";
							$html[] = "<label for='account_type'>Account Type</label>";
						$html[] = "</div>";
					}

					$html[] = "<div class='form-floating mb-3'>";
						$html[] = "<input type='text' name='username' id='username' value='".$data['account']['username']."' class='form-control'  />";
						$html[] = "<label for='username'>Username</label>";
					$html[] = "</div>";

					$html[] = "<div class='form-floating mb-3'>";
						$html[] = "<input type='email' name='email' id='email' value='".$data['account']['email']."' class='form-control'  />";
						$html[] = "<label for='email'>Email</label>";
					$html[] = "</div>";

					if($data['account']['account_type'] != "Super Administrator") {
						$html[] = "<div class='form-floating mb-3'>";
							$html[] = "<select name='status' id='status' class='form-select'>";
								foreach($data['collection']['statuses'] as $statuses) {
									$sel = $data['account']['status'] == $statuses ? "selected" : "";
									$html[] = "<option value='$statuses' $sel>".ucwords(str_replace("_", " ", $statuses))."</option>";
								}
							$html[] = "</select>";
							$html[] = "<label for='status'>Status</label>";
						$html[] = "</div>";
					}

					$html[] = "<div class='form-floating mb-3'>";
						$html[] = "<input type='text' value='".date("M d, Y", $data['account']['registered_at'])."' class='form-control-plaintext' readonly />";
						$html[] = "<label for='registered_at'>Registration Date</label>";
					$html[] = "</div>";

				$html[] = "</div>";
			$html[] = "</div>";

			$html[] = "<div class='card mb-3'>";
				$html[] = "<div class='card-body'>";
					$html[] = "<div class='row'>";
						$html[] = "<div class='col-md-6 col-lg-6 col-sm-12 col-12'>";
							$html[] = "<h3 class='card-title mb-0'>Change Password</h3>";
							$html[] = "<span class='form-hint'>If you don't want to change the account password, leave this blank.</span>";
							
							$html[] = "<div class='form-floating mb-3 mt-3'>";
								$html[] = "<input type='password' name='password' id='password' value='' class='form-control' />";
								$html[] = "<label for='password'>Password</label>";
							$html[] = "</div>";

							$html[] = "<div class='form-floating mb-3'>";
								$html[] = "<input type='password' name='confirm_password' id='confirm_password' value='' class='form-control' />";
								$html[] = "<label for='confirm_password'>Confirm Password</label>";
							$html[] = "</div>";
						$html[] = "</div>";
					$html[] = "</div>";
				$html[] = "</div>";
			$html[] = "</div>";

			$html[] = "<div class='card mb-3'>";
				$html[] = "<div class='card-body'>";
					$html[] = "<div class='row'>";
						$html[] = "<div class='col-md-6 col-lg-6 col-sm-12 col-12'>";
							$html[] = "<h3 class='card-title mb-0'>Account Permissions</h3>";
							$html[] = "<span class='form-hint'>Sets the account permissions</span>";
							
								foreach($data['collection']['permissions'] as $app => $permissionList) {
									$html[] = "<div class='my-3'>";
										$html[] = "<div class='pb-2'>".ucwords(str_replace("_"," ",$app))."</div>";
										foreach($permissionList as $permission) {
											$html[] = "<div class=''>";
												$html[] = "<div class='form-check form-switch'>";
													$html[] = "<input class='form-check-input' type='checkbox' name='permissions[$app][]' value='$permission' id='".$app."_".$permission."' ".(isset($data['account']['permissions'][$app]) && in_array($permission, $data['account']['permissions'][$app]) ? "checked" : "").">";
													$html[] = "<label class='form-check-label' for='".$app."_".$permission."'>".ucwords(str_replace("_", " ", $permission))."</label>";
												$html[] = "</div>";
											$html[] = "</div>";
										}
									$html[] = "</div>";
								}
						   
						$html[] = "</div>";
					$html[] = "</div>";
				$html[] = "</div>";
			$html[] = "</div>";

		$html[] = "</form>";

	$html[] = "</div>";
$html[] = "</div>";

$html[] = "<div class='btn-save-container fixed-bottom bg-white py-3 border-top'>";
	$html[] = "<div class='container-xl'>";
		$html[] = "<div class='text-end'>";
			$html[] = "<span class='btn btn-outline-primary btn-save'><i class='ti ti-device-floppy me-1'></i> Save Account</span>";
		$html[] = "</div>";
	$html[] = "</div>";
$html[] = "</div>";