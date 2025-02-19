<?php

use EO\View;

View::define( name: "relatedProperties", path: "/website/properties/vertical.list.template.php", data: $data );

/* ob_start();
print_r($data);
$page = ob_get_contents();
ob_end_clean();

$html[] = "<pre>";
	$html[] = $page;
$html[] = "</pre>"; */

$html[] = "<div class='properties related-properties'>";
	if(!empty($data['properties'])) {
		$html[] = View::include("relatedProperties");
	}else {
		$html[] = "<div class=''>";
			$html[] = "<div class='empty'>";
				$html[] = "<div class='empty-image mb-4'>";
					$html[] = "<img src='".CDN."/images/undraw_quitting_time_dm8t.svg' height='128' />";
				$html[] = "</div>";
				$html[] = "<p class='empty-title'>No related properties found!</p>";
			$html[] = "</div>";
		$html[] = "</div>";
	}
$html[] = "</div>";
