<?php

use EO\View;

View::setMasterTemplate(path: "/website/template.php");

/** Document Header Configuration */
View::setDocumentHeader(
	data: [
		'title' => CONFIG['site_name'] . ' Privacy Policy',
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
				$html[] = "<h1>Privacy Policy</h1>";
				$html[] = CONFIG['data_privacy'];
			$html[] = "</div>";
		$html[] = "</div>";
	$html[] = "</div>";
$html[] = "</div>";