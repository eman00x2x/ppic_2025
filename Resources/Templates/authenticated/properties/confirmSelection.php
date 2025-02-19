<?php

use EO\View;
use EO\Auth\Auth as Auth;

$html[] = "<div class='container-xl'>";
	$html[] = "<div class='row justify-content-center'>";
		$html[] = "<div class='col-lg-8 col-md-8 col-sm-12 col-12'>";

			$html[] = "<div class='d-flex mb-3'>";
				$html[] = "<div class=''>";
					$html[] = "<h1 class='text-".($data['action'] == "delete" ? "danger" : "info")."'><i class='ti ti-info-square-rounded fs-36 me-1'></i> Confirm Selection</h1>";
					$html[] = "<p class='m-0 p-0'>".$data['message']."</p>";
				$html[] = "</div>";
				$html[] = "<div class=''>";
					$html[] = "<span class='btn-close' data-bs-dismiss='modal'></span>";
				$html[] = "</div>";
			$html[] = "</div>";

			$html[] = "<form id='form' method='POST' action='".$data['url']."' method='post'>";
				$html[] = "<input type='hidden' name='ids' value='".$data['ids']."' />";
				$html[] = "<input type='hidden' name='action' value='".$data['action']."' />";
				$html[] = "<input type='hidden' name='action_value' value='".$data['action_value']."' />";
			$html[] = "</form>";

			$html[] = "<div class='' style='max-height:72vh; overflow-x: auto;'>";
				$html[] = "<div class='table-responsive'>";
					$html[] = "<table class='table table-md table-hover card-table table-vcenter text-nowrap table-list'>";
						$html[] = "<thead>";
							$html[] = "<tr>";
								$html[] = "<th class='text-center align-middle'>#</th>";
								$html[] = "<th class='align-middle'>Image</th>";
								$html[] = "<th class='align-middle'>Title</th>";
								$html[] = "<th class='align-middle text-end'>Price</th>";
								
								if(Auth::isAdmin()) { $html[] = "<th class='align-middle'>Posted By</th>"; }

								$html[] = "<th class='align-middle'>Created Date</th>";
							$html[] = "</tr>";
						$html[] = "</thead>";
						$html[] = "<tbody class='data-container'>";
							if($data['properties']) {

								$item_number = View::$collections['itemStartingNumber'] ?? 0;

								for($i=0; $i<count($data['properties']); $i++) {
									
									$html[] = "<tr class='row_".$data['properties'][$i]['property_id']."'>";
										$html[] = "<td class='text-center'>".$item_number."</td>";
										$html[] = "<td><span class='avatar avatar-md' style='background-image: url(".$data['properties'][$i]['thumb_img'].")'></span></td>";
										$html[] = "<td><a href='".url("properties.view", ["id" => $data['properties'][$i]['property_id']])."' class=''>";
											$html[] = "".$data['properties'][$i]['short_title']." <i class='ti ti-link'></i>";
											$html[] = "<div class='mt-2 fs-13 d-flex gap-2'>";
												$html[] = "<span class='badge bg-azure text-azure-fg'><i class='ti ti-category-2'></i> ".$data['properties'][$i]['category']."</span>";

												switch($data['properties'][$i]['listing_type']) {
													case "For Sale": $html[] = "<span class='badge bg-teal text-teal-fg'><i class='ti ti-report-money'></i> <span class='offer-text'>".$data['properties'][$i]['listing_type']."</span></span>"; break;
													case "For Rent": $html[] = "<span class='badge bg-cyan text-cyan-fg'><i class='ti ti-report-money'></i> <span class='offer-text'>".$data['properties'][$i]['listing_type']."</span></span>"; break;
												}

												$html[] = "<span class='status-text'>";
												switch($data['properties'][$i]['status']) {
													case 1: $html[] = "<span class='badge badge-outline text-green'><i class='ti ti-home-dollar'></i> ".$data['properties'][$i]['availability']."</span>"; break;
													case 2: $html[] = "<span class='badge bg-red text-red-fg'><i class='ti ti-home-dollar'></i> ".$data['properties'][$i]['availability']."</span>"; break;
													case 3: $html[] = "<span class='badge bg-secondary text-white'><i class='ti ti-circle-letter-x'></i> ".$data['properties'][$i]['availability']."</span>"; break;
												}
												$html[] = "</span>";
											$html[] = "</div>";
										$html[] = "</a></td>";
										$html[] = "<td class='text-end'><i class='ti ti-currency-peso'></i> ".$data['properties'][$i]['price_tag']."</td>";
										
										if(Auth::isAdmin()) {
											$html[] = "<td class='align-middle'><a href='".url("accounts.view", ["id" => $data['properties'][$i]['account']['account_id']])."' class=''>";
												$html[] = "<div class='d-flex lh-1 text-reset p-0 cursor-pointer'>";
													$html[] = "<span class='avatar avatar-md' style='background-image: url(".$data['properties'][$i]['account']['photo'].")'></span>";
													$html[] = "<span class='d-block ps-2 mt-2'>".$data['properties'][$i]['account']['full_name']." <i class='ti ti-link'></i></span>";
												$html[] = "</div>";
											$html[] = "</a></td>";
										}

										$html[] = "<td><i class='ti ti-calendar'></i> ".$data['properties'][$i]['created_date']."</td>";
									$html[] = "</tr>";

									$item_number++;

								}
							}
						$html[] = "</tbody>";
					$html[] = "</table>";
				$html[] = "</div>";
			$html[] = "</div>";

			$html[] = "<div class='btn-list mt-3 justify-content-end'>";
							
				if($data['action'] == "delete") {
					$html[] = "<span class='btn btn-md btn-danger btn-confirm-selection' ><i class='ti ti-check me-1'></i> Confirm Deletion</span>";
				}else {
					$html[] = "<span class='btn btn-md btn-success btn-confirm-selection' ><i class='ti ti-check me-1'></i> Confirm Selection</span>";
				}

				$html[] = "<span class='btn btn-md' data-bs-dismiss='modal'><i class='ti ti-x me-1'></i> Cancel</span>";
			$html[] = "</div>";

		$html[] = "</div>";
	$html[] = "</div>";
$html[] = "</div>";