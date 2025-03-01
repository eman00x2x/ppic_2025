<?php

use EO\View;

View::setMasterTemplate(path: "/authenticated/template.php");

/** Document Header Configuration */
View::setDocumentHeader(
	data: [
		'title' => 'Error Log File',
		'description' => 'View error log file',
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
		"title" => "<i class='ti ti-file me-2  fs-32'></i> Error Log File",
		"description" => "View error log file"
	]
);

$html[] = View::include("document_top");

$html[] = "<div class='page-body'>";
    $html[] = "<div class='container-xl'>";

		$html[] = "<div class='card'>";
			$html[] = "<div class='card-body'>";

				$html[] = "<div class='' style='height: 60vh; overflow: auto;'>";
					$html[] = $data['content'];
				$html[] = "</div>";

			$html[] = "</div>";
		$html[] = "</div>";

		$html[] = "<div class='text-end mt-2'>";
			$html[] = "<div class=''>";
				$html[] = "<span class='btn btn-danger btn-remove-error-log-file' data-url='".url('administration.removeErrorLogFile')."'>Delete Error Log File</span>";
			$html[] = "</div>";
		$html[] = "</div>";

	$html[] = "</div>";
$html[] = "</div>";