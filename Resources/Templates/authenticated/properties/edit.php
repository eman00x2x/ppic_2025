<?php

use EO\View;
use EO\Auth\Auth as Auth;

View::setMasterTemplate(path: "/authenticated/template.php");

/** Document Header Configuration */
View::setDocumentHeader(
	data: [
		"title" => "Edit Property",
		"description" => "Edit Property",
		"scripts" => [
			CDN . "/js/vendor/philippines-addresses/json/table_address.js",
			CDN . "/js/vendor/tabler/dist/libs/tom-select/dist/js/tom-select.base.min.js?1695847769",
			CDN . "/js/vendor/tinymce/tinymce.min.js",
			CDN . "/js/vendor/validatejs-0.13.1/validate.min.js",
			CDN . "/js/main/address.js",
			CDN . "/js/main/app/properties.js"
		]
	]
);

/** Document Top Configuration */
View::define(
	name: "document_top",
	path: "/authenticated/includes/document_top.php", 
	data: [
		"title" => "Update Posting",
		"description" => "Update Property Posting",
		"btn" => [
			"<a href='".url("properties")."' class='btn btn-primary'><i class='ti ti-list me-1'></i> <i class='ti ti-user fs-20 d-sm-block d-md-none'></i><span class='d-none d-md-block'>Properties</span></a>"
		]
	]
);

$html[] = View::include("document_top");

/** START PAGE BODY */
$html[] = "<div class='page-body'>";
	$html[] = "<div class='container-fluid'>";

		$html[] = "<form id='form' action='".url("properties.save.update", ["id" => $data['property_id']])."' method='POST'>";
			$html[] = "<input name='thumb_img' id='thumb_img' type='hidden' value='".$data['thumb_img']."' />";
			$html[] = "<input name='account_id' id='account_id' type='hidden' value='".$data['account']['account_id']."' />";


			$html[] = "<div class='row mb-5'>";
				$html[] = "<div class='col-xl-3 col-lg-4 col-md-8 col-sm-12 col-12'>";
					$html[] = "<div class='card  mb-3 '>";
						$html[] = "<div class='d-none d-md-block'>";
							$html[] = "<div class='list-group list-group-flush'>";
								$html[] = "<a href='#' class='list-group-item content-nav active' data-target='settings'><i class='ti ti-settings fs-22 me-2'></i> Post Settings</a>";
								$html[] = "<a href='#' class='list-group-item content-nav' data-target='description'><i class='ti ti-file-description fs-22 me-2'></i> Description</a>";
								$html[] = "<a href='#' class='list-group-item content-nav' data-target='technicalities'><i class='ti ti-ruler fs-22 me-2'></i> Technicalities</a>";
								$html[] = "<a href='#' class='list-group-item content-nav' data-target='locations'><i class='ti ti-map-pin fs-22 me-2'></i> Locations</a>";
								$html[] = "<a href='#' class='list-group-item content-nav' data-target='amenities'><i class='ti ti-home-shield fs-22 me-2'></i> Amenities</a>";
								$html[] = "<a href='#' class='list-group-item content-nav' data-target='terms'><i class='ti ti-cash fs-22 me-2'></i> Transaction Terms</a>";
								$html[] = "<a href='#' class='list-group-item content-nav' data-target='images'><i class='ti ti-photo fs-22 me-2'></i> Images</a>";
								$html[] = "<a href='#' class='list-group-item content-nav' data-target='documents'><i class='ti ti-pdf fs-22 me-2'></i> Documents</a>";
								$html[] = "<a href='#' class='list-group-item content-nav' data-target='videos'><i class='ti ti-brand-youtube fs-22 me-2'></i> Videos</a>";
							$html[] = "</div>";
						$html[] = "</div>";

						$html[] = "<div class='d-md-none'>";
							$html[] = "<div class='' style='overflow: auto;'>";
								$html[] = "<div class='list-group list-group-horizontal align-content-center' style='display:inline-flex;'>";
									$html[] = "<a href='#' style='width:150px;' class='list-group-item content-nav text-center active' data-target='settings'><i class='ti ti-settings fs-32 d-block mb-2'></i> Post Settings</a>";
									$html[] = "<a href='#' style='width:150px;' class='list-group-item content-nav text-center' data-target='description'><i class='ti ti-file-description fs-32 d-block mb-2'></i> Description</a>";
									$html[] = "<a href='#' style='width:150px;' class='list-group-item content-nav text-center' data-target='technicalities'><i class='ti ti-ruler fs-32 d-block mb-2'></i> Technicalities</a>";
									$html[] = "<a href='#' style='width:150px;' class='list-group-item content-nav text-center' data-target='locations'><i class='ti ti-map-pin fs-32 d-block mb-2'></i> Locations</a>";
									$html[] = "<a href='#' style='width:150px;' class='list-group-item content-nav text-center' data-target='amenities'><i class='ti ti-home-shield fs-32 d-block mb-2'></i> Amenities</a>";
									$html[] = "<a href='#' style='width:150px;' class='list-group-item content-nav text-center' data-target='terms'><i class='ti ti-cash fs-32 d-block mb-2'></i> Transaction Terms</a>";
									$html[] = "<a href='#' style='width:150px;' class='list-group-item content-nav text-center' data-target='images'><i class='ti ti-photo fs-32 d-block mb-2'></i> Images</a>";
									$html[] = "<a href='#' style='width:150px;' class='list-group-item content-nav text-center' data-target='documents'><i class='ti ti-pdf fs-32 d-block mb-2'></i> Documents</a>";
									$html[] = "<a href='#' style='width:150px;' class='list-group-item content-nav text-center' data-target='videos'><i class='ti ti-brand-youtube fs-32 d-block mb-2'></i> Videos</a>";
								$html[] = "</div>";
							$html[] = "</div>";
						$html[] = "</div>";
					$html[] = "</div>";
				$html[] = "</div>";

				$html[] = "<div class='col-xl-9 col-lg-8 col-md-8 col-sm-12 col-12'>";

					$html[] = "<div class='card main-container'>";
						$html[] = "<div class='card-body'>";

							$html[] = "<div id='settings' class='content-container'>";
								$html[] = "<h3 class='card-title'><i class='ti ti-settings fs-22 me-2'></i> Settings</h3>";

								$html[] = "<div class='form-floating mb-2'>";
									$html[] = "<select name='service_type' id='service_type' class='form-select'>";
										foreach(["project selling", "general brokerage"] as $service_type) {
											$sel = $service_type == $data['service_type'] ? "selected" : "";
											$html[] = "<option value='".$service_type."' $sel>".ucwords($service_type)."</option>";
										}
									$html[] = "</select>";
									$html[] = "<label for='service_type'><i class='ti ti-building-skyscraper'></i> Type of Real Estate Service</label>";
								$html[] = "</div>";

								$html[] = "<div class='form-floating mb-3'>";
									$html[] = "<select class='form-control' name='listing_type' id='listing_type'>";
										$listing_type = array("For Sale","For Rent", "Looking For");
										foreach($listing_type as $key => $val) {
											$sel = strtolower($val) == $data['listing_type'] ? "selected" : "";
											$html[] = "<option value='".strtolower($val)."' $sel>$val</option>";
										}
									$html[] = "</select>";
									$html[] = "<label for='listing_type'><i class='ti ti-tags'></i> Listing Type</label>";
								$html[] = "</div>";

								if($data['status'] == 1) {

									$html[] = "<input name='status' id='status' type='hidden' value='1' />";

									$html[] = "<div class='form-floating mb-4'>";
										$html[] = "<select name='duration' id='duration' class='form-select'>";
											$durations = array(15, 30, 60, 90);
											foreach($durations as $days) {
												$sel = $days == $data['duration'] ? "selected" : "";
												$html[] = "<option value='".strtotime("+".$days." days", DATE_NOW)."' $sel>$days days</option>";
											}
										$html[] = "</select>";
										$html[] = "<label for='duration'><i class='ti ti-calendar'></i> Posting Duration</label>";
									$html[] = "</div>";
								}

								$html[] = "<div class='form-group mb-3 brokerage-options hide_looking_for hide_foreclosure'>";
									$html[] = "<label class='form-check form-switch cursor-pointer'>";
										$html[] = "<input class='form-check-input' type='checkbox' name='foreclosure' value='1' id='foreclosure' ".($data['foreclosure'] == 1 ? "checked" : "")." />";
										$html[] = "<span class='form-check-label' for='foreclosure'>Is this property a foreclosure?</span>";
									$html[] = "</label>";
								$html[] = "</div>";

								$html[] = "<div class='brokerage-options'>";
									$html[] = "<div class='form-floating mb-4'>";
										$html[] = "<select name='com_share' id='com_share' class='form-select'>";
											foreach($data['collections']['commission_sharing'] as $sharing) {
												$sel = $sharing == $data['other_details']["com_share"] ? "selected" : "";
												$html[] = "<option value='$sharing' $sel>$sharing Percent</option>";
											}
										$html[] = "</select>";
										$html[] = "<label for='com_share'><i class='ti ti-percentage'></i> Commission Sharing</label>";
										$html[] = "<span class='form-hint mt-2'>Please specify the percentage of commission you are prepared to distribute.</span>";
									$html[] = "</div>";

									$html[] = "<label class='form-label text-muted'>What type of Authority to Negotiate do you hold for this property?</label>";
									$html[] = "<div class='form-floating mb-4'>";
										$html[] = "<select class='form-select' name='authority_type' id='authority_type'>";
											foreach($data['collections']['authority_types'] as $authority) {
												$sel = $data['other_details']["authority_type"] == $authority ? "selected" : "";
												$html[] = "<option value='$authority' $sel>".str_replace("Sell", "Negotiate", $authority)."</option>";
											}
										$html[] = "</select>";
										$html[] = "<label for='authority_type'><i class='ti ti-certificate'></i> Authority Type</label>";
										$html[] = "<span class='form-hint mt-2'>The legal permission granted to an individual or entity to negotiate a property on behalf of the owner(s)</span>";
									$html[] = "</div>";

									if($data['other_details']["authority_type"] == "N/A") {
										$authority_expiration_label = "";
										$authority_to_sell_expiration['value'] = strtotime("2038-01-01");
										$authority_to_sell_expiration['class'] = "d-none";
									}else {
										$authority_expiration_label = "d-none";
										$authority_to_sell_expiration['value'] = $data['other_details']["authority_to_sell_expiration"];
										$authority_to_sell_expiration['class'] = "";
									}

									$html[] = "<label class='form-label text-muted'>Authority to Negotiate Expiration Date</label>";
									$html[] = "<div class='form-floating mb-3'>";
										$html[] = "<input type='date' name='authority_to_sell_expiration' id='authority_to_sell_expiration' value='".(isset($authority_to_sell_expiration['value']) ? date("Y-m-d", $authority_to_sell_expiration['value']) : null)."' step='0.5' class='form-control ".$authority_to_sell_expiration['class']."' placeholder='Authority to Sell Expiration Date' />";
										$html[] = "<input type='text' id='authority_expiration_label' value='Never Expires' class='form-control ".$authority_expiration_label."' placeholder='Authority to Sell Expiration Date' readonly />";
										
										$html[] = "<label for='authority_to_sell_expiration'><i class='ti ti-calendar'></i> Expiration Date</label>";
										$html[] = "<span class='form-hint mt-2'>Please specify the expiration date of your Authority to Negotiate for this property.</span>";
									$html[] = "</div>";
								$html[] = "</div>";
							$html[] = "</div>";
							/** END SETTINGS */

							/** DESCRIPTION */
							$html[] = "<div id='description' class='content-container d-none'>";
								$html[] = "<h3 class='card-title'><i class='ti ti-file-description fs-22 me-1'></i> Description</h3>";

								$html[] = "<div class='form-floating mb-3'>";
									$html[] = "<input type='text' name='title' id='title' value='".$data['title']."' class='form-control' placeholder='Title' />";
									$html[] = "<label for='title'><i class='ti ti-writing'></i> Posting Title</label>";
									$html[] = "<span class='form-hint mt-2'>Do not include \"For Sale\", \"RFO\", \"Re-Sale\" etc.. in your title.<br/>Title should be short and descriptive maxlength is 80 characters and no special characters.</span>";
								$html[] = "</div>";

								$html[] = "<div class='form-group mb-3'>";
									$html[] = "<label class='form-label text-muted'>Description</label>";
									$html[] = "<textarea id='textContainer' name='long_desc' class='form-control '>".clean($data['long_desc'])."</textarea>";
									$html[] = "<span class='form-hint mt-3'>Please note that contact numbers, email addresses, names, and links are automatically removed.</span>";
								$html[] = "</div>";

							$html[] = "</div>";
							/** END DESCRIPTION */

							/** TECHNICALITIES */
							$html[] = "<div id='technicalities' class='content-container d-none'>";
								$html[] = "<h3 class='card-title'><i class='ti ti-ruler-measure fs-22 me-1'></i> Technicalities</h3>";

								$html[] = "<div class='d-flex gap-2 mb-3'>";
									$html[] = "<div class='form-floating flex-fill'>";
										$html[] = "<select id='category' class='form-select' name='category'>";
											foreach($data['collections']['categories'] as $key => $categories) {
												$html[] = "<optgroup label='$key'>";
												foreach($categories as $category) {
													$sel = $data['category'] == $category ? "selected" : "";
													$html[] = "<option value='$category' $sel>$category</option>";
												}
												$html[] = "</optgroup>";
											}
										$html[] = "</select>";
										$html[] = "<label for='category'><i class='ti ti-building-store'></i> Category</label>";
									$html[] = "</div>";

									$html[] = "<div class='form-floating flex-fill'>";
										$html[] = "<select class='form-select' name='property_type' id='property_type'>";
											$property_type = array("Residential","Commercial");
											foreach($property_type as $key => $val) {
												$sel = $val == $data['property_type'] ? "selected" : "";
												$html[] = "<option value='".$val."' $sel>$val</option>";
											}
										$html[] = "</select>";
										$html[] = "<label for='property_type'><i class='ti ti-building-estate'></i> Property Type</label>";
									$html[] = "</div>";
								$html[] = "</div>";

								$html[] = "<div class='d-flex gap-2 mb-3'>";
									$html[] = "<div class='form-floating flex-fill'>";
										$html[] = "<select class='form-select' name='bedroom' id='bedroom'>";
											$sel = "Studio" == $data['bedroom'] ? "selected" : (0 == $data['bedroom'] ? "selected" : "");
											$html[] = "<option value='0' $sel>No Bedroom</option>";
											$html[] = "<option value='Studio' $sel>Studio</option>";
											for($i=1; $i<11; $i++) {
												$sel = $i == $data['bedroom'] ? "selected" : "";
												$html[] = "<option value='$i' $sel>$i Bedroom</option>";
											}
										$html[] = "</select>";
										$html[] = "<label for='bedroom'><i class='ti ti-bed-flat'></i> Bedroom</label>";
									$html[] = "</div>";

									$html[] = "<div class='form-floating flex-fill'>";
										$html[] = "<select class='form-select' name='bathroom' id='bathroom'>";
											for($i=0; $i<11; $i++) {
												$sel = $i == $data['bathroom'] ? "selected" : "";
												$html[] = "<option value='$i' $sel>".($i == 0 ? "No" : $i)." Bathroom</option>";
											}
										$html[] = "</select>";
										$html[] = "<label for='bathroom'><i class='ti ti-bath'></i> Bathroom</label>";
									$html[] = "</div>";

									$html[] = "<div class='form-floating flex-fill'>";
										$html[] = "<select class='form-select' name='parking' id='parking'>";
											for($i=0; $i<11; $i++) {
												$sel = $i == $data['parking'] ? "selected" : "";
												$html[] = "<option value='$i' $sel>".($i == 0 ? "No Garage" : $i." car slot")."</option>";
											}
										$html[] = "</select>";
										$html[] = "<label for='parking'><i class='ti ti-car-garage'></i> Car Garage</label>";
									$html[] = "</div>";
								$html[] = "</div>";

								$html[] = "<div class='d-flex gap-2 mb-3'>";
									$tech_details = array("floor_area","lot_area");
									foreach($tech_details as $details) {
										$label = ucwords(str_replace("_"," ",$details));
										$html[] = "<div class='form-floating flex-fill'>";
											$html[] = "<input type='text' name='$details' id='$details' value='".$data[$details]."' class='form-control' placeholder='$label' />";
											$html[] = "<label for='$details'><i class='ti ti-ruler-measure me-1'></i> $label</label>";
										$html[] = "</div>";
									}
								$html[] = "</div>";

								$html[] = "<label class='form-label'><i class='ti ti-building-cottage'></i> Tags<br /></label>";
								$html[] = "<div class='form-floating mb-3'>";
									$html[] = "<select class='form-select' name='tags[]' id='tags' multiple='multiple'>";
										foreach($data['collections']['tags'] as $key => $val) {
											$sel = in_array($val, ($data['tags'] != "" ? $data['tags'] : [])) ? "selected" : "";
											$html[] = "<option value='$val' $sel>$val</option>";
										}
									$html[] = "</select>";
								$html[] = "</div>";

							$html[] = "</div>";
							/** END TECHNICALITIES */

							/** LOCATIONS */
							$html[] = "<div id='locations' class='content-container d-none'>";
								$html[] = "<h3 class='card-title'><i class='ti ti-map-pin fs-22 me-1'></i> Location</h3>";

								$html[] = "<div class='address-hidden-inputs'></div>";
								$html[] = "<div class='form-group mb-3'>";
									$html[] = "<label class='form-label text-muted'>Address</label>";
									
									$html[] = "<div class='form-floating mb-3 region-select' data-region='".$data['address']['region']."'>";
										$html[] = "<label for='region'>Region</label>";
									$html[] = "</div>";

									$html[] = "<div class='form-floating mb-3 province-select' data-province='".$data['address']['province']."'>";
										$html[] = "<label for='province'>Province</label>";
									$html[] = "</div>";

									$html[] = "<div class='form-floating mb-3 municipality-select' data-municipality='".$data['address']['municipality']."'>";
										$html[] = "<label for='municipality'>Municipality</label>";
									$html[] = "</div>";

									$html[] = "<div class='form-floating mb-3 barangay-select' data-barangay='".$data['address']['barangay']."'>";
										$html[] = "<label for='barangay'>Barangay</label>";
									$html[] = "</div>";

								$html[] = "</div>";

								$html[] = "<div class='form-floating mb-3 street-input'>";
									$html[] = "<input type='text' name='address[street]' id='address_street' value='".($data['address']['street'] ?? "")."' class='form-control' />";
									$html[] = "<label for='address_street'>Street</label>";
								$html[] = "</div>";

								$html[] = "<div class='form-floating mb-3 village-input'>";
									$html[] = "<input type='text' name='address[village]' id='address_village' value='".($data['address']['village'] ?? "")."' class='form-control' />";
									$html[] = "<label for='address_village'>Village / Building / Communities</label>";
								$html[] = "</div>";

							$html[] = "</div>";
							/** END LOCATIONS */

							/** AMENITIES */
							$html[] = "<div id='amenities' class='content-container d-none'>";
								$html[] = "<h3 class='card-title'><i class='ti ti-home-shield fs-22 me-1'></i> Features and Amenities</h3>";

								$html[] = "<div class='amenities-wrap mt-3'>";
									$html[] = "<div class='form-group'>";
										
										$amenities = $data['collections']['amenities'];
						
										$html[] = "<div class='p-4 text-dark'>";
											$html[] = "<div class='row'>";
												for($i=0; $i<count($amenities); $i++) {
												
													$check = in_array($amenities[$i], $data['amenities']) ? "checked" : "";
												
													$html[] = "<div class='col-lg-3 col-md-4 col-sm-6 col-6'>";
														$html[] = "<label class='form-check cursor-pointer'>";
															$html[] = "<input type='checkbox' class='form-check-input' id='customCheck_$i' name='amenities[$i]' value='".$amenities[$i]."' $check>";
															$html[] = "<span class='form-check-label' for='customCheck_$i'>".$amenities[$i]."</span>";
														$html[] = "</label>";
													$html[] = "</div>";

												}
											$html[] = "</div>";
										$html[] = "</div>";
										
									$html[] = "</div>";
								$html[] = "</div>";

							$html[] = "</div>";
							/** END AMENITIES */

							/** TERMS */
							$html[] = "<div id='terms' class='content-container d-none'>";
								$html[] = "<h3 class='card-title'><i class='ti ti-cash fs-22 me-1'></i> Transaction Terms</h3>";
								
								$html[] = "<div class='row'>";
								$html[] = "<div class='col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12'>";

								$html[] = "<div class='form-floating mb-3'>";
									$html[] = "<input type='number' name='price' id='price' value='".$data['price']."' step='0.05' class='form-control' placeholder='Price' />";
									$html[] = "<label for='price'><i class='ti ti-currency-peso'></i> Selling Price / Lease Price / Contract Price</label>";
								$html[] = "</div>";

								$html[] = "<div class='hide_rental'>";
									$html[] = "<div class='form-floating mb-4 hide_looking_for'>";
										$html[] = "<input type='number' name='reservation' id='reservation' value='".$data['reservation']."' step='0.05' class='form-control' placeholder='Reservation' />";
										$html[] = "<label for='reservation'><i class='ti ti-currency-peso'></i> Reservation Fee / Option Money</label>";
										$html[] = "<span class='form-hint mt-2'>Option money is a payment made by a buyer to secure the exclusive right to purchase a property within a set timeframe</span>";
									$html[] = "</div>";

									$html[] = "<div class='form-floating mb-4 brokerage-options hide_looking_for'>";
										$html[] = "<select name='payment_details[option_money_duration]' id='option_money_duration' class='form-select'>";
											foreach(range(15, 90, 15) as $duration) {
												$sel = $duration == (isset($data['payment_details']['option_money_duration']) ? $data['payment_details']['option_money_duration'] : "") ? "selected" : "";
												$html[] = "<option value='$duration' $sel>$duration days</option>";
											}
										$html[] = "</select>";
										$html[] = "<label for='option_money_duration'><i class='ti ti-clock-24'></i> Option Money Days Duration</label>";
										$html[] = "<span class='form-hint mt-2'>Duration of exclusive right to purchase</span>";
									$html[] = "</div>";

									$html[] = "<div class='form-floating mb-4 brokerage-options'>";
										$html[] = "<select name='payment_details[payment_mode]' id='payment_mode' class='form-select'>";
											foreach(["Installment", "Cash"] as $mode) {
												$sel = $mode == (isset($data['payment_details']['payment_mode']) ? $data['payment_details']['payment_mode'] : "") ? "selected" : "";
												$html[] = "<option value='$mode' $sel>$mode</option>";
											}
										$html[] = "</select>";
										$html[] = "<label for='payment_mode'><i class='ti ti-file-invoice'></i> Mode of Payment</label>";
										$html[] = "<span class='form-hint mt-2'>The mode of payment refers to the method or manner in which a financial transaction is completed, such as cash or installment payment.</span>";
									$html[] = "</div>";

									$html[] = "<div class='form-floating mb-5 brokerage-options'>";
										$html[] = "<select name='payment_details[tax_allocation]' id='tax_allocation' class='form-select'>";
											foreach(["Seller Agrees to Pay Capital Gains Tax and Buyer Pays Transfer Tax", "Buyer Pays Capital Gains Tax, Transfer Tax and Broker Commission"] as $schedule) {
												$sel = $schedule == (isset($data['payment_details']['tax_allocation']) ? $data['payment_details']['tax_allocation'] : "") ? "selected" : "";
												$html[] = "<option value='$schedule' $sel>$schedule</option>";
											}
										$html[] = "</select>";
										$html[] = "<label for='tax_allocation'>Allocation of Taxes</label>";
										$html[] = "<span class='form-hint mt-2'>Agreement between the seller and the buyer regarding who is responsible for paying which taxes.</span>";
									$html[] = "</div>";

									$html[] = "<div class='form-group mb-4'>";
										$html[] = "<label class='form-check form-switch cursor-pointer'>";
											$html[] = "<input class='form-check-input' type='checkbox' name='payment_details[bank_loan]' value='1' id='bank_loan' ".((isset($data['payment_details']['bank_loan']) ? $data['payment_details']['bank_loan'] : 0) == 1 ? "checked" : "")." />";
											$html[] = "<span class='form-check-label' for='bank_loan'>Is the property eligible for a Bank loan?</span>";
										$html[] = "</label>";
									$html[] = "</div>";

									$html[] = "<div class='form-group mb-4'>";
										$html[] = "<label class='form-check form-switch cursor-pointer'>";
											$html[] = "<input class='form-check-input' type='checkbox' name='payment_details[pagibig_loan]' value='1' id='pagibig_loan' ".((isset($data['payment_details']['pagibig_loan']) ? $data['payment_details']['pagibig_loan'] : 0) == 1 ? "checked" : "")." />";
											$html[] = "<span class='form-check-label' for='pagibig_loan'>Is the property eligible for a Pag-IBIG housing loan?</span>";
										$html[] = "</label>";
									$html[] = "</div>";

									$html[] = "<div class='form-group mb-4 brokerage-options'>";
										$html[] = "<label class='form-check form-switch cursor-pointer'>";
											$html[] = "<input class='form-check-input' type='checkbox' name='payment_details[assume_balance]' value='1' id='assume_balance' ".((isset($data['payment_details']['assume_balance']) ? $data['payment_details']['assume_balance'] : 0) == 1 ? "checked" : "")." />";
											$html[] = "<span class='form-check-label' for='assume_balance'>Will the buyer assume the remaining loan balance? \"Assume Balance\"</span>";
											$html[] = "<span class='form-hint'>Buyer takes over the seller's existing mortgage instead of getting a new one</span>";
										$html[] = "</label>";
									$html[] = "</div>";
								$html[] = "</div>";

								$html[] = "</div>";
								$html[] = "</div>";

							$html[] = "</div>";
							/** END TERMS */

							/** IMAGES */
							$html[] = "<div id='images' class='content-container d-none'>";
								
								$html[] = "<div class='d-flex align-content-center justify-content-between'>";
									$html[] = "<h3 class='card-title mb-0'><i class='ti ti-photo fs-22 me-1'></i> Images</h3>";
									$html[] = "<div class='mb-3'>";
										$html[] = "<div class='btn-list'>";
											
											$html[] = "<div class=''>";
												$html[] = "<div class='dropstart'>";
													$html[] = "<span class='btn dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='true'><i class='ti ti-help fs-22'></i></span>";
													$html[] = "<div class='dropdown-menu dropdown-menu-card' style='width: 25rem; position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate3d(-70px, 0, 0px);'>";
														
														$html[] = "<div class='card'>";
															$html[] = "<div class='card-body'>";
																$html[] = "<h3 class='card-title'>Please read the following before uploading images</h3>";
																$html[] = "<ul class='list-group mb-3'>";
																	$html[] = "<li class='list-group-item'><i class='ti ti-arrow-badge-right me-2 text-danger'></i>Only .jpg, .png, .gif are allowed</li>";
																	$html[] = "<li class='list-group-item'><i class='ti ti-arrow-badge-right me-2 text-danger'></i>Select 5 or less images per upload</li>";
																	$html[] = "<li class='list-group-item'><i class='ti ti-arrow-badge-right me-2 text-danger'></i>Images less than 2MB file sizes are allowed</li>";
																	$html[] = "<li class='list-group-item'><i class='ti ti-arrow-badge-right me-2 text-danger'></i>Resize your images before uploading</li>";
																	$html[] = "<li class='list-group-item'><i class='ti ti-arrow-badge-right me-2 text-danger'></i>For website compatibility, only upload landscape images</li>";
																$html[] = "</ul>";
															$html[] = "</div>";
														$html[] = "</div>";

													$html[] = "</div>";
												$html[] = "</div>";
											$html[] = "</div>";

											$html[] = "<span class='image-uploader' data-url='".url("PropertyImagesController@upload")."'></span>";
										$html[] = "</div>";
									$html[] = "</div>";
								$html[] = "</div>";

								$html[] = "<div class='upload-response'></div>";


								$html[] = "<div class='' style='max-height: 85vh; overflow-y:auto;'>";
									$html[] = "<div class='d-flex flex-wrap justify-content-center images-container m-0'>";
										
										if(!empty($data['images'])) {
											for($i=0; $i<count($data['images']); $i++) {
											
												$html[] = "<div class='me-2 mb-3 image_".$data['images'][$i]['image_id']." flex-grow-1'>";
													
													/* $html[] = "<input type='hidden' name='upload[$i][image_id]' value='".$data['images'][$i]['image_id']."' />";
													$html[] = "<input type='hidden' name='upload[$i][width]' value='".$data['images'][$i]['width']."' />";
													$html[] = "<input type='hidden' name='upload[$i][height]' value='".$data['images'][$i]['height']."' />";
													$html[] = "<input type='hidden' name='upload[$i][filename]' value='".$data['images'][$i]['filename']."' />";
													$html[] = "<input type='hidden' name='upload[$i][url]' value='".$data['images'][$i]['url']."' />"; */

													$html[] = "<div class=''>";
														$html[] = "<span class='avatar avatar-xxxl' style=\"background-image:url('".$data['images'][$i]['url']."'); \"></span>";
													$html[] = "</div>";
													$html[] = "<div class='btn-list mt-2 text-center'>";
														$html[] = "<span class='btn btn-outline-secondary btn-remove-image' title='Remove image' data-container='.image_".$data['images'][$i]['image_id']."' data-filename='".$data['images'][$i]['filename']."' data-url='".url("properties.image.delete", ["id" => $data['images'][$i]['image_id']])."'><i class='ti ti-trash'></i></span>";
														if($data['thumb_img'] == $data['images'][$i]['url']) {
															$html[] = "<span class='btn btn-success btn-set-thumbnail' title='Set image as thumbnail' data-container='.image_".$data['images'][$i]['image_id']."' data-final-url='".$data['images'][$i]['url']."'><i class='ti ti-check me-2'></i> Thumbnail</span>";
														}else {
															$html[] = "<span class='btn btn-outline-primary btn-set-thumbnail' title='Set image as thumbnail' data-container='.image_".$data['images'][$i]['image_id']."' data-final-url='".$data['images'][$i]['url']."'><i class='ti ti-click me-2'></i> Thumbnail</span>";
														}
													$html[] = "</div>";
													
												$html[] = "</div>";
											}
										}
										
									$html[] = "</div>";
								$html[] = "</div>";
									
							$html[] = "</div>";
							/** END IMAGES */

							/** DOCUMENTS */
							$html[] = "<div id='documents' class='content-container d-none'>";

								$html[] = "<div class='d-flex align-content-center justify-content-between'>";
									$html[] = "<h3 class='card-title mb-0'><i class='ti ti-pdf fs-22 me-1'></i> Documents</h3>";
									$html[] = "<div class=''>";
										$html[] = "<div class='btn-list'>";
											
											$html[] = "<div class=''>";
												$html[] = "<div class='dropstart'>";
													$html[] = "<span class='btn dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='true'><i class='ti ti-help fs-22'></i></span>";
													$html[] = "<div class='dropdown-menu dropdown-menu-card' style='width: 25rem; position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate3d(-70px, 0, 0px);'>";
														
														$html[] = "<div class='card'>";
															$html[] = "<div class='card-body'>";
																$html[] = "<h3 class='card-title'>Please read the following before uploading PDF's</h3>";
																$html[] = "<ul class='list-group'>";
																	$html[] = "<li class='list-group-item'><i class='ti ti-arrow-badge-right me-2 text-danger'></i>Only .pdf file is allowed</li>";
																	$html[] = "<li class='list-group-item'><i class='ti ti-arrow-badge-right me-2 text-danger'></i>Rename your pdf file before uploading</li>";
																	$html[] = "<li class='list-group-item'><i class='ti ti-arrow-badge-right me-2 text-danger'></i>Select 5 or less pdf file per upload</li>";
																	$html[] = "<li class='list-group-item'><i class='ti ti-arrow-badge-right me-2 text-danger'></i>Pdf files less than 3MB file sizes are allowed</li>";
																$html[] = "</ul>";
															$html[] = "</div>";
														$html[] = "</div>";

													$html[] = "</div>";
												$html[] = "</div>";
											$html[] = "</div>";

											$html[] = "<span class='file-uploader' data-url='".url("PropertiesController@upload")."'></span>";
										$html[] = "</div>";
									$html[] = "</div>";
								$html[] = "</div>";
								
								$html[] = "<div class='upload-response mb-3'></div>";
								
								$html[] = "<span class='form-hint mb-3'>Note: If you delete a document, please ensure to save your work before navigating away from this page</span>";
								$html[] = "<div class='container-tight' style='max-height:85vh; overflow-y:auto;'>";
									$html[] = "<ul class='list-group list-group-flush files-container'>";
										
										if(isset($data['documents']) && !empty($data['documents'])) {
											foreach($data['documents'] as $docs) {
												$html[] = "<li class='list-group-item d-flex gap-3 justify-content-between align-items-center py-3 file_".$docs['id']."'>";
													$html[] = "<div class='flex-grow-1'>";
														$html[] = "<input type='hidden' name='documents[".$docs['id']."][id]' value='".$docs['id']."' />";
														$html[] = "<input type='hidden' name='documents[".$docs['id']."][filename]' value='".$docs['filename']."' />";
														$html[] = "<input type='hidden' name='documents[".$docs['id']."][size]' value='".$docs['size']."' />";
														$html[] = "<input type='hidden' name='documents[".$docs['id']."][finalUrl]' value='".$docs['finalUrl']."' />";
														
														$html[] = "<div class='d-flex p-y align-items-center '>";
															$html[] = "<span class='avatar me-2'><i class='ti ti-pdf fs-18'></i></span>";
															$html[] = "<div class='flex-fill'>";
																$html[] = "<div class='font-weight-medium'><input type='text' name='documents[".$docs['id']."][alias]' value='".$docs['alias']."' class='border-0 w-100' /></div>";
																$html[] = "<div class='text-secondary small'>".$docs['size']."</div>";
															$html[] = "</div>";
														$html[] = "</div>";

													$html[] = "</div>";
													$html[] = "<div class='btn-list'>";
														$html[] = "<span class='btn-remove-document cursor-pointer p-2' title='remove ".$docs['alias']."' data-id='".$docs['id']."' data-property_id='".$data['property_id']."' data-remove_url='".DOMAIN."/properties/".$data['property_id']."/removeDocument/".$docs['filename']."' data-filename='".$docs['filename']."'><i class='ti ti-trash me-1'></i></span>";
													$html[] = "</div>";
												$html[] = "</li>";
											}
										}
										
									$html[] = "</ul>";
								$html[] = "</div>";
									
							$html[] = "</div>";
							/** END DOCUMENTS */

							$html[] = "<div id='videos' class='content-container d-none'>";
								
								$html[] = "<div class='hide_looking_for mb-5'>";
									
									$html[] = "<div class='p-4 bg-muted-lt border-bottom' style='margin: -20px -20px 20px -20px;'>";		
										$html[] = "<div class='' id='videoInput'></div>";
										$html[] = "<p class='form-hint mt-3'>Sample Youtube Url: https://www.youtube.com/watch?v=uiZVssPtPr4</p>";
									$html[] = "</div>";
										
									$html[] = "<h3 class='card-title'><i class='ti ti-brand-youtube fs-22 me-1'></i> Youtube Videos</h3>";
									
									$html[] = "<div class='video-list-container d-flex flex-wrap justify-content-center gap-3'>";
									if(!empty($data['videos']) || $data['videos'] != "") {
										foreach($data['videos'] as $video) {
											$html[] = "<div class='".$video['id']."' data-id='".$video['id']."' style='position:relative;'>";
												$html[] = "<input type='hidden' name='videos[".$video['id']."][id]' value='".$video['id']."' />";
												$html[] = "<input type='hidden' name='videos[".$video['id']."][thumbnail][default]' value='".$video['thumbnail']['default']."' />";
												$html[] = "<input type='hidden' name='videos[".$video['id']."][thumbnail][hq]' value='".$video['thumbnail']['hq']."' />";
												$html[] = "<input type='hidden' name='videos[".$video['id']."][thumbnail][mq]' value='".$video['thumbnail']['mq']."' />";
												$html[] = "<input type='hidden' name='videos[".$video['id']."][thumbnail][sd]' value='".$video['thumbnail']['sd']."' />";
												$html[] = "<input type='hidden' name='videos[".$video['id']."][thumbnail][maxres]' value='".$video['thumbnail']['maxres']."' />";
												$html[] = "<input type='hidden' name='videos[".$video['id']."][url]' value='".$video['url']."' />";
												$html[] = "<input type='hidden' name='videos[".$video['id']."][embed]' value='".$video['embed']."' />";
												$html[] = "<input type='hidden' name='videos[".$video['id']."][created_at]' value='".$video['created_at']."' />";

												$html[] = "<div class='btn-delete-container w-100 text-end p-1'>";
													$html[] = "<span class='btn btn-danger btn-remove-video' data-id='".$video['id']."'><i class='ti ti-trash'></i></span>";
												$html[] = "</div>";
												$html[] = "<div class='avatar avatar-xxxl p-2 btn-playback cursor-pointer text-white' data-id='".$video['id']."' data-url='".$video['url']."' data-embed='".$video['embed']."' style='background-image: url(".$video['thumbnail']['sd']."); height:120px !important;'>";
													$html[] = "<i class='ti ti-brand-youtube fs-32'></i>";
												$html[] = "</div>";
											$html[] = "</div>";
										}
									}
									$html[] = "</div>";
									
								$html[] = "</div>";

							$html[] = "</div>";
							
						$html[] = "</div>";
					$html[] = "</div>";


				$html[] = "</div>";
			$html[] = "</div>";

		$html[] = "</form>";

		$html[] = "<div class='btn-save-container fixed-bottom bg-white py-3 border-top'>";
			$html[] = "<div class='text-end pe-5'>";
				$html[] = "<span class='btn btn-outline-primary btn-save'><i class='ti ti-device-floppy me-2'></i> Save Property Listing</span>";
			$html[] = "</div>";
		$html[] = "</div>";
	
	$html[] = "</div>";
$html[] = "</div>";

	


/* 
// INSTRUCTIONS AND TIPS
$html[] = "<div class='mt-5 pt-5'>";
	$html[] = "<div class='card mt-4'>";
        $html[] = "<div class='card-body'>";
			$html[] = "<h5 class='card-title'>How to make your postings rank higher in the list.</h5>";
			$html[] = "<ul>";
				$html[] = "<li>Your posting will remain active for a specified duration. Choose the duration based on your listings or Authority to Sell period: 15, 30, 60, or 90 days.</li>";
				$html[] = "<li>Your posting can be shared with others using the handshake feature. Be sure to include all necessary information, but do not include your name or contact numbers in the descriptions.</li>";
				$html[] = "<li>Select the appropriate tags and choose multiple features and amenities.</li>";
				$html[] = "<li>Upload more than 10 high-resolution photos of the property, ensuring they are at least 1024px in width and height, but not exceeding 3000px in any dimension.</li>";
				$html[] = "<li>For a larger audience and more impressions, enable the 'Publish on Public Website' option, share your posting on social media sites, and visit your posting on the public website to click the share button.</li>";
				$html[] = "<li>Choosing to publish in MLS is also a good idea, as the listing can be shared with other real estate brokers. However, remember that the MLS is a private portal, and only members of PAREB are allowed to view the postings.</li>";
			$html[] = "</ul>";
        $html[] = "</div>";
    $html[] = "</div>";
$html[] = "</div>"; */