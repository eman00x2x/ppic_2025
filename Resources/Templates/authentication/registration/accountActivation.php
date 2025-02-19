<?php

use EO\View;

View::setMasterTemplate(path: "/authentication/template.php");

View::setDocumentHeader(
	data: [
		"title" => "Account Activation",
		"description" => "Account Activation"
	]
);

$html[] = "<div class='d-flex flex-column'>";
	$html[] = "<div class='page page-center mt-5'>";
		$html[] = "<div class='container container-normal py-5 mt-5'>";

			$html[] = "<div class='row align-items-center g-4'>";
				$html[] = "<div class='col-lg'>";
					$html[] = "<div class='container-tight'>";

						$html[] = "<div class='card'>";
							$html[] = "<div class='card-header bg-teal text-center'>";
								$html[] = "<h1 class='flex-fill text-white mb-0' style='font-size:95px;'><i class='ti ti-user-check me-2' ></i><span class='d-block fs-32 mt-3'>Account Activated</span></h1>";
							$html[] = "</div>";
							$html[] = "<div class='card-body'>";
								$html[] = "<h1>Congratulations!</h1>";	
								$html[] = "<p>Your account has been activated click <a href='".url("login")."'>here</a> to login</p>";
							$html[] = "</div>";
						$html[] = "</div>";
					
					$html[] = "</div>";
				$html[] = "</div>";
			$html[] = "</div>";

		$html[] = "</div>";
	$html[] = "</div>";
$html[] = "</div>";