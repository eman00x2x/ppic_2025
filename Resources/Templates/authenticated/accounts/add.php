<?php

use EO\View;

View::setMasterTemplate(path: "/authenticated/template.php");

/** Document Header Configuration */
View::setDocumentHeader(
	data: [
		"title" => "Create Account",
		"description" => "",
		"scripts" => [
			CDN . "/js/main/uploader.js",
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
		"title" => "Create Account",
		"description" => "",
		"btn" => [
			"<a href='".url("AccountsController@index")."' class='btn btn-primary'><i class='ti ti-list me-1'></i> <i class='ti ti-user fs-20 d-sm-block d-md-none'></i><span class='d-none d-md-block'>Accounts</span></a>"
		]
	]
);

$html[] = View::include("document_top");

$html[] = "<form id='uploadForm' class='d-none' action='".url("AccountsController@upload")."' method='POST' enctype='multipart/form-data' data-uploader='account'>";
	$html[] = "<center>";
		$html[] = "<input type='file' name='browseFile' id='browseFile' />";
	$html[] = "</center>";
$html[] = "</form>";

$html[] = "<div class='page-body'>";
	$html[] = "<div class='container-xl'>";

		$html[] = "<form id='form' action='".url("accounts.save.new")."' method='POST'>";

			$html[] = "<input type='hidden' name='organization_id' value='1' />";
			$html[] = "<input type='hidden' name='photo' id='photo' value='".$data['photo']."' />";

			$html[] = "<div class='card mb-3'>";
				$html[] = "<div class='card-body'>";
					$html[] = "<h3 class='card-title'>Personal Details</h3>";

					$html[] = "<div class='row justify-content-center'>";
						$html[] = "<div class='col-xl-2 col-lg-4 col-md-4 col-sm-12 col-12'>";
							$html[] = "<div class='mb-3 text-center'>";
								$html[] = "<span class='avatar avatar-xxxl photo-preview btn-photo-browse cursor-pointer' style='background-image:url(".$data['photo'].")'></span>";
								$html[] = "<span class='form-hint d-block mt-2'>Click to Upload Photo</span>";
								$html[] = "<span class='photo-upload-loader d-block'></span>";
							$html[] = "</div>";
						$html[] = "</div>";
						$html[] = "<div class='col-xl-10 col-lg-8 col-md-8 col-sm-12 col-12'>";
							$html[] = "<div class='d-flex gap-2'>";
								$html[] = "<div class='form-floating mb-3'>";
									$html[] = "<input type='text' name='names[firstname]' id='firstname' value='' class='form-control' />";
									$html[] = "<label for='firstname'>Firstname</label>";
								$html[] = "</div>";

								$html[] = "<div class='form-floating mb-3'>";
									$html[] = "<input type='text' name='names[lastname]' id='lastname' value='' class='form-control' />";
									$html[] = "<label for='lastname'>Lastname</label>";
								$html[] = "</div>";
							$html[] = "</div>";

							$html[] = "<div class='form-floating mb-3'>";
								$html[] = "<input type='text' name='mobile_number' id='mobileNumber' value='' class='form-control' />";
								$html[] = "<label for='mobileNumber'>Mobile Number</label>";
							$html[] = "</div>";
							
						$html[] = "</div>";
					$html[] = "</div>";

					

				$html[] = "</div>";
			$html[] = "</div>";

			$html[] = "<div class='card mb-3'>";
				$html[] = "<div class='card-body'>";
					
					$html[] = "<h3 class='card-title'>Account Details</h3>";

					$html[] = "<div class='form-floating mb-3'>";
						$html[] = "<select name='account_type' id='account_type' class='form-select'>";
							foreach($data['collection']['types'] as $type) {
								$sel = $type == "Registered User" ? "selected" : "";
								$html[] = "<option value='$type' $sel>$type</option>";
							}
						$html[] = "</select>";
						$html[] = "<label for='account_type'>Account Type</label>";
					$html[] = "</div>";

					$html[] = "<div class='form-floating mb-3'>";
						$html[] = "<input type='text' name='username' id='username' value='' class='form-control'  />";
						$html[] = "<label for='username'>Username</label>";
					$html[] = "</div>";

					$html[] = "<div class='form-floating mb-3'>";
						$html[] = "<input type='email' name='email' id='email' value='' class='form-control'  />";
						$html[] = "<label for='email'>Email</label>";
					$html[] = "</div>";

					$html[] = "<div class='form-floating mb-3'>";
						$html[] = "<select name='status' id='status' class='form-select'>";
							foreach($data['collection']['statuses'] as $statuses) {
								$html[] = "<option value='$statuses'>".ucwords(str_replace("_", " ", $statuses))."</option>";
							}
						$html[] = "</select>";
						$html[] = "<label for='status'>Status</label>";
					$html[] = "</div>";

				$html[] = "</div>";
			$html[] = "</div>";

			$html[] = "<div class='card mb-3'>";
				$html[] = "<div class='card-body'>";
					$html[] = "<div class='row'>";
						$html[] = "<div class='col-md-6 col-lg-6 col-sm-12 col-12'>";
							$html[] = "<h3 class='card-title mb-0'>Account Credentials</h3>";
							$html[] = "<span class='form-hint'>The account password.</span>";
							
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
											$checked = isset($data['default_permission'][$app]) && in_array($permission, $data['default_permission'][$app]) ? "checked" : "";
											$html[] = "<div class=''>";
												$html[] = "<div class='form-check form-switch'>";
													$html[] = "<input class='form-check-input' type='checkbox' name='permissions[$app][]' value='$permission' id='flexSwitchCheckDefault_".$permission."' $checked>";
													$html[] = "<label class='form-check-label' for='flexSwitchCheckDefault_".$permission."'>".ucwords(str_replace("_", " ", $permission))."</label>";
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