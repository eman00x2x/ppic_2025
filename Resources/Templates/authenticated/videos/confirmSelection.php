<?php

use EO\View;

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
					$html[] = "<table class='table card-table table-vcenter text-nowrap datatable'>";
						$html[] = "<thead>";
							$html[] = "<tr>";
								$html[] = "<th class='text-center align-middle'>#</th>";
								$html[] = "<th class='align-middle'></th>";
								$html[] = "<th class='align-middle'>ID</th>";
								$html[] = "<th class='align-middle'>Category</th>";
								$html[] = "<th class='align-middle'>Url</th>";
								$html[] = "<th class='align-middle'>Created Date</th>";
							$html[] = "</tr>";
						$html[] = "</thead>";
						$html[] = "<tbody class='data-container'>";
							if($data['videos']) {

								$item_number = View::$collections['itemStartingNumber'] ?? 0;

								for($i=0; $i<count($data['videos']); $i++) {
									
									$html[] = "<tr>";
										$html[] = "<td class='text-center'>".$item_number."</td>";
										$html[] = "<td><span class='avatar avatar-xl' style='background-image: url(".$data['videos'][$i]['thumbnail']['default'].")'></span></td>";
										$html[] = "<td>".$data['videos'][$i]['unique_id']."</td>";
										$html[] = "<td class='category-text'>".$data['videos'][$i]['category']."</td>";
										$html[] = "<td>".$data['videos'][$i]['url']."</td>";
										$html[] = "<td>".$data['videos'][$i]['created_date']."</td>";
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