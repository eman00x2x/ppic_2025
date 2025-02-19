<?php

$html[] = "<div class='actions-wrapper d-flex gap-2 me-2 d-none'>";
	$html[] = "<div class='change-category-wrapper'>";
		$html[] = "<span class='btn btn-md dropdown-toggle' id='set-category' data-bs-toggle='dropdown' aria-expanded='false'><i class='ti ti-status-change me-1'></i> <span class='d-none d-sm-block'>Change Category</span></span>";
		$html[] = "<ul class='dropdown-menu' aria-labelledby='set-category'>";
			foreach($data['categories'] as $category) {
				$html[] = "<li><span class='dropdown-item do-action cursor-pointer' data-action='set_category' data-action-value='$category' data-url='".url("VideosController@confirmSelection")."'>Move to <b class='ms-1'>$category</b></span></li>";
			}
		$html[] = "</ul>";
	$html[] = "</div>";
	$html[] = "<div class='delete-wrapper'>";
		$html[] = "<span class='btn btn-md btn-outline-danger do-action' data-action='delete' data-action-value='true' data-url='".url("VideosController@confirmSelection")."'><i class='ti ti-trash me-1'></i> <span class='d-none d-sm-block'>Delete Selected</span></span>";
	$html[] = "</div>";
$html[] = "</div>";