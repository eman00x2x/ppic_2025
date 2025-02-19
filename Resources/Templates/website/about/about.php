<?php

use EO\View;

View::setMasterTemplate(path: "/website/template.php");

View::define( name: "propertyList", path: "/website/properties/list.template.php", data: $data );

/** Document Header Configuration */
View::setDocumentHeader(
	data: [
		'title' => 'About' . CONFIG['site_name'],
		'description' => ''
	]
);

$html[] = "<div class='page-body bg-white'>";
    $html[] = "<div class='container'>";
        $html[] = "<div class='row'>";
            $html[] = "<div class='col-lg-6 col-md-8 col-sm-12 col-12'>";
			
				$html[] = "<h1>About Us</h1>";
				$html[] = $data['about'];
			
			$html[] = "</div>";
		$html[] = "</div>";
	$html[] = "</div>";
$html[] = "</div>";