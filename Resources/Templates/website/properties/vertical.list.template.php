<?php

$html[] = "<div class='row'>";
	if($data['properties']) {
		for($i=0; $i<count($data['properties']); $i++) {
			
			$html[] = "<div class='col-12 col-md-3 col-lg-3 col-xl-3 mb-3'>";
				$html[] = "<div class='card'>";
					$html[] = "<a href='".$data['properties'][$i]['url']."' title='".$data['properties'][$i]['title']."' class='text-decoration-none text-dark'>";
						$html[] = "<div class='card-img-top property-image-container avatar avatar-xxxxl w-100 rounded-0' style=\"background-image:url('".$data['properties'][$i]['thumb_img']."');\">";
							$html[] = "<div class='image-overlay border-0'>";
								$html[] = "<div class='d-flex justify-content-between image-overlay-content text-white fs-14'>";
									$html[] = "<div class='bottom-text'>";
										$html[] = "<i class='ti ti-map-pin me-1'></i> ".$data['properties'][$i]['short_address']."";
									$html[] = "</div>";
									$html[] = "<div class='bottom-text w-25 text-end'>";
										$html[] = "<i class='ti ti-photo me-1'></i> ".$data['properties'][$i]['total_images']."";
									$html[] = "</div>";
								$html[] = "</div>";
							$html[] = "</div>";
						$html[] = "</div>";
					$html[] = "</a>";
					$html[] = "<div class='card-body position-relative'>";
						$html[] = "<h3 class='card-title text-primary mb-2'>".$data['properties'][$i]['short_title']."</h3>";
						$html[] = "<p class='card-text price_tag fw-bold'>&#8369; ".$data['properties'][$i]['price_tag']."</p>";
						$html[] = "<div class='position-absolute bottom-0 w-100 pe-5'>";
							$html[] = "<div class='d-flex justify-content-between fs-13'>";
								if($data['properties'][$i]['lot_area'] != "" && $data['properties'][$i]['lot_area'] > 0) { 		$html[] = "<div class=''><i class='ti ti-maximize me-1'></i> ".number_format($data['properties'][$i]['lot_area'], 0)."<span class='text-muted'>m<sup>2</sup></span> <span class='d-block text-muted fs-10'>Lot Area</span></div>"; }
								if($data['properties'][$i]['floor_area'] != "" && $data['properties'][$i]['floor_area'] > 0) {	$html[] = "<div class=''><i class='ti ti-ruler me-1'></i> ".number_format($data['properties'][$i]['floor_area'], 0)."<span class='text-muted'>m<sup>2</sup></span> <span class='d-block text-muted fs-10'>Floor Area</span></div>"; }
								if($data['properties'][$i]['bedroom'] != "" && $data['properties'][$i]['bedroom'] > 0) { 		$html[] = "<div class=''>".$data['properties'][$i]['bedroom']." <i class='ti ti-bed me-1'></i> <span class='d-block text-muted fs-10'>Bed rooms</span></div>"; }
								if($data['properties'][$i]['parking'] != "" && $data['properties'][$i]['parking'] > 0) { 		$html[] = "<div class=''>".$data['properties'][$i]['parking']." <i class='ti ti-car-garage me-1'></i> <span class='d-block text-muted fs-10'>Car Spaces</span></div>"; }
							$html[] = "</div>";
						$html[] = "</div>";
					$html[] = "</div>";
					$html[] = "<div class='card-footer pt-0 mt-0 border-0'>";
						$html[] = "<a href='".$data['properties'][$i]['url']."' title='".$data['properties'][$i]['title']."' class='stretched-link w-100'></a>";
					$html[] = "</div>";
				$html[] = "</div>";
			$html[] = "</div>";
		}
	}else {
		$html[] = "<div class='alert alert-warning'>No properties found!</div>";
	}
$html[] = "</div>";