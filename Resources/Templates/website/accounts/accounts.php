<?php

use EO\View;

View::setMasterTemplate(path: "/website/template.php");

/** Document Header Configuration */
View::setDocumentHeader(
	data: [
		'title' => CONFIG['site_name'] . ' Team',
		'description' => '',
		'url' => DOMAIN . url(),
		'image' => '',
		'modified_at' => DATE_NOW
	]
);

$html[] = "<div class='page-body'>";
    $html[] = "<div class='container-xl'>";

		$html[] = "<h1>Our Team</h1>";
		$html[] = "<p>At ".CONFIG['site_name'].", our dedicated real estate brokers and sales professionals are committed to delivering exceptional service. With deep market knowledge and a client-first approach, we guide you through every step of buying, selling, or investing in property. Trust our expert team to turn your real estate goals into reality.</p>";

		$html[] = "<div class=''>";
			$html[] = "<div class='row row-cards'>";
				if(!empty($data['accounts'])) {
					for($i = 0; $i < count($data['accounts']); $i++) {
						if ($data['accounts'][$i]['account_id'] != 22) {
							$html[] = "<div class='col-md-6 col-lg-3'>";
								$html[] = "<div class='card'>";
									$html[] = "<div class='card-body p-4 text-center'>";
										$html[] = "<span class='avatar avatar-xl mb-3 rounded' style='background-image: url(".$data['accounts'][$i]['photo'].")'></span>";
										$html[] = "<h3 class='m-0 mb-1'><a href='#'>".$data['accounts'][$i]['fullname']."</a></h3>";
									$html[] = "</div>";
								$html[] = "</div>";
							$html[] = "</div>";
						}
					}
				}
			$html[] = "</div>";

			$html[] = View::getPaginationTemplate();
			
		$html[] = "</div>";

	$html[] = "</div>";
$html[] = "</div>";