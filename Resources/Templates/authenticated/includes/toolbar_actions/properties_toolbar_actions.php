<?php

$html[] = "<div class='actions-wrapper d-flex gap-2 me-2 d-none'>";
	$html[] = "<div class='change-category-wrapper'>";
		$html[] = "<span class='btn btn-md dropdown-toggle' id='set-category' data-bs-toggle='dropdown' aria-expanded='false'><i class='ti ti-status-change me-1'></i> <span class='d-none d-sm-block'>Change Category</span></span>";
		$html[] = "<ul class='dropdown-menu' aria-labelledby='set-category' style='height:300px; overflow-x: hidden; overflow-y: auto;'>";
			foreach($data['categories'] as $key => $categories) {
				$html[] = "<li class='border-top'><span class='dropdown-item text-muted'>$key</span></li>";
				foreach($categories as $category) {
					$html[] = "<li><span class='dropdown-item do-action cursor-pointer ms-2 py-1' data-action='set_category' data-action-value='$category' data-url='".url("PropertiesController@confirmSelection")."'>Move to <b class='ms-1'>$category</b></span></li>";
				}
			}
		$html[] = "</ul>";
	$html[] = "</div>";
	$html[] = "<div class='update-status-wrapper btn-list'>";
		$html[] = "<span class='btn btn-outline-primary do-action' data-action='set_status' data-action-value='1' data-url='".url("PropertiesController@confirmSelection")."'><i class='ti ti-file-check me-1'></i> <span class='d-none d-sm-block'>Available</span></span>";
		$html[] = "<span class='btn btn-outline-secondary do-action' data-action='set_status' data-action-value='2' data-url='".url("PropertiesController@confirmSelection")."'><i class='ti ti-home-dollar me-1'></i> <span class='d-none d-sm-block'>Sold</span></span>";
		$html[] = "<span class='btn btn-outline-danger do-action' data-action='delete' data-action-value='3' data-url='".url("PropertiesController@confirmSelection")."'><i class='ti ti-trash me-1'></i> <span class='d-none d-sm-block'>Remove</span></span>";
	$html[] = "</div>";
$html[] = "</div>";