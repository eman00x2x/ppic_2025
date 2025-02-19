<?php

use EO\View;

View::setMasterTemplate(path: "/website/template.php");

/** Document Header Configuration */
View::setDocumentHeader(
	data: [
		'title' => CONFIG['site_name'] . ' Terms and Conditions',
		'description' => '',
		'url' => DOMAIN . url(),
		'image' => '',
		'modified_at' => DATE_NOW
	]
);

$html[] = "<div class='page-body bg-white'>";
	$html[] = "<div class='container'>";
		$html[] = "<div class='row'>";
			$html[] = "<div class='col-md-8'>";
				$html[] = "<h1>Terms and Conditions</h1>";
				$html[] = CONFIG['terms'];
			$html[] = "</div>";
		$html[] = "</div>";
	$html[] = "</div>";
$html[] = "</div>";