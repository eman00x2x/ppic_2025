<?php

use EO\View;

View::setMasterTemplate(path: "/authenticated/template.php");

/** Document Header Configuration */
View::setDocumentHeader(
	data: [
		"title" => "Traffics",
		"description" => "",
		"scripts" => [
			CDN . "/js/main/app/traffics.js"
		]
	]
);

/** Document Top Configuration */
View::define(
	name: "document_top",
	path: "/authenticated/includes/document_top.php", 
	data: [
		"title" => "Traffics",
		"description" => $data['session_id'] . " " . nice_trim(["string" => $data['description'], "max_length" => 40]),
		"btn" => [
			"<a href='".url("TrafficsController@index")."' class='btn btn-primary'><i class='ti ti-list me-1'></i> <i class='ti ti-user fs-20 d-sm-block d-md-none'></i><span class='d-none d-md-block'>Traffics</span></a>"
		]
	]
);

$html[] = View::include("document_top");

$html[] = "<div class='page-body'>";
    $html[] = "<div class='container mb-5'>";

        
    $html[] = "</div>";
$html[] = "</div>";