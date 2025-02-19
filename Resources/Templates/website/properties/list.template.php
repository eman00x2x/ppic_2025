<?php

foreach($data['properties'] as $property) {
	$html[] = "<div class='card mb-3 rounded-0'>";
		$html[] = "<div class='row g-0'>";
			$html[] = "<div class='col-12 col-md-5 col-lg-4 col-xl-4'>";
				
				$html[] = "<div class='property-image-container avatar avatar-xxxxl w-100 rounded-0' style=\"background-image:url('".$property['thumb_img']."');\">";
					$html[] = "<div class='image-overlay border-0'>";
						$html[] = "<div class='d-flex justify-content-between image-overlay-content text-white fs-12'>";
							$html[] = "<div class='bottom-text'>";
								$html[] = "<i class='ti ti-map-pin'></i> ".$property['short_address']."";
							$html[] = "</div>";
							$html[] = "<div class='bottom-text w-25 text-end'>";
								$html[] = "<i class='ti ti-photo'></i> ".$property['total_images']."";
							$html[] = "</div>";
						$html[] = "</div>";
					$html[] = "</div>";
				$html[] = "</div>";
				
			$html[] = "</div>";
			$html[] = "<div class='col-12 col-md-7 col-lg-8 col-xl-8'>";
				$html[] = "<div class='card-body'>";
					$html[] = "<h3 class='card-title text-primary mb-1 fs-18'>".$property['title']."</h3>";
					$html[] = "<p class='card-text text-muted mb-3 fs-14'><i class='ti ti-home me-1'></i> ".$property['category']." ".($property['address']['village'] != "" ?  "at ".$property['address']['village'] : "")."</p>";
					$html[] = "<p class='card-text text-dark fw-bold m-0 mb-3 price_tag'>&#8369; ".$property['price_tag']."</p>";
					$html[] = "<ul class='list-group list-group-horizontal'>";
						if($property['lot_area'] != "" && $property['lot_area'] > 0) { $html[] = "<li class='list-group-item border-0 text-center p-2 ps-0 px-4'><i class='ti ti-maximize me-1'></i> ".number_format($property['lot_area'], 0)."<span class='text-muted fs-14'>m<sup>2</sup></span> <span class='d-block text-muted fs-12'>Lot Area</span></li>"; }
						if($property['floor_area'] != "" && $property['floor_area'] > 0) { $html[] = "<li class='list-group-item border-0 text-center p-2 ps-0 px-4'><i class='ti ti-ruler me-1'></i> ".number_format($property['floor_area'], 0)."<span class='text-muted fs-14'>m<sup>2</sup></span> <span class='d-block text-muted fs-12'>Floor Area</span></li>"; }
						if($property['bedroom'] != "" && $property['bedroom'] > 0) { $html[] = "<li class='list-group-item border-0 text-center p-2 ps-0 px-4'>".$property['bedroom']." <i class='ti ti-bed me-1'></i> <span class='d-block text-muted fs-12'>Bed rooms</span></li>"; }
						if($property['parking'] != "" && $property['parking'] > 0) { $html[] = "<li class='list-group-item border-0 text-center p-2 ps-0 px-4'>".$property['parking']." <i class='ti ti-car-garage me-1'></i> <span class='d-block text-muted fs-12'>Car Spaces</span></li>"; }
					$html[] = "</ul>";
				$html[] = "</div>";
				$html[] = "<div class='card-footer pt-0 mt-0 border-0'>";
					$html[] = "<a href='".$property['url']."' class='stretched-link w-100' title='".$property['title']."'></a>";
				$html[] = "</div>";
			$html[] = "</div>";
		$html[] = "</div>";
	$html[] = "</div>";
}