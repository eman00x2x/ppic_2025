<?php

use EO\View;

View::setMasterTemplate(path: "/website/template.php");

/** Document Header Configuration */
View::setDocumentHeader(
	data: [
		'title' => CONFIG['site_name'] . ' Videos',
		'description' => '',
		'url' => DOMAIN . url("web.videos"),
		'image' => '',
		'modified_at' => DATE_NOW
	]
);

$html[] = "<div class='page-body bg-white'>";
    $html[] = "<div class='container-xl'>";

		$html[] = "<div class=''>";
			$html[] = "<div class='row'>";
				$html[] = "<div class='col-lg-9 col-md-8 col-sm-12 col-12 order-md-1 order-2'>";
					$html[] = "<div class=''>";
						$html[] = "<h1><i class='ti ti-brand-youtube me-1'></i> Videos</h1>";
						if($data['videos']) {
							$html[] = "<div class='row'>";
								for($i=0; $i<count($data['videos']); $i++) {
									$html[] = "<div class='col-lg-4 col-md-6 col-sm-12 col-12'>";
										$html[] = "<div class='card mb-4'>";
											$html[] = "<div class='img-responsive img-responsive-21x9 card-img-top bg-primary-lt' style='height:200px; background-image: url(".$data['videos'][$i]['thumbnail']['mq'].");'><i class='ti ti-brand-youtube' style='font-size: 5rem !important; color: #fff; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);'></i></div>";
											$html[] = "<div class='card-footer p-0 border-0'>";
												$html[] = "<a href='javascript:void(0);' class='stretched-link w-100 btn-playback' data-embed='".$data['videos'][$i]['embed']."' data-url='".$data['videos'][$i]['url']."' data-id='".$data['videos'][$i]['unique_id']."'></a>";
											$html[] = "</div>";
										$html[] = "</div>";
									$html[] = "</div>";
								}
							$html[] = "</div>";
							$html[] = View::getPaginationTemplate();

						}else {
							$html[] = "<div class=''>";
								$html[] = "<div class='empty'>";
									$html[] = "<div class='empty-image mb-4'>";
										$html[] = "<img src='".CDN."/images/undraw_quitting_time_dm8t.svg' height='128' />";
									$html[] = "</div>";
									$html[] = "<p class='empty-title'>No videos found!</p>";
								$html[] = "</div>";
							$html[] = "</div>";
						}
					$html[] = "</div>";
				$html[] = "</div>";
				$html[] = "<div class='col-lg-3 col-md-4 col-sm-12 col-12 order-md-2 order-1'>";
					$html[] = "<div class='mb-4 sticky-md-top'>";
						$html[] = "<div class=''>";
							
						$html[] = "</div>";
					$html[] = "</div>";

				$html[] = "</div>";
			$html[] = "</div>";
		$html[] = "</div>";

    $html[] = "</div>";
$html[] = "</div>";