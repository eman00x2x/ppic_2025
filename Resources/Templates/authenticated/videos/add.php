<?php

use EO\View;

View::setMasterTemplate(path: "/authenticated/template.php");

/** Document Header Configuration */
View::setDocumentHeader(
	data: [
		"title" => "Add Video",
		"description" => "",
		"scripts" => [
			CDN . "/js/vendor/validatejs-0.13.1/validate.min.js",
			CDN . "/js/main/app/videos.js"
		]
	]
);

/** Document Top Configuration */
View::define(
	name: "document_top",
	path: "/authenticated/includes/document_top.php", 
	data: [
		"title" => "Videos",
		"description" => "",
		"btn" => [
			"<a href='".url("VideosController@index")."' class='btn btn-primary'><i class='ti ti-list me-1'></i> <i class='ti ti-user fs-20 d-sm-block d-md-none'></i><span class='d-none d-md-block'>Videos</span></a>"
		]
	]
);

$html[] = View::include("document_top");

$html[] = "<div class='page-body'>";
    $html[] = "<div class='container mb-5'>";

        $html[] = "<div class='card'>";
			$html[] = "<div class='card-body border-bottom'>";

				$html[] = "<div class='p-4 bg-muted-lt border-top border-bottom rounded' style='margin: -20px -20px 20px -20px;'>";		
					$html[] = "<div class='' id='videoInput'></div>";
					$html[] = "<p class='form-hint mt-3'>Sample Youtube Url: https://www.youtube.com/watch?v=uiZVssPtPr4</p>";
				$html[] = "</div>";

				$html[] = "<form id='form' action='".url("videos.save.new")."' method='post'>";
					$html[] = "<div class='video-list-container d-flex flex-wrap justify-content-center gap-3'></div>";
				$html[] = "</form>";

			$html[] = "</div>";
        $html[] = "</div>";
    
    $html[] = "</div>";
$html[] = "</div>";

$html[] = "<div class='btn-save-container fixed-bottom bg-white py-3 border-top'>";
	$html[] = "<div class='container-xl'>";
		$html[] = "<div class='text-end'>";
			$html[] = "<span class='btn btn-outline-primary btn-save'><i class='ti ti-device-floppy me-1'></i> Save Videos</span>";
		$html[] = "</div>";
	$html[] = "</div>";
$html[] = "</div>";