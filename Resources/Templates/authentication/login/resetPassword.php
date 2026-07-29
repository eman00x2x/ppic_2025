<?php

use EO\View;

View::setMasterTemplate(path: "/authentication/template.php");

View::setDocumentHeader(
	data: [
		"title" => "Password Reset",
		"description" => "Password Reset",
		"scripts" => [
			CDN . "/js/vendor/validatejs-0.13.1/validate.min.js",
			CDN . "/js/main/app/login.js"
		]
	]
);

$html[] = "<input type='hidden' id='reference_url' value='".url("passwordResetSuccess")."' />";
$html[] = "<div class='d-flex flex-column'>";
	$html[] = "<div class='page page-center'>";
		$html[] = "<div class='container container-tight py-4'>";
			$html[] = "<div class='text-center mb-4 mt-5'>";
				/* $html[] = "<a href='".DOMAIN."' class='navbar-brand'><span class='d-block fs-30 fw-bold'><i class='ti ti-building-skyscraper'></i> ".CONFIG['site_name']."</span></a>";
				$html[] = "<span class='d-block'><b>Account Password Reset</b></span>"; */
			$html[] = "</div>";

			$html[] = "<div class='card'>";
			
				$html[] = "<div class='card-body p-6'>";
					$html[] = "<div class='card-status bg-blue'></div>";
					
					if($data['expired']) {
						$html[] = "<div class='card-title'>Link Expired</div>";
						$html[] = "<p>Your password reset link has expire. Please <a href='".url("requestPasswordReset")."' title='Send Password Reset Link'>request another one</a> to reset your password.</p>";
					}else {
						$html[] = "<div class='card-title'>Reset your password</div>";
						$html[] = "<div class='response mb-4'></div>";
						
						$html[] = "<form id='form' action='".url("login.saveNewPassword")."' method='POST'>";
							$html[] = "<input name='account_id' id='account_id' type='hidden' value='".$data['account_id']."' />";
							
							$html[] = "<div class='mb-3'>";
								$html[] = "<label class='form-label'>New Password</label>";
								$html[] = "<input type='password' class='form-control' name='password' id='password'  placeholder='Enter password' autocomplete='off' required />";
							$html[] = "</div>";
							
							$html[] = "<div class='mb-3'>";
								$html[] = "<label class='form-label'>Confirm Password</label>";
								$html[] = "<input type='password' class='form-control' name='confirm_password' id='confirmPassword'  placeholder='Confirm password' autocomplete='off' required />";
							$html[] = "</div>";
						$html[] = "</form>";

						$html[] = "<div class='form-footer text-center mb-3'>";
							$html[] = "<span class='btn btn-outline-primary btn-save'><i class='ti ti-device-floppy'></i> &nbsp; Reset Password</span>";
						$html[] = "</div>";

						$html[] = "<p class='text-center'>";
							$html[] = "<span class='d-block mb-2'><a href='".url("login")."' class='text-decoration-none' title='MLS Login'><i class='ti ti-key'></i> Login here</a></span>";
						$html[] = "</p>";
						
					}
					
				$html[] = "</div>";
			$html[] = "</div>";
		$html[] = "</div>";
	$html[] = "</div>";
$html[] = "</div>";