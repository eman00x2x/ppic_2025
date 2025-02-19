<?php

use EO\View;

View::setMasterTemplate(path: "/authenticated/template.php");

/** Document Header Configuration */
View::setDocumentHeader(
	data: [
		"title" => "Edit Leads",
		"description" => "",
		"scripts" => [
			CDN . "/js/vendor/validatejs-0.13.1/validate.min.js",
			CDN . "/js/main/app/leads.js"
		]
	]
);

/** Document Top Configuration */
View::define(
	name: "document_top",
	path: "/authenticated/includes/document_top.php", 
	data: [
		"title" => "Edit Leads",
		"description" => "",
		"btn" => [
			"<a href='".url("LeadsController@index")."' class='btn btn-primary'><i class='ti ti-list me-1'></i> <i class='ti ti-user fs-20 d-sm-block d-md-none'></i><span class='d-none d-md-block'>Leads</span></a>",
			"<a href='".url("LeadsController@view", ["id" => $data['lead_id']])."' class='btn btn-dark'><i class='ti ti-eye me-1'></i> <i class='ti ti-user fs-20 d-sm-block d-md-none'></i><span class='d-none d-md-block'>View Leads</span></a>"
		]
	]
);

$html[] = View::include("document_top");

$html[] = "<div class='page-body'>";
    $html[] = "<div class='container mb-5'>";

        $html[] = "<div class='card'>";
			$html[] = "<div class='card-body border-bottom'>";

				$html[] = "<form id='form' action='".url("leads.save.update", ["id" => $data["lead_id"]])."' method='post'>";

					$html[] = "<div class='d-flex flex-wrap gap-3'>";
						$html[] = "<div class=''>";

							$html[] = "<div class='text-center mb-3'>";
								$html[] = "<span class='avatar avatar-xxxl' style='background-image: url(".CDN."/images/blank-profile.png);'></span>";
							$html[] = "</div>";

						$html[] = "</div>";

						$html[] = "<div class='flex-grow-1'>";
							
							$html[] = "<div class='form-floating mb-3 '>";
								$html[] = "<input type='text' name='name' id='name' value='".$data['name']."' class='form-control' />";
								$html[] = "<label for='title'>Name</label>";
							$html[] = "</div>";

							$html[] = "<div class='row'>";
								$html[] = "<div class='col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12'>";
									$html[] = "<div class='form-floating mb-3'>";
										$html[] = "<input type='text' name='contact_number' id='contact_number' value='".$data['contact_number']."' class='form-control' />";
										$html[] = "<label for='title'>Contact Number</label>";
									$html[] = "</div>";
								$html[] = "</div>";
								$html[] = "<div class='col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12'>";
									$html[] = "<div class='form-floating mb-3'>";
										$html[] = "<input type='email' name='email' id='email' value='".$data['email']."' class='form-control' />";
										$html[] = "<label for='title'>Email Address</label>";
									$html[] = "</div>";
								$html[] = "</div>";
								$html[] = "<div class='col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12'>";
									$html[] = "<div class='form-floating mb-3'>";
										$html[] = "<select name='source' id='source' class='form-select'>";
											foreach($data['sources'] as $source) {
												$sel = $data['source'] == $source ? "selected" : "";
												$html[] = "<option value='$source' $sel>$source</option>";
											}
										$html[] = "</select>";
										$html[] = "<label for='title'>Source</label>";
									$html[] = "</div>";
								$html[] = "</div>";
							$html[] = "</div>";

							$html[] = "<div class='form-floating mb-3'>";
								$html[] = "<textarea name='description' id='description' class='form-control' style='height:100px;'>".$data['description']."</textarea>";
								$html[] = "<label for='title'>Description of Leads</label>";
							$html[] = "</div>";

							$html[] = "<div class='form-floating mb-3'>";
								$html[] = "<textarea name='message' id='message' class='form-control' style='height:100px;' disabled>".$data['message']."</textarea>";
								$html[] = "<label for='title'>Notes / Inquiring / Message</label>";
							$html[] = "</div>";

						$html[] = "</div>";
					$html[] = "</div>";

				$html[] = "</form>";

			$html[] = "</div>";
        $html[] = "</div>";
    
    $html[] = "</div>";
$html[] = "</div>";

$html[] = "<div class='btn-save-container fixed-bottom bg-white py-3 border-top'>";
	$html[] = "<div class='container-xl'>";
		$html[] = "<div class='text-end'>";
			$html[] = "<span class='btn btn-outline-primary btn-save'><i class='ti ti-device-floppy me-1'></i> Save Lead</span>";
		$html[] = "</div>";
	$html[] = "</div>";
$html[] = "</div>";