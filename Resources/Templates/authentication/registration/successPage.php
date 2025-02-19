<?php

use EO\View;

View::setMasterTemplate(path: "/authentication/template.php");

View::setDocumentHeader(
	data: [
		"title" => "Account Registration Successfull",
		"description" => "Account Registration Successfull",
		"scripts" => [
		]
	]
);

$html[] = "<div class='page mt-5'>";
	$html[] = "<div class=' container-tight'>";
		
		$html[] = "<div class='card'>";
			$html[] = "<div class='card-header bg-teal text-center'>";
				$html[] = "<h1 class='flex-fill text-white mb-0' style='font-size:95px;'><i class='ti ti-user-check me-2' ></i><span class='d-block fs-32 mt-3'>Registration Completed</span></h1>";
			$html[] = "</div>";
			$html[] = "<div class='card-body text-center'>";
				$html[] = "<p>You have been successfully registered. To activate your account, Please check your registered email and click on the activation link.</p>";
				$html[] = "<a href='".url('login')."' class='btn btn-gray-100'>Sign-in here</a>";
			$html[] = "</div>";
		$html[] = "</div>";

	$html[] = "</div>";
$html[] = "</div>";