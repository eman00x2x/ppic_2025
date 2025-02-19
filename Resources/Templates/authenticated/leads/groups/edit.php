<?php

use EO\View;

View::setMasterTemplate(path: "/authenticated/template.php");

/** Document Header Configuration */
View::setDocumentHeader(
	data: [
		"title" => "Update Lead Group",
		"description" => "",
		"scripts" => [
			CDN . "/js/vendor/validatejs-0.13.1/validate.min.js",
			CDN . "/js/main/app/leadGroups.js"
		]
	]
);

/** Document Top Configuration */
View::define(
	name: "document_top",
	path: "/authenticated/includes/document_top.php", 
	data: [
		"title" => "Update Lead Group",
		"description" => "",
		"btn" => [
			"<a href='".url("LeadsController@index")."' class='btn btn-primary'><i class='ti ti-list me-1'></i> <i class='ti ti-user fs-20 d-sm-block d-md-none'></i><span class='d-none d-md-block'>Leads</span></a>"
		]
	]
);

$html[] = "<div class='container-tight'>";

	$html[] = View::include("document_top");

	$html[] = "<div class='page-body'>";
		$html[] = "<div class='mb-5'>";

			$html[] = "<div class='card'>";
				$html[] = "<div class='card-body border-bottom'>";

					$html[] = "<form id='form' action='".url("leads.groups.save.update", ["id" => $data["lead_group_id"]])."' method='post'>";

						$html[] = "<div class='form-floating mb-3 '>";
							$html[] = "<input type='text' name='name' id='name' value='".$data["name"]."' class='form-control' />";
							$html[] = "<label for='title'>Name</label>";
						$html[] = "</div>";

					$html[] = "</form>";

					$html[] = "<div class='text-end'>";
						$html[] = "<span class='btn btn-outline-primary btn-save'><i class='ti ti-device-floppy me-1'></i> Save Group</span>";
					$html[] = "</div>";

				$html[] = "</div>";
			$html[] = "</div>";
		
		$html[] = "</div>";
	$html[] = "</div>";
$html[] = "</div>";