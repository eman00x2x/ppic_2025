<?php

$html[] = "<input type='hidden' id='reference_url' value='".url("LoginController@passwordResetSuccess")."' />";
$html[] = "<div class='d-flex flex-column'>";
	$html[] = "<div class='page page-center'>";
		$html[] = "<div class='container container-tight py-4'>";
			$html[] = "<div class='text-center mb-4 mt-5'>";
				$html[] = "<a href='".DOMAIN."' class='navbar-brand'><span class='d-block fs-30 fw-bold'>".CONFIG['site_name']."</span></a>";
				$html[] = "<span class='d-block'><b>Account Password Reset</b></span>";
			$html[] = "</div>";

			$html[] = "<div class='card'>";
			
				$html[] = "<div class='card-body p-6'>";
					$html[] = "<div class='card-status bg-blue'></div>";
					
					$html[] = "<div class='card-title'><i class='ti ti-check text-green'></i> Password Change</div>";
					$html[] = "<p>Your password was successfully change. You can now try <a href='".url("LoginController@getLoginForm")."'>logging in</a></p>";
					
				$html[] = "</div>";
			$html[] = "</div>";
		$html[] = "</div>";
	$html[] = "</div>";
$html[] = "</div>";