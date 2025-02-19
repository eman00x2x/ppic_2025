<?php

use EO\View;

View::setMasterTemplate(path: "/authentication/template.php");

View::setDocumentHeader(
	data: [
		"title" => "Send Password Reset Link",
		"description" => "Send Password Reset Link",
		"scripts" => [
			CDN . "/js/vendor/validatejs-0.13.1/validate.min.js",
			CDN . "/js/main/app/login.js"
		]
	]
);

$html[] = "<div class='d-flex flex-column'>";
	$html[] = "<div class='page page-center'>";
		$html[] = "<div class='container container-tight py-4'>";
			$html[] = "<div class='text-center mb-4 mt-5'>";
				/* $html[] = "<a href='".ADMIN."' class='navbar-brand'><span class='d-block fs-30 fw-bold'><i class='ti ti-building-skyscraper'></i> ".CONFIG['site_name']."</span></a>";
				$html[] = "<span class='d-block'><b>Account Password Reset</b></span>"; */
			$html[] = "</div>";

			$html[] = "<form id='form' class='card card-md' action='".url("registration.resendActivationEmail")."' method='POST' autocomplete='off'>";
				
				$html[] = "<div class='card-body'>";
					$html[] = "<h2 class='card-title text-center mb-4'><i class='ti ti-mail-fast me-1 fs-22'></i> Resend Email Activation</h2>";
					$html[] = "<p class='text-secondary mb-4'>Enter your email address and your Email Activation link will be emailed to you.</p>";

					$html[] = "<div class='response mb-4'></div>";

					$html[] = "<div class='mb-3'>";
						$html[] = "<label class='form-label'><i class='ti ti-email'></i> Registered email address</label>";
						$html[] = "<input type='email' class='form-control' name='email' id='email'  placeholder='Enter registered email' autocomplete='off' role='presentation' required />";
					$html[] = "</div>";

					$html[] = "<div class='form-footer'>";
						$html[] = "<span class='btn btn-primary w-100 btn-verify-email'><i class='ti ti-send'></i> &nbsp; Send Email Activation Link</span>";
					$html[] = "</div>";
				$html[] = "</div>";
			$html[] = "</form>";

			$html[] = "<div class='text-center text-secondary mt-3'>";
				$html[] = "Forget it, <a href='".url("LoginController@getLoginForm")."'>send me back</a> to the sign in screen.";
			$html[] = "</div>";

		$html[] = "</div>";
	$html[] = "</div>";
$html[] = "</div>";