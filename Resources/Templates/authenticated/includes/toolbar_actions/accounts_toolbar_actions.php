<?php

$html[] = "<div class='actions-wrapper d-flex gap-2 me-2 d-none'>";
	if(isset($data['toolbar']['components']) && in_array("status_change", $data['toolbar']['components'])) {
		$html[] = "<div class='update-status-wrapper'>";
			$html[] = "<span class='btn btn-md dropdown-toggle' id='statuses' data-bs-toggle='dropdown' aria-expanded='false'><i class='ti ti-status-change me-1'></i> <span class='d-none d-sm-block'>Update Status</span></span>";
			$html[] = "<ul class='dropdown-menu' aria-labelledby='statuses'>";
				$html[] = "<li><span class='dropdown-item do-action cursor-pointer' data-action='set_status' data-action-value='active' data-url='".url("AccountsController@confirmSelection")."'>Set all selected <b class='ms-1'>Active</b></span></li>";
				$html[] = "<li><span class='dropdown-item do-action cursor-pointer' data-action='set_status' data-action-value='ban' data-url='".url("AccountsController@confirmSelection")."'>Set all selected <b class='ms-1'>Ban</b></span></li>";
			$html[] = "</ul>";
		$html[] = "</div>";
	}

	$html[] = "<div class='delete-wrapper'>";
		$html[] = "<span class='btn btn-md btn-outline-danger btn-single-delete'><i class='ti ti-trash me-1'></i> <span class='d-none d-sm-block'>Delete Selected</span></span>";
	$html[] = "</div>";
$html[] = "</div>";
	
