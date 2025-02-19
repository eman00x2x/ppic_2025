<?php

use EO\View;

View::setMasterTemplate(path: "/authenticated/template.php");

/** Document Header Configuration */
View::setDocumentHeader(
	data: [
		"title" => "Create Article",
		"description" => "Create a new article",
		"scripts" => [
			CDN . "/js/vendor/validatejs-0.13.1/validate.min.js",
			CDN . "/js/vendor/tinymce/tinymce.min.js",
			CDN . "/js/main/app/articles.js"
		]
	]
);

/** Document Top Configuration */
View::define(
	name: "document_top",
	path: "/authenticated/includes/document_top.php", 
	data: [
		"title" => "Create Article",
		"btn" => [
			"<a href='".url("ArticlesController@index")."' class='btn btn-primary'><i class='ti ti-list me-1'></i> <i class='ti ti-book fs-20 d-sm-block d-md-none'></i><span class='d-none d-md-block'>Articles</span></a>"
		]
	]
);

$html[] = View::include("document_top");

$html[] = "<form id='uploadForm' class='d-none' action='".url("ArticlesController@upload")."' method='POST' enctype='multipart/form-data' data-uploader='article'>";
	$html[] = "<center>";
		$html[] = "<input type='file' name='browseFile' id='browseFile' />";
	$html[] = "</center>";
$html[] = "</form>";

$html[] = "<div class='page-body'>";
    $html[] = "<div class='container-xl mb-5'>";

		$html[] = "<form id='form' action='".url("articles.save.new")."' method='post'>";

			$html[] = "<div class='row'>";
				$html[] = "<div class='col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12 order-md-1 order-2'>";

					$html[] = "<div class='card'>";
						$html[] = "<div class='card-body border-bottom'>";

							$html[] = "<input type='hidden' name='banner' id='photo' value='".$data['banner']."' />";
							
							$html[] = "<div class='form-floating mb-3'>";
								$html[] = "<input type='text' name='title' id='title' value='' class='form-control' />";
								$html[] = "<label for='title'>Title</label>";
							$html[] = "</div>";

							$html[] = "<div class='mb-3'>";
								$html[] = "<textarea name='content' id='textContainer' class='form-control'></textarea>";
							$html[] = "</div>";

						$html[] = "</div>";
					$html[] = "</div>";

				$html[] = "</div>";
				$html[] = "<div class='col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 order-md-2 order-1'>";

					$html[] = "<div class='card'>";
						$html[] = "<div class='card-body border-bottom'>";
							
							/* $html[] = "<div class='mb-3 text-center'>";
								$html[] = "<span class='avatar avatar-xxxl banner-preview cursor-pointer' style='background-image:url(".$data['banner'].")'></span>";
								$html[] = "<span class='banner-container d-block mt-2' data-url='".url("ArticlesController@upload")."'></span>";
							$html[] = "</div>"; */

							$html[] = "<div class='form-floating mb-3'>";
								$html[] = "<select name='category' id='category' class='form-select'>";
									foreach($data['categories'] as $category) {
										$html[] = "<option value='$category'>$category</option>";
									}
								$html[] = "</select>";
								$html[] = "<label for='title'>Cateory</label>";
							$html[] = "</div>";

							$html[] = "<div class='form-floating mb-3'>";
								$html[] = "<select name='is_published' id='is_published' class='form-select'>";
									foreach(["Draft", "Publish"] as $status => $label) {
										$html[] = "<option value='$status'>$label</option>";
									}
								$html[] = "</select>";
								$html[] = "<label for='title'>Status</label>";
							$html[] = "</div>";

							$html[] = "<div class='form-floating mb-3'>";
								$html[] = "<input type='text' name='created_by' id='created_by' value='".request()->authenticated['account']['full_name']."' class='form-control' />";
								$html[] = "<label for='title'>Author</label>";
							$html[] = "</div>";

						$html[] = "</div>";
					$html[] = "</div>";

				$html[] = "</div>";
			$html[] = "</div>";

		$html[] = "</form>";
    
    $html[] = "</div>";
$html[] = "</div>";

$html[] = "<div class='btn-save-container fixed-bottom bg-white py-3 border-top'>";
	$html[] = "<div class='container-xl'>";
		$html[] = "<div class='text-end'>";
			$html[] = "<span class='btn btn-outline-primary btn-save'><i class='ti ti-device-floppy me-1'></i> Save Article</span>";
		$html[] = "</div>";
	$html[] = "</div>";
$html[] = "</div>";