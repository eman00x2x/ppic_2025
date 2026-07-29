<?php

use EO\View;

View::setMasterTemplate(path: "/website/template.php");

View::define( name: "social_media_share", path: "/website/includes/social.media.share.php", data: $data );

View::setDocumentHeader(
	data: [
		'title' => $data['title'],
		'description' => '',
		'url' => $data['url'],
		'image' => $data['thumb_img'],
		'modified_at' => $data['modified_at'],
		"scripts" => [
			CDN . "/js/main/app/website/relatedProperties.js",
			CDN . "/js/vendor/validatejs-0.13.1/validate.min.js"
		]
	]
);

$social_media_share = View::include("social_media_share", $data);

$html[] = "<div class='modal' id='modalSendMessageForm' aria-labelledby='modalSendMessageFormLabel'>";
	$html[] = "<div class='modal-dialog modal-fullscreen '>";
		$html[] = "<div class='modal-content bg-primary'>";

			$html[] = "<div class='modal-body'>";
				$html[] = "<button type='button' class='btn-close text-white fs-20' data-bs-dismiss='modal' aria-label='Close'>X</button>";
				$html[] = "<div class='send-message-modal-container'></div>";
			$html[] = "</div>";

		$html[] = "</div>";
	$html[] = "</div>";
$html[] = "</div>";

$html[] = "<div class='bg-primary text-white py-3 px-2'>";
	$html[] = "<div class='container'>";

		$html[] = "<div class='row align-items-center'>";
			$html[] = "<div class='col-md-8 col-12'>";
				$html[] = "<div class='mb-3'>";
					$html[] = "<h1 class='fs-26 mb-0 text-white'>".$data['title']."</h1>";
					$html[] = "<p class='mb-0 fs-14'><i class='ti ti-map-pin me-1'></i> ".$data['complete_address']."</p>";
					$html[] = $social_media_share;
				$html[] = "</div>";
			$html[] = "</div>";
			$html[] = "<div class='col-md-4 col-12'>";
				$html[] = "<div class='align-self-stretch'>";
					$html[] = "<div class='bg-white text-primary px-4 py-2 rounded'>";
						$html[] = "<span class='text-muted fs-12' >Price</span>";
						$html[] = "<span class='d-block fw-bold fs-40' style='margin-top:-10px;'>&#8369;".$data['price_tag']."</span>";
					$html[] = "</div>";
				$html[] = "</div>";
			$html[] = "</div>";
		$html[] = "</div>";

	$html[] = "</div>";
$html[] = "</div>";

$html[] = "<div class='page-body'>";
	
	$html[] = "<div class='container'>";
		$html[] = "<div class='row mb-4'>";
			$html[] = "<div class='col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12'>";

				$html[] = "<div id='photos' class=' mb-3 '>";
					
					$html[] = "<div class='avatar avatar-xxxl w-100 bg-white' style='position: relative; height: 500px; background-image: url(".$data['thumb_img'].")'>";
						$html[] = "<a data-fslightbox data-type='image' href='".$data['thumb_img']."' class='stretched-link'></a>";
						
						if($data['total_images'] > 1) {
							$html[] = "<div class='position-absolute top-0 start-0 p-3'>";
								$html[] = "<span class='fw-bold bg-white text-dark p-2'><i class='ti ti-photo'></i> +".$data['total_images']."</span>";
							$html[] = "</div>";
						}

						$html[] = "<div class='position-absolute bottom-0 start-0 w-100 py-3' style='z-index: 2;'>";
							if($data['images']) {
								$html[] = "<div class='d-flex gap-2 overflow-auto px-3' style='height: 85px;'>";

								$total_videos = (!empty($data['videos']) ? count($data['videos']) : 0);

								$limit = $total_videos >= 7 ? 2 : (8 - $total_videos) ;
								if(!empty($data['videos'])) {
									foreach($data['videos'] as $video) {
										$html[] = "<div><a data-fslightbox href='".$video['url']."' id='youtube_video'>";
											$html[] = "<div class='avatar avatar-xl p-2 bg-dark' style='background-image: url(".$video['thumbnail']['default'].")'><i class='ti ti-brand-youtube me-1 fs-36'></i></div>";
										$html[] = "</a></div>";
									}
								}

								for($i=0; $i<$data['total_images']; $i++) {

									if($data['thumb_img'] != $data['images'][$i]['url']) {

										if($i < $limit) { $hide = "";

											if($i > ($limit - 3)) {
												$hide = "d-none d-md-block";
											}
											
											$html[] = "<div class='$hide'><a data-fslightbox data-type='image' href='".$data['images'][$i]['url']."'>";
												$html[] = "<div class='avatar avatar-xl' style='position:relative; background-image: url(".$data['images'][$i]['url'].")'>";
													
													if($i == ($limit - 3)) {
														if(($data['total_images'] - 3) > $i) {
															$html[] = "<div class='overlay d-md-none d-sm-block' style='z-index: 1; position:absolute; background-color: rgba(0, 0, 0, 0.5); height: 100%; width: 100%;'>";
																$html[] = "<span class='text-white d-block mt-4' style='z-index: 2;'>+".($data['total_images'] - ($limit - 1))."</span>";
															$html[] = "</div>";
														}
													}
												
													if($i == ($limit - 1)) {
														if(($data['total_images'] - 1) > $i) {
															$html[] = "<div class='overlay d-none d-md-block' style='z-index: 1; position:absolute; background-color: rgba(0, 0, 0, 0.5); height: 100%; width: 100%;'>";
																$html[] = "<span class='text-white d-block mt-4' style='z-index: 2;'>+".($data['total_images'] - $limit)."</span>";
															$html[] = "</div>";
														}
													}

												$html[] = "</div>";
											$html[] = "</a></div>";
											
										}else {
											$html[] = "<a data-fslightbox data-type='image' class='d-none' href='".$data['images'][$i]['url']."'></a>";
										}
									}
								}
								$html[] = "</div>";
							}
						$html[] = "</div>";

					$html[] = "</div>";

				$html[] = "</div>";

				$html[] = "<div class='mb-3'>";
					$html[] = "<p><span class='text-muted'><i class='ti ti-tag me-1'></i> Tags:</span> ".implode(", ", array_map("ucwords", $data['tags']))."</p>";
				$html[] = "</div>";

				$html[] = "<div class='border bg-white p-3 py-5'>";
					$html[] = "<h3><i class='ti ti-ruler-2 me-1'></i> Technical Description</h3>";
					$html[] = "<div class='d-flex flex-wrap justify-content-between gap-3'>";
						if($data['lot_area'] > 0) { $html[] = "<div class='flex-fill p-2 border text-center fw-bold fs-26 text-primary'><i class='ti ti-maximize me-1'></i> ".$data['lot_area']."<span class='text-muted fs-12'>m<sup>2</sup></span> <span class='d-block fw-normal text-muted fs-12'>Land Area</span></div>"; }
						if($data['floor_area'] > 0) { $html[] = "<div class='flex-fill p-2 border text-center fw-bold fs-26 text-primary'><i class='ti ti-ruler me-1'></i> ".$data['floor_area']."<span class='text-muted fs-12'>m<sup>2</sup></span> <span class='d-block fw-normal text-muted fs-12'>Floor Area</span></div>"; }
						if($data['bedroom'] > 0) { $html[] = "<div class='flex-lg-fill p-2 border text-center fw-bold fs-26 text-primary'>".$data['bedroom']." <i class='ti ti-bed ms-1'></i> <span class='d-block fw-normal text-muted fs-12'>Bed room</span></div>"; }
						if($data['bathroom'] > 0) { $html[] = "<div class='flex-lg-fill p-2 border text-center fw-bold fs-26 text-primary'>".$data['bathroom']." <i class='ti ti-bath ms-1'></i> <span class='d-block fw-normal text-muted fs-12'>Bath room</span></div>"; }
						if($data['parking'] > 0) { $html[] = "<div class='flex-lg-fill p-2 border text-center fw-bold fs-26 text-primary'>".$data['parking']." <i class='ti ti-car-garage ms-1'></i> <span class='d-block fw-normal text-muted fs-12'>Car spaces</span></div>"; }
					$html[] = "</div>";
				$html[] = "</div>";

				$html[] = "<div class='border bg-white border-top-0 p-3 py-5'>";
					$html[] = "<h3><i class='ti ti-file-description me-1'></i> Description</h3>";
					$html[] = "<div class='overflow-auto' style='max-height: 300px;'>";
						$html[] = $data['long_desc'];
					$html[] = "</div>";
				$html[] = "</div>";

				if(!empty($data['amenities']) && is_array($data['amenities']) && count($data['amenities']) > 0) {
					$html[] = "<div class='border border-top-0 bg-white p-3 py-5'>";
						$html[] = "<h3><i class='ti ti-home-shield me-1'></i> Amenities</h3>";
						$html[] = "<ul class='m-0 p-0 column-list'>";
							foreach($data['amenities'] as $amenities) {
								$html[] = "<li class='m-0 p-0'><i class='ti ti-check me-1'></i>".$amenities."</li>";
							}
						$html[] = "</ul>";
					$html[] = "</div>";
				}

				$html[] = "<div class='border bg-white border-top-0 p-3 py-5'>";
					$html[] = "<h3><i class='ti ti-wallet me-1'></i> Price Breakdown</h3>";
					$html[] = "<table class='table table-md'>";
					$html[] = "<tr>";
						$html[] = "<td class='w-50'>Price</td>";
						$html[] = "<td>&#8369; ".$data['price_tag']."</td>";
					$html[] = "</tr>";
					
					if($data['reservation'] > 0) {
						$html[] = "<tr>";
							$html[] = "<td>Reservation</td>";
							$html[] = "<td>&#8369; ".$data['reservation']."</td>";
						$html[] = "</tr>";
					}

					$html[] = "<tr>";
						$html[] = "<td>Mode of Payment</td>";
						$html[] = "<td>".$data['payment_details']['payment_mode']."</td>";
					$html[] = "</tr>";
					$html[] = "<tr>";
						$html[] = "<td>Bank Loan</td>";
						$html[] = "<td>".(isset($data['payment_details']['bank_loan']) && $data['payment_details']['bank_loan'] == 1 ? "<i class='ti ti-check'></i> Yes" : "<i class='ti ti-circle-x'></i> No")."</td>";
					$html[] = "</tr>";
					$html[] = "<tr>";
						$html[] = "<td>Pag-ibig Housing Loan</td>";
						$html[] = "<td>".(isset($data['payment_details']['pagibig_loan']) && $data['payment_details']['pagibig_loan'] == 1 ? "<i class='ti ti-check'></i> Yes" : "<i class='ti ti-circle-x'></i> No")."</td>";
					$html[] = "</tr>";

					if($data['service_type'] == "general brokerage") {
						if(isset($data['payment_details']['tax_allocation'])) {
							$html[] = "<tr>";
								$html[] = "<td>Tax Allocation</td>";
								$html[] = "<td>".$data['payment_details']['tax_allocation']."</td>";
							$html[] = "</tr>";
						}
					}

					$html[] = "</table>";
				$html[] = "</div>";

				/** MORTGAGE CALCULATOR */
				$html[] = "<div class='mortgage-calculator-form mt-5 p-3 bg-primary border'>";
					
					$html[] = "<input type='hidden' id='sellingPrice' value='".$data['price']."' />";
					$html[] = "<h3 id='mortgage_calculator' class='mb-2 text-white'><i class='ti ti-calculator'></i> Mortgage Calculator</h3>";
					$html[] = "<p class='mb-2 text-white'>With the current price of <b>&#8369;".number_format($data['price'],0)."</b> and mortgage rates as stated below, expect to have a monthly payment of:</p>";
					
					$html[] = "<div class='p-4 border bg-primary-lt'>";
						$html[] = "<div class='row align-items-center justify-content-center'>";
							$html[] = "<div class='col-12 col-lg-6 col-md-12'>";
								$html[] = "<div class='text-center text-highlight mb-3'>";
									$html[] = "<span class='d-block mb-2'>Monthly Payment of</span>";
									$html[] = "<span id='result' class='fs-36 fw-bold monthly_dp'></span>";
								$html[] = "</div>";
							$html[] = "</div>";
							$html[] = "<div class='col-12 col-lg-6 col-md-12'>";
								$html[] = "<div class='d-flex gap-2 justify-content-center'>";
									$html[] = "<div class='form-floating flex-fill'>";
										$html[] = "<div id='dpSelection'></div>";
										$html[] = "<label for='dpSelection'>Down Payment</label>";
									$html[] = "</div>";
									$html[] = "<div class='form-floating flex-fill'>";
										$html[] = "<div id='interestSelection'></div>";
										$html[] = "<label for='mortgageInterest'>Interest Rate</label>";
									$html[] = "</div>";
									$html[] = "<div class='form-floating flex-fill'>";
										$html[] = "<div id='yearSelection'></div>";
										$html[] = "<label for='mortgageYear'>Years</label>";
									$html[] = "</div>";
								$html[] = "</div>";
								$html[] = "<p class='fs-12 text-muted m-0 mt-2'>You can use the mortgage calculator to estimate the monthly payment with different values.</p>";
								
							$html[] = "</div>";
						$html[] = "</div>";
					$html[] = "</div>";
					$html[] = "<p class='mt-2 mb-0 p-0 text-muted fs-12'>* The accuracy and applicability of this calculator are not guaranteed.</p>";
				$html[] = "</div>";
				/** MORTGAGE CALCULATOR END */

			$html[] = "</div>";
			$html[] = "<div class='col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12'>";
				$html[] = "<div class='inquiry-form sticky-top d-none d-md-block'>";
					$html[] = "<div class='inquiry-form-wrapper'>";
						$html[] = "<div class='inquiry-form-container card bg-primary text-white'>";
							$html[] = "<div class='card-body'>";
								$html[] = "<h3 class='text-white'><i class='ti ti-message me-1'></i> Send message</h3>";

								$html[] = "<div class='d-flex align-items-center gap-3 mb-3 border-bottom pb-3 border-white'>";
									$html[] = "<div class='avatar avatar' style='background-image: url(".$data['account']['photo'].")'></div>";
									$html[] = "<div class=''>";
										$html[] = "<span class=''>".$data['account']['full_name']."</span>";
										$html[] = "<span class=''></span>";
									$html[] = "</div>";
								$html[] = "</div>";

								$html[] = "<form id='inquiry-form' action='".url("web.save.leads")."' method='POST'>";

									$html[] = "<input type='hidden' name='source' value='Philproperties Website' />";
									$html[] = "<input type='hidden' name='account_id' value='".$data['account_id']."' />";
									$html[] = "<input type='hidden' name='reference' value='".$data['reference']."' />";
									$html[] = "<input type='hidden' name='send_to' value='".$data['account']['email']."' />";
									$html[] = "<input type='hidden' name='label' value='' />";

									$html[] = "<div class='form-floating mb-3'>";
										$html[] = "<input type='text' name='name' id='name' value='' class='form-control text-dark' placeholder='Name' />";
										$html[] = "<label for='name' class='text-dark'>Name</label>";
									$html[] = "</div>";

									$html[] = "<div class='form-floating mb-3 '>";
										$html[] = "<input type='text' name='contact_number' id='contact_number' value='' class='form-control text-dark' placeholder='Contact Number' />";
										$html[] = "<label for='name' class='text-dark'>Contact Number</label>";
									$html[] = "</div>";

									$html[] = "<div class='form-floating mb-3 show-hide-input d-none'>";
										$html[] = "<input type='email' name='email' id='email' value='' class='form-control text-dark' placeholder='Email Address' />";
										$html[] = "<label for='name' class='text-dark'>Email Address</label>";
									$html[] = "</div>";

									$html[] = "<div class='form-floating mb-3 show-hide-input d-none'>";
										$html[] = "<textarea name='message' class='form-control text-dark'></textarea>";
										$html[] = "<label for='name' class='text-dark'>Message</label>";
									$html[] = "</div>";

									$html[] = "<div class=''>";
										$html[] = "<div class='form-check form-switch'>";
											$html[] = "<input class='form-check-input' type='checkbox' value='1' id='scheduleSwitch'>";
											$html[] = "<label class='form-check-label cursor-pointer' for='scheduleSwitch'>Include viewing schedule</label>";
										$html[] = "</div>";
									$html[] = "</div>";

									$html[] = "<div class='form-floating mb-3 viewing-schedule-input d-none'>";
										$html[] = "<input type='datetime-local' name='viewing_date' value='' class='form-control text-dark' placeholder='Contact Number' />";
										$html[] = "<label for='name' class='text-dark'>Viewing Date</label>";
									$html[] = "</div>";

									$html[] = "<div class='d-flex justify-content-between align-items-center gap-2  mb-3'>";
										$html[] = "<div class='flex-grow-1'>";
											$html[] = "<div class='form-floating'>";
												$html[] = "<input type='text' name='security_code' value='' class='form-control text-dark' placeholder='Enter Security Code' />";
												$html[] = "<label for='name' class='text-dark'>Enter Security Code</label>";
											$html[] = "</div>";
										$html[] = "</div>";
										$html[] = "<div class='flex-fill align-self-stretch text-white text-center border border-white pt-3 rounded'>";
											$html[] = "<input type='hidden' name='generated_security_code' id='generated_security_code' value='' class='form-control text-dark' placeholder='Enter Security Code' />";
											$html[] = "<span id='securityCodeText'><span class='spinner-border spinner-border-sm'></span></span><span class='d-block fs-9 text-muted'>Security Code</span>";
										$html[] = "</div>";
									$html[] = "</div>";

									$html[] = "<p class='text-muted fs-12'>By clicking send message, you accept our <a href='".url("web.terms")."'>Terms and Condition</a> and <a href='".url("web.privacy")."'>Privacy Policy</a> page.</p>";

									$html[] = "<div class='response mb-3'></div>";
									$html[] = "<span class='btn btn-outline-light btn-send-message w-100'><i class='ti ti-send me-1'></i> Send Message</span>";
								$html[] = "</form>";
							
								$html[] = "<div class=' mt-4 '>";
									$html[] = $social_media_share;
								$html[] = "</div>";

							$html[] = "</div>";
						$html[] = "</div>";
					$html[] = "</div>";
				$html[] = "</div>";
			$html[] = "</div>";
		$html[] = "</div>";
	$html[] = "</div>";

	$html[] = "<div class='bg-white border-top'>";
		$html[] = "<div class='container'>";
			/* $html[] = "<div class='row'>";
				$html[] = "<div class='col-xl-8 col-lg-8 col-md-8 col-12'>"; */
					$html[] = "<div class='related-properties-wrapper pt-4' data-uri='".url("web.related.properties", null, $data['related_properties_search'])."'>";
						$html[] = "<h3 class='fw-bold fs-20'><i class='ti ti-building'></i> Related Properties</h3>";
						$html[] = "<div class='related-properties-container'></div>";
					$html[] = "</div>";
				/* $html[] = "</div>";

				$html[] = "<div class='col-xl-4 col-lg-4 col-md-4 col-12'>";
				$html[] = "</div>";
			$html[] = "</div>"; */
		$html[] = "</div>";
	$html[] = "</div>";
$html[] = "</div>";

$html[] = "<div class='sticky-bottom d-block d-md-none'>";
	$html[] = "<div class='p-3 pb-2 bg-white w-100 border-top'>";
		$html[] = "<div class='container'>";
			$html[] = "<span class='mb-2 btn btn-primary w-100' data-bs-toggle='modal' data-bs-target='#modalSendMessageForm' data-bs-backdrop='static' data-bs-keyboard='false'><i class='ti ti-message me-1'></i> Send Message</span>";
		$html[] = "</div>";
	$html[] = "</div>";
$html[] = "</div>";

$html[] = "<script type='text/javascript' src='".CDN . "/js/vendor/tabler/dist/libs/fslightbox/index.js"."'></script>";