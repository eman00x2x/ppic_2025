<?php

$html[] = "<div class='actions-wrapper d-flex gap-2 me-2 d-none'>";
	$html[] = "<div class='change-source-wrapper'>";
		$html[] = "<span class='btn btn-md dropdown-toggle' id='set-source' data-bs-toggle='dropdown' aria-expanded='false'><i class='ti ti-status-change me-1'></i> <span class='d-none d-sm-block'>Change Source</span></span>";
		$html[] = "<ul class='dropdown-menu' aria-labelledby='set-source'>";
			foreach($data['sources'] as $source) {
				$html[] = "<li><span class='dropdown-item do-action cursor-pointer' data-action='set_source' data-action-value='$source' data-url='".url("LeadsController@confirmSelection")."'>Change to <b class='ms-1'>$source</b></span></li>";
			}
		$html[] = "</ul>";
	$html[] = "</div>";

	$html[] = "<div class='change-group-wrapper'>";
		$html[] = "<span class='btn btn-md dropdown-toggle' id='set-group' data-bs-toggle='dropdown' aria-expanded='false'><i class='ti ti-folder me-1'></i> <span class='d-none d-sm-block'>Move to Group</span></span>";
		$html[] = "<ul class='dropdown-menu' aria-labelledby='set-group' style='max-height:300px; overflow-x: hidden; overflow-y: auto;'>";
			$html[] = "<li><span class='dropdown-item do-action cursor-pointer' data-action='move_to_group' data-action-value='0_Ungrouped' data-url='".url("LeadsController@confirmSelection")."'>Move to <b class='ms-1'>Ungrouped</b></span></li>";
			foreach($data['groups'] as $group) {
				$html[] = "<li><span class='dropdown-item do-action cursor-pointer' data-action='move_to_group' data-action-value='".$group['lead_group_id']."_".$group['name']."' data-url='".url("LeadsController@confirmSelection")."'>Move to <b class='ms-1'>".$group['name']."</b></span></li>";
			}
		$html[] = "</ul>";
	$html[] = "</div>";
	
	$html[] = "<div class='delete-wrapper'>";
		$html[] = "<span class='btn btn-md btn-outline-danger do-action' data-action='delete' data-action-value='true' data-url='".url("LeadsController@confirmSelection")."'><i class='ti ti-trash me-1'></i> <span class='d-none d-sm-block'>Delete Selected</span></span>";
	$html[] = "</div>";
$html[] = "</div>";
	
