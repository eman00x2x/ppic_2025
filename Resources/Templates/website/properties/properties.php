<?php

$html[] = "<div class='spacer' style='margin-top:100px;'></div>";

$html[] = "<div class='container'>";

	$html[] = "<h1 class='mb-4'>".count($data)." Results found ".(isset($_REQUEST['category']) ? ($_REQUEST['category'] == "Any" ? "Property" : "for ".$_REQUEST['category']) : "for Condominium, House and Lot and Townhouses")." For Sale in ".(isset($_REQUEST['address']) ? ($_REQUEST['address'] != "" ? ($_REQUEST['address']) : "  Metro Manila and Nearby Provinces") : " Metro Manila and Nearby Provinces")."</h1>";
	
	$html[] = "<div class='row'>";
		$html[] = "<div class='col-lg-3 col-md-3'>";
			$html[] = "<div class='filter-wrap border p-3 sticky-top sticky-offset sticky-adjust d-none d-md-block'>";
				
				$html[] = "<h4 class='mb-3'>FILTER YOUR RESULTS</h4>";
				
				$html[] = "<div class='border-bottom mb-3'>";
					$html[] = "<div class='form-group'>";
						$html[] = "<label>Keyword or Place</label>";
						$html[] = "<input type='text' name='keyword' id='keyword-search-listings' value='' class='keyword-search-listings form-control' autocomplete='off' />";
					$html[] = "</div>";
				$html[] = "</div>";
				
				
				$html[] = "<div class='border-bottom mb-3'>";
					$html[] = "<div class='form-group'>";
						$html[] = "<label>OFFER TYPE</label>";
						$offer_types = array("For_Sale","Forclosure");
						foreach($offer_types as $type) {
							$sel = $type == "For_Sale" ? "checked" : "";
							$html[] = "<div class='custom-control custom-radio'>";
								$html[] = "<input type='radio' id='type-$type' name='type' value='".strtolower($type)."' class='type custom-control-input' $sel />";
								$html[] = "<label class='custom-control-label' for='type-$type'>".str_replace("_"," ",$type)."</label>";
							$html[] = "</div>";
						}
						
					$html[] = "</div>";
					
					$html[] = "<small><a href='".url("rent")."' class='mb-3 d-block'>Do you want to rent? Search now!</a></small>";
					
				$html[] = "</div>";
				
				$html[] = "<div class='border-bottom mb-3'>";
					$html[] = "<label>PRICE</label>";
					$html[] = "<div class='row'>";
						$html[] = "<div class='col-md-6'>";
							$html[] = "<div class='form-group'>";
								$html[] = "<input type='text' name='min-price' id='min-price' value='' placeholder='Minimum' class='min-price form-control' />";
							$html[] = "</div>";
						$html[] = "</div>";
						$html[] = "<div class='col-md-6'>";
							$html[] = "<div class='form-group'>";
								$html[] = "<input type='text' name='max-price' id='max-price' value='' placeholder='Maximum' class='max-price form-control' />";
							$html[] = "</div>";
						$html[] = "</div>";
					$html[] = "</div>";
				$html[] = "</div>";
				
				$html[] = "<div class='border-bottom mb-3'>";
					$html[] = "<div class='form-group'>";
						$html[] = "<label>PROPERTY TYPE</label>";
						$categories = array("Any","Commercial","Land","Townhouse","House and Lot","Condominium");
						foreach($categories as $category) {
							$sel = $category == "Any" ? "checked" : "";
							$html[] = "<div class='custom-control custom-radio'>";
								$html[] = "<input type='radio' id='category-$category' name='category' value='$category' class='category custom-control-input' $sel />";
								$html[] = "<label class='custom-control-label' for='category-$category'>$category</label>";
							$html[] = "</div>";
						}
					$html[] = "</div>";
				$html[] = "</div>";
				
				$html[] = "<span class='btn btn-sm btn-primary d-block btn-filter-result' data-url='".url("buy")."'>APPLY FILTER</span>";
				
			$html[] = "</div>";
		$html[] = "</div>";
		
		$html[] = "<div class='col-lg-9 col-md-9'>";
		
			$html[] = "<div class='listings'>";
				
				if($data) {
					for($i=0; $i<count($data); $i++) {
					
						$file = ROOT.DS."images/properties/".$data[$i]['thumb_img'];
						if(file_exists($file)) {
							$thumb_img = CDN."/images/properties/".$data[$i]['thumb_img'];
						}else {
							$thumb_img = CDN."/images/item_default.jpg.png";
						}
					
						$html[] = "<div class='border p-2 mb-3'>";
							$html[] = "<div class='row'>";
								$html[] = "<div class='col-md-5'>";
									$html[] = "<a href='".$data[$i]['url']."' title='".$data[$i]['title']."'>";
										$html[] = "<div class='img-wrap mb-3' style=\"background-image:url('".$thumb_img."')\">";
										$html[] = "</div>";
									$html[] = "</a>";
								$html[] = "</div>";
								
								$html[] = "<div class='col-md-7'>";
									$html[] = "<p class='title p-0 m-0'><a href='".$data[$i]['url']."' title='".$data[$i]['title']."'>".$data[$i]['title']."</a></p>";
									$html[] = "<span class='address d-block'>".($data[$i]['full_address'])."</span>";
									
									$html[] = "<p style='font-size:14px;' class='mt-2'>";
									
									if($data[$i]['bedroom'] == 0 || $data[$i]['bedroom'] == "") {}else {
										$html[] = $data[$i]['bedroom']." bedroom ";
									}
									
									if($data[$i]['category'] != "") {
										$html[] = $data[$i]['category'];
									}
									
									$html[] = " with ";
									
									if($data[$i]['floor_area'] == 0 || $data[$i]['floor_area'] == "") {}else {
										$html[] = ",floor area of ".$data[$i]['floor_area']."sqm ";
									}
									
									if($data[$i]['lot_area'] == 0 || $data[$i]['lot_area'] == "") {}else {
										$html[] = ",lot area of ".$data[$i]['lot_area']."sqm ";
									}
									
									$html[] = $data[$i]['type'];
									$html[] = " in ".($data[$i]['full_address']);
									
									$html[] = "</p>";
									
									$html[] = "<span class='price d-block mt-2'>&#8369; ".number_format($data[$i]['price'],0)."</span>";
									
									$html[] = "<div class='payment-details'>";
										$html[] = "<div class='row'>";
											
											if($data[$i]['reservation'] > 0) {
												$html[] = "<div class='col-md-4'>";
													$html[] = "<label>Reservation</label>";
													$html[] = "<span class='d-block p-0 m-0'>&#8369; ".number_format($data[$i]['reservation'],0)."</span>";
												$html[] = "</div>";
											}
											
										$html[] = "</div>";
									$html[] = "</div>";
									
									$html[] = "<div class='technical-details mt-2'>";
										$html[] = "<p>";
											if($data[$i]['bedroom'] == "" || $data[$i]['bedroom'] == 0) {}else {$html[] = $data[$i]['bedroom']." <span>Bedrooms</span>&nbsp;";}
											if($data[$i]['bathroom'] == "" || $data[$i]['bathroom'] == 0) {}else {$html[] = $data[$i]['bathroom']." <span>Baths</span>&nbsp;";}
											if($data[$i]['floor_area'] == "" || $data[$i]['floor_area'] == 0) {}else {$html[] = $data[$i]['floor_area']."sqm <span>Floor Area</span>&nbsp;";}
											if($data[$i]['lot_area'] == "" || $data[$i]['lot_area'] == 0) {}else {$html[] = $data[$i]['lot_area']."sqm <span>Lot Area</span>";}
										$html[] = "</p>";
									$html[] = "</div>";
									
								$html[] = "</div>";
							$html[] = "</div>";
							
							$html[] = "<span class='addToCompare-btn btn btn-sm btn-outline-secondary p-1' data-id='".$data[$i]['property_id']."' data-url='".url()."'><span class='glyphicon glyphicon-plus'></span> Compare</span>";
							
						$html[] = "</div>";
						
					}
					
				}else {
					$html[] = "<p>No property listings found in this category. <br/>";
						if(isset($_REQUEST['full_address'])) {
							$html[] = " <a href='".url("buy")."' class='mt-3 btn btn-outline-primary '>Reset your filter</a>";
						}
					$html[] = "</p>";
				}
			
			
			$html[] = "</div>";
		$html[] = "</div>";
	$html[] = "</div>";
	
$html[] = "</div>";