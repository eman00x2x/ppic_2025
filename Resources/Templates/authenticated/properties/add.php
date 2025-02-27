<?php

use EO\View;
use EO\Auth\Auth as Auth;

View::setMasterTemplate(path: "/authenticated/template.php");

/** Document Header Configuration */
View::setDocumentHeader(
	data: [
		"title" => "Add Property",
		"description" => "Add Property",
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
		"title" => "Post Property",
		"description" => "",
		"btn" => [
			"<a href='".url("properties")."' class='btn btn-primary'><i class='ti ti-list me-1'></i> <i class='ti ti-user fs-20 d-sm-block d-md-none'></i><span class='d-none d-md-block'>Properties</span></a>"
		]
	]
);

$html[] = View::include("document_top");

/** START PAGE BODY */
$html[] = "<div class='page-body'>";
	$html[] = "<div class='container-fluid'>";

		$html[] = "<form id='form' action='".url("properties.save.new")."' method='POST'>";
			$html[] = "<input name='thumb_img' id='thumb_img' type='hidden' value='' />";
			$html[] = "<input name='account_id' id='account_id' type='hidden' value='' />";


			$html[] = "<div class='row mb-5 '>";
				$html[] = "<div class='col-lg-8 col-md-8 col-sm-12 col-12'>";

					/** SETTINGS */
					$html[] = "<div id='settings' class='content-container'>";
						$html[] = "<div class='card'>";
							$html[] = "<div class='card-body'>";
								
								$html[] = "<div id='' class=''>";
									$html[] = "<h3 class='card-title'><i class='ti ti-settings fs-22 me-2'></i> What service do you offer?</h3>";

									$html[] = "<div class='form-floating mb-2'>";
										$html[] = "<select name='service_type' id='service_type' class='form-select'>";
											foreach(["project selling", "general brokerage"] as $service_type) {
												$html[] = "<option value='".$service_type."'>".ucwords($service_type)."</option>";
											}
										$html[] = "</select>";
										$html[] = "<label for='service_type'><i class='ti ti-building-skyscraper'></i> Type of Real Estate Service</label>";
									$html[] = "</div>";

									$html[] = "<div class='form-floating mb-2'>";
										$html[] = "<select class='form-control' name='listing_type' id='listing_type'>";
											$listing_type = array("For Sale","For Rent", "Looking For");
											foreach($listing_type as $key => $val) {
												$html[] = "<option value='".strtolower($val)."'>$val</option>";
											}
										$html[] = "</select>";
										$html[] = "<label for='listing_type'><i class='ti ti-tags'></i> Listing Type</label>";
									$html[] = "</div>";

									$html[] = "<input name='status' id='status' type='hidden' value='1' />";

									$html[] = "<div class='form-floating mb-4'>";
										$html[] = "<select name='duration' id='duration' class='form-select'>";
											$durations = array(15, 30, 60, 90);
											foreach($durations as $days) {
												$sel = $days == 90 ? "selected" : "";
												$html[] = "<option value='".strtotime("+".$days." days", DATE_NOW)."' $sel>$days days</option>";
											}
										$html[] = "</select>";
										$html[] = "<label for='duration'><i class='ti ti-calendar'></i> Posting Duration</label>";
									$html[] = "</div>";
									
									$html[] = "<div class='form-group mb-3 brokerage-options hide_looking_for hide_foreclosure'>";
										$html[] = "<label class='form-check form-switch cursor-pointer'>";
											$html[] = "<input class='form-check-input' type='checkbox' name='foreclosure' value='1' id='foreclosure' />";
											$html[] = "<span class='form-check-label' for='foreclosure'>Is this property a foreclosure?</span>";
										$html[] = "</label>";
									$html[] = "</div>";

									$html[] = "<div class='brokerage-options'>";
										$html[] = "<div class='form-floating mb-4'>";
											$html[] = "<select name='com_share' id='com_share' class='form-select'>";
												foreach($data['collections']['commission_sharing'] as $sharing) {
													$sel = $sharing == 50 ? "selected" : "";
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
													$html[] = "<option value='$authority'>".str_replace("Sell", "Negotiate", $authority)."</option>";
												}
											$html[] = "</select>";
											$html[] = "<label for='authority_type'><i class='ti ti-certificate'></i> Authority Type</label>";
											$html[] = "<span class='form-hint mt-2'>The legal permission granted to an individual or entity to negotiate a property on behalf of the owner(s)</span>";
										$html[] = "</div>";

										$html[] = "<label class='form-label text-muted'>Authority to Negotiate Expiration Date</label>";
										$html[] = "<div class='form-floating mb-3'>";
											$html[] = "<input type='date' name='authority_to_sell_expiration' id='authority_to_sell_expiration' value='2038-01-01' step='0.5' class='form-control d-none' placeholder='Authority to Sell Expiration Date' />";
											$html[] = "<input type='text' id='authority_expiration_label' value='Never Expires' class='form-control' placeholder='Authority to Sell Expiration Date' readonly />";
											
											$html[] = "<label for='authority_to_sell_expiration'><i class='ti ti-calendar'></i> Expiration Date</label>";
											$html[] = "<span class='form-hint mt-2'>Please specify the expiration date of your Authority to Negotiate for this property.</span>";
										$html[] = "</div>";

										$html[] = "<div class='form-floating mb-3'>";
											$html[] = "<input type='text' name='other_details[owned_by]' id='title' value='' class='form-control' placeholder='Owner Name' />";
											$html[] = "<label for='title'><i class='ti ti-writing'></i> Owner Name</label>";
										$html[] = "</div>";

										$html[] = "<div class='form-floating mb-3'>";
											$html[] = "<input type='text' name='other_details[contact_details]' id='title' value='' class='form-control' placeholder='Owner Contact Details' />";
											$html[] = "<label for='title'><i class='ti ti-writing'></i> Owner Contact Details</label>";
										$html[] = "</div>";

										$html[] = "<div class='form-floating mb-3'>";
											$html[] = "<input type='text' name='other_details[exact_address]' id='title' value='' class='form-control' placeholder='Owner Exact Address' />";
											$html[] = "<label for='title'><i class='ti ti-writing'></i> Owner Exact Address</label>";
										$html[] = "</div>";
										
									$html[] = "</div>";

								$html[] = "</div>";

							$html[] = "</div>";

						$html[] = "</div>";

						$html[] = "<div class='text-end mt-3'>";
							$html[] = "<span class='btn btn-md btn-outline-secondary content-nav' data-target='description'>Create a Posting Description <i class='ti ti-arrow-right ms-1 fw-bold fs-16'></i></span>";
						$html[] = "</div>";
					$html[] = "</div>";
					/** SETTINGS END */

					/** DESCRIPTION */
					$html[] = "<div id='description' class='d-none content-container'>";
						$html[] = "<div class='card'>";
							$html[] = "<div class='card-body'>";
								$html[] = "<h3 class='card-title'><i class='ti ti-file-description fs-22 me-1'></i> Posting Description</h3>";

								$html[] = "<div class='form-floating mb-3'>";
									$html[] = "<input type='text' name='title' id='title' value='' class='form-control' placeholder='Title' />";
									$html[] = "<label for='title'><i class='ti ti-writing'></i> Posting Title</label>";
									$html[] = "<span class='form-hint mt-2'>Do not include \"For Sale\", \"RFO\", \"Re-Sale\" etc.. in your title.<br/>Title should be short and descriptive maxlength is 80 characters and no special characters.</span>";
								$html[] = "</div>";

								$html[] = "<div class='form-group mb-3'>";
									$html[] = "<label class='form-label text-muted'>Description</label>";
									$html[] = "<textarea id='textContainer' name='long_desc' class='form-control '></textarea>";
									$html[] = "<span class='form-hint mt-3'>Please note that contact numbers, email addresses, names, and links are automatically removed.</span>";
								$html[] = "</div>";
							$html[] = "</div>";
						$html[] = "</div>";
						$html[] = "<div class='text-end mt-3'>";
							$html[] = "<span class='btn btn-md btn-outline-secondary content-nav me-2' data-target='settings'><i class='ti ti-arrow-left me-1 fw-bold fs-16'></i> Service Offerings</span>";
							$html[] = "<span class='btn btn-md btn-outline-secondary content-nav' data-target='technicalities'>Posting Technicalities <i class='ti ti-arrow-right ms-1 fw-bold fs-16'></i></span>";
						$html[] = "</div>";
					$html[] = "</div>";
					/** END DESCRIPTION */

					/** TECHNICALITIES */
					$html[] = "<div id='technicalities' class='content-container d-none'>";
						$html[] = "<div class='card'>";
							$html[] = "<div class='card-body'>";
								$html[] = "<h3 class='card-title'><i class='ti ti-ruler-measure fs-22 me-1'></i> Property Technicalities</h3>";

								$html[] = "<div class='d-flex gap-2 mb-3'>";
									$html[] = "<div class='form-floating flex-fill'>";
										$html[] = "<select id='category' class='form-select' name='category'>";
											foreach($data['collections']['categories'] as $key => $categories) {
												$html[] = "<optgroup label='$key'>";
												foreach($categories as $category) {
													$html[] = "<option value='$category'>$category</option>";
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
												$html[] = "<option value='".$val."'>$val</option>";
											}
										$html[] = "</select>";
										$html[] = "<label for='property_type'><i class='ti ti-building-estate'></i> Property Type</label>";
									$html[] = "</div>";
								$html[] = "</div>";

								$html[] = "<div class='d-flex gap-2 mb-3'>";
									$html[] = "<div class='form-floating flex-fill'>";
										$html[] = "<select class='form-select' name='bedroom' id='bedroom'>";
											$html[] = "<option value='0'>No Bedroom</option>";
											$html[] = "<option value='Studio'>Studio</option>";
											for($i=1; $i<11; $i++) {
												$html[] = "<option value='$i'>$i Bedroom</option>";
											}
										$html[] = "</select>";
										$html[] = "<label for='bedroom'><i class='ti ti-bed-flat'></i> Bedroom</label>";
									$html[] = "</div>";

									$html[] = "<div class='form-floating flex-fill'>";
										$html[] = "<select class='form-select' name='bathroom' id='bathroom'>";
											for($i=0; $i<11; $i++) {
												$html[] = "<option value='$i'>".($i == 0 ? "No" : $i)." Bathroom</option>";
											}
										$html[] = "</select>";
										$html[] = "<label for='bathroom'><i class='ti ti-bath'></i> Bathroom</label>";
									$html[] = "</div>";

									$html[] = "<div class='form-floating flex-fill'>";
										$html[] = "<select class='form-select' name='parking' id='parking'>";
											for($i=0; $i<11; $i++) {
												$html[] = "<option value='$i'>".($i == 0 ? "No Garage" : $i." car slot")."</option>";
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
											$html[] = "<input type='text' name='$details' id='$details' value='' class='form-control' placeholder='$label' />";
											$html[] = "<label for='$details'><i class='ti ti-ruler-measure me-1'></i> $label</label>";
										$html[] = "</div>";
									}
								$html[] = "</div>";

								$html[] = "<label class='form-label'><i class='ti ti-building-cottage'></i> Tags<br /></label>";
								$html[] = "<div class='form-floating mb-3'>";
									$html[] = "<select class='form-select' name='tags[]' id='tags' multiple='multiple'>";
										foreach($data['collections']['tags'] as $key => $val) {
											$sel = in_array($val, ["New", "Pre-Sale"]) ? "selected" : "";
											$html[] = "<option value='$val' $sel>$val</option>";
										}
									$html[] = "</select>";
								$html[] = "</div>";
							$html[] = "</div>";
						$html[] = "</div>";
						$html[] = "<div class='text-end mt-3'>";
							$html[] = "<span class='btn btn-md btn-outline-secondary content-nav me-2' data-target='description'><i class='ti ti-arrow-left me-1 fw-bold fs-16'></i> Posting Description</span>";
							$html[] = "<span class='btn btn-md btn-outline-secondary content-nav' data-target='locations'>Property Location <i class='ti ti-arrow-right ms-1 fw-bold fs-16'></i></span>";
						$html[] = "</div>";
					$html[] = "</div>";
					/** END TECHNICALITIES */

					/** LOCATIONS */
					$html[] = "<div id='locations' class='content-container d-none'>";
						$html[] = "<div class='card'>";
							$html[] = "<div class='card-body'>";
								$html[] = "<h3 class='card-title'><i class='ti ti-map-pin fs-22 me-1'></i> Location</h3>";

								$html[] = "<div class='address-hidden-inputs'></div>";
								$html[] = "<div class='form-group mb-3'>";
									$html[] = "<label class='form-label text-muted'>Address</label>";
									
									$html[] = "<div class='form-floating mb-3 region-select' data-region=''>";
										$html[] = "<label for='region'>Region</label>";
									$html[] = "</div>";

									$html[] = "<div class='form-floating mb-3 province-select' data-province=''>";
										$html[] = "<label for='province'>Province</label>";
									$html[] = "</div>";

									$html[] = "<div class='form-floating mb-3 municipality-select' data-municipality=''>";
										$html[] = "<label for='municipality'>Municipality</label>";
									$html[] = "</div>";

									$html[] = "<div class='form-floating mb-3 barangay-select' data-barangay=''>";
										$html[] = "<label for='barangay'>Barangay</label>";
									$html[] = "</div>";

								$html[] = "</div>";

								$html[] = "<div class='form-floating mb-3 street-input'>";
									$html[] = "<input type='text' name='address[street]' id='address_street' value='' class='form-control' />";
									$html[] = "<label for='address_street'>Street</label>";
								$html[] = "</div>";

								$html[] = "<div class='form-floating mb-3 village-input'>";
									$html[] = "<input type='text' name='address[village]' id='address_village' value='' class='form-control' />";
									$html[] = "<label for='address_village'>Village / Building / Communities</label>";
								$html[] = "</div>";
							$html[] = "</div>";
						$html[] = "</div>";
						$html[] = "<div class='text-end mt-3'>";
							$html[] = "<span class='btn btn-md btn-outline-secondary content-nav me-2' data-target='technicalities'><i class='ti ti-arrow-left me-1 fw-bold fs-16'></i> Posting Technicalities</span>";
							$html[] = "<span class='btn btn-md btn-outline-secondary content-nav' data-target='amenities'>Property Amenities <i class='ti ti-arrow-right ms-1 fw-bold fs-16'></i></span>";
						$html[] = "</div>";
					$html[] = "</div>";
					/** END LOCATIONS */

					/** AMENITIES */
					$html[] = "<div id='amenities' class='content-container d-none'>";
						$html[] = "<div class='card'>";
							$html[] = "<div class='card-body'>";
								$html[] = "<h3 class='card-title'><i class='ti ti-home-shield fs-22 me-1'></i> Features and Amenities</h3>";

								$html[] = "<div class='amenities-wrap mt-3'>";
									$html[] = "<div class='form-group'>";
										
										$amenities = $data['collections']['amenities'];
										$defaultAmenities = explode(", ","24 Hours Security, Near in Churches, Near in Schools, Near Malls, Near Hospitals, Gated Community, CCTV Cameras, Near Public Markets, Guard House, Club House");
										
										$html[] = "<div class='p-4 text-dark'>";
											$html[] = "<div class='row'>";
												for($i=0; $i<count($amenities); $i++) {
													$html[] = "<div class='col-lg-3 col-md-4 col-sm-6 col-6'>";
														$html[] = "<label class='form-check cursor-pointer'>";
															$html[] = "<input type='checkbox' class='form-check-input' id='customCheck_$i' name='amenities[]' value='".$amenities[$i]."' ".(in_array($amenities[$i], $defaultAmenities) ? "checked" : "").">";
															$html[] = "<span class='form-check-label' for='customCheck_$i'>".$amenities[$i]."</span>";
														$html[] = "</label>";
													$html[] = "</div>";

												}
											$html[] = "</div>";
										$html[] = "</div>";
										
									$html[] = "</div>";
								$html[] = "</div>";
							$html[] = "</div>";
						$html[] = "</div>";
						$html[] = "<div class='text-end mt-3'>";
							$html[] = "<span class='btn btn-md btn-outline-secondary content-nav me-2' data-target='locations'><i class='ti ti-arrow-left me-1 fw-bold fs-16'></i> Property Location</span>";
							$html[] = "<span class='btn btn-md btn-outline-secondary content-nav' data-target='terms'>Transaction Terms <i class='ti ti-arrow-right ms-1 fw-bold fs-16'></i></span>";
						$html[] = "</div>";
					$html[] = "</div>";
					/** END AMENITIES */

					/** TERMS */
					$html[] = "<div id='terms' class='content-container d-none'>";
						$html[] = "<div class='card'>";
							$html[] = "<div class='card-body'>";
								$html[] = "<h3 class='card-title'><i class='ti ti-cash fs-22 me-1'></i> Transaction Terms</h3>";
								
								$html[] = "<div class='row'>";
									$html[] = "<div class='col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12'>";

									$html[] = "<div class='form-floating mb-3'>";
										$html[] = "<input type='number' name='price' id='price' value='' step='0.05' class='form-control' placeholder='Price' />";
										$html[] = "<label for='price'><i class='ti ti-currency-peso'></i> Selling Price / Lease Price / Contract Price</label>";
									$html[] = "</div>";

									$html[] = "<div class='hide_rental'>";
										$html[] = "<div class='form-floating mb-4 hide_looking_for'>";
											$html[] = "<input type='number' name='reservation' id='reservation' value='' step='0.05' class='form-control' placeholder='Reservation' />";
											$html[] = "<label for='reservation'><i class='ti ti-currency-peso'></i> Reservation Fee / Option Money</label>";
											$html[] = "<span class='form-hint mt-2'>Option money is a payment made by a buyer to secure the exclusive right to purchase a property within a set timeframe</span>";
										$html[] = "</div>";

										$html[] = "<div class='form-floating mb-4 brokerage-options hide_looking_for'>";
											$html[] = "<select name='payment_details[option_money_duration]' id='option_money_duration' class='form-select'>";
												foreach(range(15, 90, 15) as $duration) {
													$sel = ($duration == 30) ? "selected" : "";
													$html[] = "<option value='$duration' $sel>$duration days</option>";
												}
											$html[] = "</select>";
											$html[] = "<label for='option_money_duration'><i class='ti ti-clock-24'></i> Option Money Days Duration</label>";
											$html[] = "<span class='form-hint mt-2'>Duration of exclusive right to purchase</span>";
										$html[] = "</div>";

										$html[] = "<div class='form-floating mb-4 brokerage-options'>";
											$html[] = "<select name='payment_details[payment_mode]' id='payment_mode' class='form-select'>";
												foreach(["Installment", "Cash"] as $mode) {
													$sel = ($mode == "Cash") ? "selected" : "";
													$html[] = "<option value='$mode' $sel>$mode</option>";
												}
											$html[] = "</select>";
											$html[] = "<label for='payment_mode'><i class='ti ti-file-invoice'></i> Mode of Payment</label>";
											$html[] = "<span class='form-hint mt-2'>The mode of payment refers to the method or manner in which a financial transaction is completed, such as cash or installment payment.</span>";
										$html[] = "</div>";

										$html[] = "<div class='form-floating mb-5 brokerage-options'>";
											$html[] = "<select name='payment_details[tax_allocation]' id='tax_allocation' class='form-select'>";
												foreach(["Seller Agrees to Pay Capital Gains Tax and Buyer Pays Transfer Tax", "Buyer Pays Capital Gains Tax, Transfer Tax and Broker Commission"] as $schedule) {
													$html[] = "<option value='$schedule'>$schedule</option>";
												}
											$html[] = "</select>";
											$html[] = "<label for='tax_allocation'>Allocation of Taxes</label>";
											$html[] = "<span class='form-hint mt-2'>Agreement between the seller and the buyer regarding who is responsible for paying which taxes.</span>";
										$html[] = "</div>";

										$html[] = "<div class='form-group mb-4'>";
											$html[] = "<label class='form-check form-switch cursor-pointer'>";
												$html[] = "<input class='form-check-input' type='checkbox' name='payment_details[bank_loan]' value='1' id='bank_loan' checked />";
												$html[] = "<span class='form-check-label' for='bank_loan'>Is the property eligible for a Bank loan?</span>";
											$html[] = "</label>";
										$html[] = "</div>";

										$html[] = "<div class='form-group mb-4'>";
											$html[] = "<label class='form-check form-switch cursor-pointer'>";
												$html[] = "<input class='form-check-input' type='checkbox' name='payment_details[pagibig_loan]' value='1' id='pagibig_loan' />";
												$html[] = "<span class='form-check-label' for='pagibig_loan'>Is the property eligible for a Pag-IBIG housing loan?</span>";
											$html[] = "</label>";
										$html[] = "</div>";

										$html[] = "<div class='form-group mb-4 brokerage-options'>";
											$html[] = "<label class='form-check form-switch cursor-pointer'>";
												$html[] = "<input class='form-check-input' type='checkbox' name='payment_details[assume_balance]' value='1' id='assume_balance' />";
												$html[] = "<span class='form-check-label' for='assume_balance'>Will the buyer assume the remaining loan balance? \"Assume Balance\"</span>";
												$html[] = "<span class='form-hint'>Buyer takes over the seller's existing mortgage instead of getting a new one</span>";
											$html[] = "</label>";
										$html[] = "</div>";
									$html[] = "</div>";

									$html[] = "</div>";
								$html[] = "</div>";
							$html[] = "</div>";
						$html[] = "</div>";
						$html[] = "<div class='text-end mt-3'>";
							$html[] = "<span class='btn btn-md btn-outline-secondary content-nav me-2' data-target='amenities'><i class='ti ti-arrow-left me-1 fw-bold fs-16'></i> Property Amenities</span>";
							$html[] = "<span class='btn btn-md btn-outline-secondary content-nav' data-target='images'>Property Images <i class='ti ti-arrow-right ms-1 fw-bold fs-16'></i></span>";
						$html[] = "</div>";
					$html[] = "</div>";
					/** END TERMS */

					/** IMAGES */
					$html[] = "<div id='images' class='content-container d-none'>";
						$html[] = "<div class='card'>";
							$html[] = "<div class='card-body'>";
								$html[] = "<div class='d-flex align-content-center justify-content-between'>";
									$html[] = "<h3 class='card-title mb-0'><i class='ti ti-photo fs-22 me-1'></i> Images</h3>";
									$html[] = "<div class='mb-3'>";
										$html[] = "<div class='btn-list'>";
											
											$html[] = "<div class=''>";
												$html[] = "<div class='dropstart'>";
													$html[] = "<span class='btn dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='true'><i class='ti ti-help fs-22'></i></span>";
													$html[] = "<div class='dropdown-menu dropdown-menu-card show' style='width: 25rem; position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate3d(-70px, 0, 0px);'>";
														
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
										
									$html[] = "</div>";
								$html[] = "</div>";
							$html[] = "</div>";
						$html[] = "</div>";
						$html[] = "<div class='text-end mt-3'>";
							$html[] = "<span class='btn btn-md btn-outline-secondary content-nav me-2' data-target='terms'><i class='ti ti-arrow-left me-1 fw-bold fs-16'></i> Transaction Terms</span>";
							$html[] = "<span class='btn btn-md btn-outline-secondary content-nav' data-target='documents'>Property Documents <i class='ti ti-arrow-right ms-1 fw-bold fs-16'></i></span>";
						$html[] = "</div>";
					$html[] = "</div>";
					/** END IMAGES */
					
					/** DOCUMENTS */
					$html[] = "<div id='documents' class='content-container d-none'>";
						$html[] = "<div class='card'>";
							$html[] = "<div class='card-body'>";
								$html[] = "<div class='d-flex align-content-center justify-content-between'>";
									$html[] = "<h3 class='card-title mb-0'><i class='ti ti-pdf fs-22 me-1'></i> Documents</h3>";
									$html[] = "<div class=''>";
										$html[] = "<div class='btn-list'>";
											
											$html[] = "<div class=''>";
												$html[] = "<div class='dropstart'>";
													$html[] = "<span class='btn dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='true'><i class='ti ti-help fs-22'></i></span>";
													$html[] = "<div class='dropdown-menu dropdown-menu-card show' style='width: 25rem; position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate3d(-70px, 0, 0px);'>";
														
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
								
								$html[] = "<div class='container-tight' style='max-height:85vh; overflow-y:auto;'>";
									$html[] = "<ul class='list-group list-group-flush files-container'></ul>";
								$html[] = "</div>";
							$html[] = "</div>";
						$html[] = "</div>";
						$html[] = "<div class='text-end mt-3'>";
							$html[] = "<span class='btn btn-md btn-outline-secondary content-nav me-2' data-target='images'><i class='ti ti-arrow-left me-1 fw-bold fs-16'></i> Property Images</span>";
							$html[] = "<span class='btn btn-md btn-outline-secondary content-nav hide_looking_for me-2' data-target='videos'>Property Videos <i class='ti ti-arrow-right ms-1 fw-bold fs-16'></i></span>";
							
							$html[] = "<span class='btn btn-outline-primary btn-save brokerage-options'><i class='ti ti-device-floppy me-2'></i> Save Property Listing</span>";
						$html[] = "</div>";
					$html[] = "</div>";
					/** END DOCUMENTS */

					$html[] = "<div id='videos' class='content-container d-none'>";
						$html[] = "<div class='card'>";
							$html[] = "<div class='card-body'>";
								$html[] = "<div class='hide_looking_for mb-5'>";
									
									$html[] = "<div class='p-4 bg-muted-lt border-bottom' style='margin: -20px -20px 20px -20px;'>";		
										$html[] = "<div class='' id='videoInput'></div>";
										$html[] = "<p class='form-hint mt-3'>Sample Youtube Url: https://www.youtube.com/watch?v=uiZVssPtPr4</p>";
									$html[] = "</div>";
										
									$html[] = "<h3 class='card-title'><i class='ti ti-brand-youtube fs-22 me-1'></i> Youtube Videos</h3>";
									
									$html[] = "<div class='video-list-container d-flex flex-wrap justify-content-center gap-3'></div>";
									
								$html[] = "</div>";
							$html[] = "</div>";
						$html[] = "</div>";
						$html[] = "<div class='text-end mt-3'>";
							$html[] = "<span class='btn btn-md btn-outline-secondary content-nav me-2' data-target='documents'><i class='ti ti-arrow-left me-1 fw-bold fs-16'></i> Property Documents</span>";
							$html[] = "<span class='btn btn-outline-primary btn-save'><i class='ti ti-device-floppy me-2'></i> Save Property Listing</span>";
						$html[] = "</div>";
					$html[] = "</div>";

				$html[] = "</div>";
			$html[] = "</div>";

		$html[] = "</form>";

	$html[] = "</div>";
$html[] = "</div>";