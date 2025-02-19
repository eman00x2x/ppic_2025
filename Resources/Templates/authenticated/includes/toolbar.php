<?php

use EO\View;

if(!isset($data['url'])) {
	$url = $data['controller'] . "@index";
} else {
	$url = $data['url'];
}

if(isset($data['toolbar']['filter'])) {
	$html[] = View::include($data['toolbar']['filter']);
}

$html[] = "<div class='d-flex pb-3 border-bottom overflow-auto'>";

	if(isset($data['toolbar']['actions'])) {
		$html[] = View::include($data['toolbar']['actions']);
	}

	if(isset($data['toolbar']['components']) && in_array("search", $data['toolbar']['components'])) {
		$html[] = "<div class='search-wrapper align-items-center me-1'>";
			$html[] = "<div class='input-icon'>";
				$html[] = "<span class='input-icon-addon'><i class='ti ti-search'></i></span>";
				$html[] = "<input type='text' id='search' class='form-control w-auto' placeholder='Search' data-url='".url($url)."'  />";
			$html[] = "</div>";
		$html[] = "</div>";
	}

	$html[] = "<div class='btn-list-wrapper ms-auto cursor-pointer d-flex'>";
		
		if(isset($data['toolbar']['filter'])) {
			$html[] = "<div class='filter-wrapper'>";
				$html[] = "<span class='btn ' data-bs-toggle='modal' data-bs-target='#modalFilterForm'><i class='ti ti-filter me-1'></i> <span class='d-none d-sm-block'>Filter</span></span>";
			$html[] = "</div>";
		}

		if(isset($data['toolbar']['components']) && in_array("sort", $data['toolbar']['components'])) {
			$html[] = "<div class='sort-wrapper ms-1'>";
			$html[] = "<span class='btn dropdown-toggle ' data-bs-toggle='dropdown'><i class='ti ti-arrows-up-down me-1'></i> <span class='d-none d-sm-block'>Sort by</span></span>";
				$html[] = "<div class='dropdown-menu'>";

					$request = View::$collections['urlParameters'] ?? [];

					foreach($data['sort_by'] as $field) {

						$name = ucwords(str_replace("_"," ", $field));
						$direction = "ASC";
						$sorting = $field."|".$direction;

						if(isset($_GET['sort'])) {
							$sort = explode("|", $_GET['sort']);

							if($field === $sort[0]) {
								if($sort[1] === "ASC") {
									$direction = "DESC";
								}else {$direction = "ASC";}

								$sorting = $field."|".$direction;
							}
						}

						$request["sort"] = $sorting;
						$html[] = "<a class='dropdown-item d-flex justify-content-between' href='".url($url, null, $request)."'><span>".$name."</span> <span class='text-muted fs-11'>$direction</span></a>";
					}

				$html[] = "</div>";
			$html[] = "</div>";
		}

		if(isset($data['toolbar']['components']) && in_array("limit", $data['toolbar']['components'])) {
			$html[] = "<div class='rows-wrapper ms-1'>";
				$html[] = "<span class='btn dropdown-toggle ' data-bs-toggle='dropdown'><i class='ti ti-table-down me-1'></i> <span class='d-none d-sm-block'>Rows</span></span>";
				$html[] = "<div class='dropdown-menu'>";
					foreach($data['rows'] as $rows) {                        
						View::$collections['urlParameters']["rows"] = $rows;
						$html[] = "<a class='dropdown-item d-flex justify-content-between' href='".url($url, null, View::$collections['urlParameters'])."'><span>Show $rows rows</span></a>";
					}
				$html[] = "</div>";
			$html[] = "</div>";
		}

		$html[] = "<a href='".url($url)."' class='btn ms-1'><i class='ti ti-eraser me-1'></i> <span class='d-none d-sm-block'>Clear filter</span></a>";
	$html[] = "</div>";
$html[] = "</div>";