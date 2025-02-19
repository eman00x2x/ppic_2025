<?php

use EO\View;

View::setMasterTemplate(path: "/authenticated/template.php");

/** Document Header Configuration */
View::setDocumentHeader(
	data: [
		"title" => "Lead",
		"description" => "",
		"scripts" => [
			CDN . "/js/main/app/leads.js"
		]
	]
);

/** Document Top Configuration */
View::define(
	name: "document_top",
	path: "/authenticated/includes/document_top.php", 
	data: [
		"title" => "Lead",
		"description" => $data['name'] . " " . niceTrim(["string" => $data['description'], "max_length" => 40]),
		"btn" => [
			"<a href='".url("LeadsController@add")."' class='btn btn-primary'><i class='ti ti-plus me-1'></i> <i class='ti ti-user fs-20 d-sm-block d-md-none'></i><span class='d-none d-md-block'>Create Leads</span></a>",
			"<a href='".url("LeadsController@index")."' class='btn btn-primary'><i class='ti ti-list me-1'></i> <i class='ti ti-user fs-20 d-sm-block d-md-none'></i><span class='d-none d-md-block'>Leads</span></a>",
			"<a href='".url("LeadsController@edit", ["id" => $data["lead_id"]])."' class='btn btn-dark'><i class='ti ti-user-edit me-1'></i> <i class='ti ti-user fs-20 d-sm-block d-md-none'></i><span class='d-none d-md-block'>Update Details</span></a>"
		]
	]
);

$html[] = View::include("document_top");

$html[] = "<div class='page-body'>";
    $html[] = "<div class='container mb-5'>";

        $html[] = "<div class='card'>";
			$html[] = "<div class='card-body border-bottom'>";

				$html[] = "<div class='d-flex flex-wrap flex-md-nowrap gap-3'>";
					$html[] = "<div class=''>";

						$html[] = "<div class='text-center'>";
							$html[] = "<span class='avatar avatar-xxxl' style='background-image: url(".CDN."/images/blank-profile.png);'></span>";
						$html[] = "</div>";

					$html[] = "</div>";

					$html[] = "<div class='flex-fill'>";
						
						$html[] = "<div class='d-flex align-items-top justify-content-between'>";
							$html[] = "<div class='ms-2 mb-3'>";
								$html[] = "<label class='text-muted fs-12'>Name</label>";
								$html[] = "<p class='fs-18 mb-0'>".$data['name']."</p>";
							$html[] = "</div>";

							$html[] = "<div class=''>";
								$html[] = "<div class='btn-list'>";
									if($data['contact_number'] != "") {
										$html[] = "<a href='tel:+63".$data['contact_number']."' class='btn btn-success'><i class='ti ti-phone me-1'></i> <span class='d-none d-md-block'>Call Phone</span></a>";
									}
									if($data['email'] != "") {
										$html[] = "<a href='".$data['email']."' class='btn btn-primary'><i class='ti ti-mail me-1'></i> <span class='d-none d-md-block'>Compose Mail</span></a>";
									}
								$html[] = "</div>";
							$html[] = "</div>";
						$html[] = "</div>";

						$html[] = "<div class='row'>";
							if($data['contact_number'] != "") {
								$html[] = "<div class='col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12'>";
									$html[] = "<div class='ms-2'>";
										$html[] = "<label class='text-muted fs-12'>Contact Number</label>";
										$html[] = "<p class='fs-18 mb-0'><i class='ti ti-phone me-1'></i>".$data['contact_number']."</p>";
									$html[] = "</div>";
								$html[] = "</div>";
							}
							
							if($data['email'] != "") {
								$html[] = "<div class='col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12'>";
									$html[] = "<div class='ms-2'>";
										$html[] = "<label class='text-muted fs-12'>Email Address</label>";
										$html[] = "<p class='fs-18 mb-0'><i class='ti ti-mail me-1'></i>".$data['email']."</p>";
									$html[] = "</div>";
								$html[] = "</div>";
							}

							$html[] = "<div class='col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12'>";
								$html[] = "<div class='ms-2'>";
									$html[] = "<label class='text-muted fs-12'> Source</label>";
									$html[] = "<p class='fs-18 mb-0'><i class='ti ti-brand-stackshare me-1'></i>".$data['source']."</p>";
								$html[] = "</div>";
							$html[] = "</div>";
						$html[] = "</div>";

						
						$html[] = "<div class='mt-3 ms-2'>";
							$html[] = "<label class='text-muted fs-12'> Description</label>";
							$html[] = "<p class='mb-0'>".$data['description']."</p>";
						$html[] = "</div>";
						
					$html[] = "</div>";
				$html[] = "</div>";

			$html[] = "</div>";
        $html[] = "</div>";

		$html[] = "<div class='d-flex flex-wrap flex-md-nowrap gap-3'>";
			$html[] = "<div class=''>";

				$html[] = "<div class='card mt-3'>";
					$html[] = "<div class='card-body border-bottom'>";
												
						$html[] = "<div class=''>";
							$html[] = "<h3 class=''><i class='ti ti-notebook me-1'></i> Notes / Inquiring / Message</h3>";
							$html[] = "<blockquote class='blockquote'>";
								$html[] = "<p>".$data['message']."</p>";
							$html[] = "</blockquote>";
						$html[] = "</div>";

					$html[] = "</div>";
				$html[] = "</div>";

				if(empty($data['reference'])) {
					$html[] = "<div class='card mt-3'>";
						$html[] = "<div class='card-body border-bottom'>";
													
							$html[] = "<div class=''>";
								$html[] = "<h3 class=''><i class='ti ti-notebook me-1'></i> Reference</h3>";
								$html[] = "<div class='d-flex gap-2'>";
									$html[] = "<span class='avatar avatar-md' style='background-image:url(".$data['reference']['thumb_img'].")'></span>";
									$html[] = "<div class=''>";
										$html[] = "<a href='".$data['reference']['url']."'>".$data['reference']['title']."</a>";
										$html[] = "<span class='d-block'>".$data['reference']['price']."</span>";
									$html[] = "</div>";
								$html[] = "</div>";
							$html[] = "</div>";

						$html[] = "</div>";
					$html[] = "</div>";
				}

			$html[] = "</div>";

			$html[] = "<div class='card mt-3'>";
				$html[] = "<div class='card-body border-bottom'>";
					$html[] = "<p>".$data['message']."</p>";
				$html[] = "</div>";
			$html[] = "</div>";

		$html[] = "</div>";
    
    $html[] = "</div>";
$html[] = "</div>";