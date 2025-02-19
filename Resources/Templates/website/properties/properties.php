<?php

use EO\View;

View::setMasterTemplate(path: "/website/template.php");

View::define( name: "property_list", path: "/website/properties/list.template.php", data: $data );
View::define( name: "contact_form", path: "/website/includes/contact.form.php", data: $data );

/** Document Header Configuration */
View::setDocumentHeader(
	data: [
		'title' => CONFIG['site_name'] . ' Properties',
		'description' => '',
		'url' => DOMAIN . url(),
		'image' => '',
		'modified_at' => DATE_NOW,
		"scripts" => [
			CDN . "/js/vendor/wnumb-1.2.0/wNumb.min.js",
			CDN . "/js/vendor/tabler/dist/libs/nouislider/dist/nouislider.min.js?1724846371",
			CDN . "/js/vendor/validatejs-0.13.1/validate.min.js",
		]
	]
);

$request = View::$collections['urlParameters'] ?? [];

$html[] = "<div class='modal ' id='modalFilterForm' aria-labelledby='modalFilterFormLabel'>";
	$html[] = "<div class='modal-dialog modal-fullscreen'>";
		$html[] = "<div class='modal-content bg-primary'>";

			$html[] = "<div class='modal-body'>";
				$html[] = "<button type='button' class='btn-close text-white fs-20' data-bs-dismiss='modal' aria-label='Close'>X</button>";
				$html[] = "<div class='filter-modal-container'></div>";
			$html[] = "</div>";

		$html[] = "</div>";
	$html[] = "</div>";
$html[] = "</div>";

$html[] = "<div class='page-body'>";
	$html[] = "<div class='container'>";

		$html[] = "<div class='row properties'>";
			$html[] = "<div class='d-none d-md-block col-md-4 col-lg-4 col-xl-3'>";

				$html[] = "<div class='filter-container sticky-top d-none d-md-block'>";
					$html[] = "<div class='filter-fields' id='filterFormBody'>";

						$html[] = "<div class='card bg-primary text-white'>";
							$html[] = "<div class='card-body'>";
								$html[] = "<h3 class='mb-3 fw-bold fs-18 text-white'><i class='ti ti-filter'></i> Filter your results</h3>";
								
								$html[] = "<form id='filterForm' action='".url(null, null, [])."' method='get'>";

									$html[] = "<div class='mb-2'>";
										$html[] = "<p class='mb-3'>You are filtering <span class='fw-bold'>".(url()->contains("buy") ? "For Sale" : "For Rent")." Properties</span></p>";
										
										$html[] = "<div class='d-flex justify-content-between align-items-center mt-3 '>";
											$html[] = "<div class='flex-fill'>";
												if(url()->contains("buy")) {
													$html[] = "<a href='".url("/rent")."' class='fs-12 p-1 btn btn-outline-light'>Search rental properties</a>";
												}else {
													$html[] = "<a href='".url("/buy")."' class='fs-12 p-1 btn btn-outline-light'>Search for sale properties</a>";
												}
											$html[] = "</div>";
											$html[] = "<div class=''><a href='".url(null, null, [])."' class='btn p-1 btn-outline-secondary border-0 fs-12'><i class='ti ti-x me-1'></i> Clear Filter</a></div>";
										$html[] = "</div>";
									$html[] = "</div>";

									$html[] = "<div class='mb-3'>";
										$html[] = "<label class='text-muted mb-2 fs-12'>Category</label>";
										$html[] = "<select name='category' class='form-select'>";
											$html[] = "<option value=''>Select Category</option>";
											foreach($data['collections']['categories'] as $groupName => $categories) {
												$html[] = "<optgroup label='".$groupName."'>";
												foreach($categories as $category) {
													$sel = isset($request['category']) && $request['category'] == $category ? "selected" : "";
													$html[] = "<option value='".$category."' $sel>".$category."</option>";
												}
												$html[] = "</optgroup>";
											}
										$html[] = "</select>";
									$html[] = "</div>";

									$html[] = "<div class='mb-2'>";
										$html[] = "<div class='fs-12'>";
											$html[] = "<label class='text-muted'>Price Range</label>";
											$html[] = "<div class='mt-2'>";
												$html[] = "<div class='slider-display' data-min='1000000' data-max='300000000' data-steps='1000000' data-input-from-id='priceFrom' data-input-to-id='priceTo'></div>";
											$html[] = "</div>";
										$html[] = "</div>";
									$html[] = "</div>";

									$html[] = "<div class='d-flex justify-content-between gap-2 mb-2'>";
										$html[] = "<div class=''>";
											$html[] = "<label class='text-muted mb-2 fs-12'>Land Area</label>";
											$html[] = "<select name='lot_area' id='lot_area' class='form-select'>";
												$html[] = "<option value=''>Land Area</option>";
												foreach(["0 - Below 100sqm", "101sqm - 200sqm", "201sqm - 300sqm", "301sqm - 400sqm", "401sqm - 500sqm", "501sqm - 1000sqm", "1001sqm - 2000sqm", "2001sqm - 5000sqm", "5001sqm - 10000sqm", "10001sqm and above - 00",] as $range) {
													
													$land_area = trim(str_replace(["Below", "and above", "sqm", " "],["","", "", ""], $range));
													
													$area = explode(" - ", str_replace(["sqm", "Below", "and above"],"", $range));
													$r1 = ($area[0] == 0 ? "Below" : number_format($area[0],0)."sqm");
													$r2 = ($area[1] == "00" ? "above " : number_format($area[1],0)."sqm");

													$sel = isset($request['lot_area']) && $request['lot_area'] == $land_area ? "selected" : "";
													$html[] = "<option value='".$land_area."' $sel>".$r1." - ".$r2."</option>";
												}
											$html[] = "</select>";
										$html[] = "</div>";

										$html[] = "<div class=''>";
											$html[] = "<label class='text-muted mb-2 fs-12'>Floor Area</label>";
											$html[] = "<select name='floor_area' id='floor_area' class='form-select'>";
												$html[] = "<option value=''>Floor Area</option>";
												foreach(["0 - Below 100sqm", "101sqm - 200sqm", "201sqm - 300sqm", "301sqm - 400sqm", "401sqm - 500sqm", "501sqm - 1000sqm", "1001sqm - 2000sqm", "2001sqm - 5000sqm", "5001sqm - 10000sqm", "10001sqm and above - 00",] as $range) {
													
													$floor_area = trim(str_replace(["Below", "and above", "sqm", " "],["","", "", ""], $range));
													
													$area = explode(" - ", str_replace(["sqm", "Below", "and above"],"", $range));
													$r1 = ($area[0] == 0 ? "Below" : number_format($area[0],0)." sqm");
													$r2 = ($area[1] == "00" ? "above " : number_format($area[1],0)." sqm");

													$sel = isset($request['floor_area']) && $request['floor_area'] == $floor_area ? "selected" : "";
													$html[] = "<option value='".$floor_area."' $sel>".$r1." - ".$r2."</option>";
												}
											$html[] = "</select>";
										$html[] = "</div>";
									$html[] = "</div>";

									$html[] = "<div class='d-flex justify-content-between gap-2 mb-2'>";
										$html[] = "<div class=''>";
											$html[] = "<label class='text-muted mb-2 fs-12'>Bed room</label>";
											$html[] = "<select name='bedroom' class='form-select'>";
												$html[] = "<option value=''>Bed room</option>";
												foreach(["Studio", "1 Bed room", "2 Bed room", "3 Bed room", "4 Bed room", "5 Bed room", "6 and more Bed room"] as $room) {
													$bedroom_val = trim(str_replace([" Bed room", " and more"],["", ""], $room));
													$sel = isset($request['bedroom']) && $request['bedroom'] == $bedroom_val ? "selected" : "";
													$html[] = "<option value='".$bedroom_val."' $sel>".$room."(s)</option>";
												}
											$html[] = "</select>";
										$html[] = "</div>";

										$html[] = "<div class=''>";
											$html[] = "<label class='text-muted mb-2 fs-12'>Toilet and Bath</label>";
											$html[] = "<select name='bathroom' class='form-select'>";
												$html[] = "<option value=''>Toilet and Bath</option>";
												foreach(["1", "2", "3", "4", "5", "6 and more"] as $room) {
													$bathroom = trim(str_replace(["and more"],[""], $room));
													$sel = isset($request['bathroom']) && $request['bathroom'] == $bathroom ? "selected" : "";
													$html[] = "<option value='".$bathroom."' $sel>".$room." Toilet and Bath</option>";
												}
											$html[] = "</select>";
										$html[] = "</div>";
									$html[] = "</div>";

									$html[] = "<div class='mb-2'>";
										$html[] = "<label class='text-muted mb-2 fs-12'>Garage</label>";
										$html[] = "<select name='parking' id='parking' class='form-select'>";
											$html[] = "<option value=''>Available Car space</option>";
											foreach(["1 Car Space", "2 Car Space", "3 Car Space", "4 Car Space", "5 Car Space", "6 and more Car Space"] as $space) {
												$parking = trim(str_replace(["Car Space", "and more"],["",""], $space));
												$sel = isset($request['parking']) && $request['parking'] == $parking ? "selected" : "";
												$html[] = "<option value='".$parking."' $sel>$space</option>";
											}
										$html[] = "</select>";
									$html[] = "</div>";

									$html[] = "<div class='mb-2'>";
										$html[] = "<label class='text-muted mb-2 fs-12'>Include foreclosure properties?</label>";
										$html[] = "<div class='form-check form-switch'>";
											$html[] = "<input class='form-check-input cursor-pointer' type='checkbox' name='foreclosure' value='1' id='foreclosure' ".(isset($request['foreclosure']) ? "checked" : "").">";
											$html[] = "<label class='form-check-label cursor-pointer' for='foreclosure'>Yes</label>";
										$html[] = "</div>";
									$html[] = "</div>";

									
									$html[] = "<div class='mt-3'><span class='btn btn-outline-light btn-filter w-100'><i class='ti ti-filter me-1'></i> Filter Result</span></div>";
									

								$html[] = "</form>";
							$html[] = "</div>";
						$html[] = "</div>";

					$html[] = "</div>";
				$html[] = "</div>";

			$html[] = "</div>";

			$html[] = "<div class='col-12 col-md-8 col-lg-8 col-xl-9'>";

				$html[] = "<div class='d-flex justify-content-between align-items-center mb-3'>";
					$html[] = "<div class=''>";
						$html[] = "<h1 class='mb-2'>Properties</h1>";
						$html[] = "<p class='fs-14 text-muted mb-1'>Found ".View::$collections['totalRows']." properties ".(url()->contains("buy") ? "for sale" : "for rent")."</p>";
					$html[] = "</div>";

					$html[] = "<div class='d-flex align-items-center'>";

						$html[] = "<span class='btn btn-light d-md-none d-sm-block' data-bs-toggle='modal' data-bs-target='#modalFilterForm'>Filter</span>";
						
						$html[] = "<div class='dropdown sort-wrapper ms-2'>";
							$html[] = "<span class='btn btn-light dropdown-toggle ' data-bs-toggle='dropdown'>Sort by</span>";
							$html[] = "<div class='dropdown-menu'>";

								foreach($data['collections']['sorting_fields'] as $args) {
									$html[] = "<a class='dropdown-item d-block' href='".url(null, null, $args['uri'])."'>";
										$html[] = "<div class='d-flex justify-content-between align-items-center'>";
											$html[] = "<span class='d-block'>".$args['field']."</span>";
											$html[] = "<span class='d-block text-muted fs-11'>".$args['direction']."</span>";
										$html[] = "</div>";
									$html[] = "</a>";
								}

							$html[] = "</div>";
						$html[] = "</div>";
						
					$html[] = "</div>";
				$html[] = "</div>";

				if(!empty($data['properties'])) {
					$html[] = View::include("property_list");
				}else {
					$html[] = "<div class=''>";
						$html[] = "<div class='empty'>";
							$html[] = "<div class='empty-image mb-4'>";
								$html[] = "<img src='".CDN."/images/undraw_quitting_time_dm8t.svg' height='128' />";
							$html[] = "</div>";
							$html[] = "<p class='empty-title'>No results found</p>";
							$html[] = "<p class='empty-subtitle text-secondary'>Try adjusting your search or filter to find what you're looking for.</p>";
						$html[] = "</div>";
					$html[] = "</div>";
				}
				$html[] = View::getPaginationTemplate();

			$html[] = "</div>";
		$html[] = "</div>";
	$html[] = "</div>";

	
	$html[] = "<div class='mt-5 py-5 border-top bg-light'>";
		$html[] = "<div class='container'>";
			$html[] = "<div class='row justify-content-center'>";
				$html[] = "<div class='col-md-4 col-12'>";
					$html[] = "<div class='text-center mb-4'>";
						$html[] = "<h2 class='mb-0'>Let us help you</h2>";
						$html[] = "<p>Use the form below to send us a message.</p>";
					$html[] = "</div>";

					$html[] = View::include("contact_form");
				$html[] = "</div>";
			$html[] = "</div>";
		$html[] = "</div>";
	$html[] = "</div>";

$html[] = "</div>";