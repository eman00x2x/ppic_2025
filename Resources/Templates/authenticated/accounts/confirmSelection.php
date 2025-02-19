<?php

use EO\View;
use EO\Auth\Auth as Auth;

$html[] = "<div class='container-xl'>";
	$html[] = "<div class='row justify-content-center'>";
		$html[] = "<div class='col-lg-8 col-md-8 col-sm-12 col-12'>";

			$html[] = "<div class='d-flex mb-3'>";
				$html[] = "<div class=''>";
					$html[] = "<h1 class='text-info'><i class='ti ti-info-square-rounded fs-36 me-1'></i> Confirm Selection</h1>";						
					$html[] = "<p class='m-0 p-0'>You are performing an action on ".count(explode(",", $data['ids']))." selected items</p>";
					$html[] = "<p class='m-0 p-0'>Action: ".ucwords(str_replace("_", " ", $data['action']))." ".$data['action_value']."</p>";
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
					$html[] = "<table class='table card-table table-vcenter text-nowrap datatable'>";
						$html[] = "<thead>";
							$html[] = "<tr>";
								$html[] = "<th class='text-center align-middle'>#</th>";
								$html[] = "<th class='align-middle'>Photo</th>";
								$html[] = "<th class='align-middle'>Name</th>";
								$html[] = "<th class='align-middle'>Username</th>";
								$html[] = "<th class='align-middle'>Email Address</th>";
								$html[] = "<th class='align-middle'>Account Type</th>";
								$html[] = "<th class='align-middle'>Status</th>";
								$html[] = "<th class='align-middle'>Registered Date</th>";
							$html[] = "</tr>";
						$html[] = "</thead>";
						$html[] = "<tbody class='data-container'>";
							if($data['accounts']) {

								$item_number = View::$collections['itemStartingNumber'] ?? 0;

								for($i=0; $i<count($data['accounts']); $i++) {
									
									$html[] = "<tr>";
										$html[] = "<td class='text-center'>".$item_number."</td>";
										$html[] = "<td><span class='avatar avatar-md' style='background-image: url(".$data['accounts'][$i]['photo'].")'></span></td>";
										$html[] = "<td>".$data['accounts'][$i]['full_name']."</td>";
										$html[] = "<td>".$data['accounts'][$i]['username']."</td>";
										$html[] = "<td>".$data['accounts'][$i]['email']."</td>";
										$html[] = "<td>".$data['accounts'][$i]['account_type']."</td>";
										$html[] = "<td class='statusText'><span class='badge bg-".($data['accounts'][$i]['status'] == "active" ? "success" : "danger")." me-1'></span> ".$data['accounts'][$i]['status']."</td>";
										$html[] = "<td>".$data['accounts'][$i]['registered_date']."</td>";
									$html[] = "</tr>";

									$item_number++;

								}
							}
						$html[] = "</tbody>";
					$html[] = "</table>";
				$html[] = "</div>";
			$html[] = "</div>";

			$html[] = "<div class='btn-list mt-3 justify-content-end'>";
				$html[] = "<span class='btn btn-md btn-success btn-confirm-selection' ><i class='ti ti-check me-1'></i> Continue</span>";
				$html[] = "<span class='btn btn-md' data-bs-dismiss='modal'><i class='ti ti-x me-1'></i> Cancel</span>";
			$html[] = "</div>";

		$html[] = "</div>";
	$html[] = "</div>";
$html[] = "</div>";