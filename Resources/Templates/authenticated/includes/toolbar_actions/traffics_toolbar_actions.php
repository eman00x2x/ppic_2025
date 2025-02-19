<?php

$html[] = "<div class='actions-wrapper d-flex gap-2 me-2 d-none'>";
	$html[] = "<div class='delete-wrapper'>";
		$html[] = "<span class='btn btn-md btn-outline-danger do-action' data-action='delete' data-action-value='true' data-url='".url("TrafficsController@confirmSelection")."'><i class='ti ti-trash me-1'></i> <span class='d-none d-sm-block'>Delete Selected</span></span>";
	$html[] = "</div>";
$html[] = "</div>";
	
