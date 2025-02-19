<?php

use EO\View;

View::setMasterTemplate(path: "/authenticated/template.php");

/** Document Header Configuration */
View::setDocumentHeader(
	data: [
		'title' => 'Database Administration',
		'description' => 'Database Query',
		'scripts' => [
			CDN . "/js/main/app/administration.js"
		]
	]
);

/** Document Top Configuration */
View::define(
	name: "document_top",
	path: "/authenticated/includes/document_top.php", 
	data: [
		"title" => "<i class='ti ti-database me-2  fs-32'></i> Database Administration",
		"description" => "Database Query"
	]
);

$html[] = View::include("document_top");

$html[] = "<div class='page-body'>";
    $html[] = "<div class='container-xl'>";

		$html[] = "<div class='box-container  mt-2 mb-4'>";

			$html[] = "<div class='mb-3'>";
				$html[] = "<h5 class='mb-2'>TABLE OPTIONS</h5>";
				$html[] = "<div class='d-flex gap-2'>";
					$html[] = "<div class='dropdown'>";
						$html[] = "<button class='btn btn-secondary btn-sm dropdown-toggle mr-1 mb-1' type='button' id='dropdownMenuButton' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'> SELECT TABLE </button>";
						$html[] = "<div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>";
							foreach($data['tables'] as $table_name) {
								$html[] = "<a class='dropdown-item show_table cursor-pointer' data-query='SELECT * FROM $table_name LIMIT 0,20'>".$table_name."</a>";
							}
						$html[] = "</div>";
					$html[] = "</div>";

					$html[] = "<div class='dropdown'>";
						$html[] = "<button class='btn btn-secondary btn-sm dropdown-toggle  mr-1 mb-1' type='button' id='dropdownMenuButton' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'> DESCRIBE TABLE </button>";
						$html[] = "<div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>";
							foreach($data['tables'] as $table_name) {
								$html[] = "<a class='dropdown-item show_table cursor-pointer' data-query='DESCRIBE $table_name'>".$table_name."</a>";
							}
						$html[] = "</div>";
					$html[] = "</div>";
				$html[] = "</div>";
			$html[] = "</div>";

			$html[] = "<div class=''>";
				$html[] = "<div class='d-flex align-content-center justify-content-between'>";
					$html[] = "<div><h3 class='mb-0'>Run MySQL query/queries on server</h3></div>";
					$html[] = "<div class='mb-3'>";
						$html[] = "<span class='btn btn-md btn-primary btn-submit-admin-form cursor-pointer'>Run Query</span>";
					$html[] = "</div>";
				$html[] = "</div>";
				$html[] = "<form id='form' method='POST' action='".url("administration.queryResult")."'>";
					$html[] = "<div class='form-group'>";
						$html[] = "<textarea class='form-control' name='query' id='query' rows='5'></textarea>";
					$html[] = "</div>";
				$html[] = "</form>";
			$html[] = "</div>";

		$html[] = "</div>";

		$html[] = "<div class='card'>";
			$html[] = "<div class='card-body'>";
				$html[] = "<h3 class='px-0'>Result</h3>";
				$html[] = "<div class='query_result'></div>";
			$html[] = "</div>";
		$html[] = "</div>";

	$html[] = "</div>";
$html[] = "</div>";
