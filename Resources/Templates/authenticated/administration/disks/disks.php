<?php

use EO\View;

View::setMasterTemplate(path: "/authenticated/template.php");

/** Document Header Configuration */
View::setDocumentHeader(
	data: [
		'title' => 'Disk Monitoring',
		'description' => 'Disks Spaces',
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
		"title" => "<i class='ti ti-calendar-due me-2  fs-32'></i> Disks Spaces",
		"description" => "View disks spaces"
	]
);

$html[] = View::include("document_top");

$html[] = "<div class='page-body'>";
    $html[] = "<div class='container-xl'>";

		$html[] = "<div class='d-flex gap-3 mb-3'>";

			$html[] = "<div class='card'>";
				$html[] = "<div class='card-body'>";
					$html[] = "<div class='subheader'>Total Disk Space</div>";
					$html[] = "<div class='h3'>".$data['total_disk_space']."</div>";
				$html[] = "</div>";
			$html[] = "</div>";
			
			$html[] = "<div class='card'>";
				$html[] = "<div class='card-body'>";
					$html[] = "<div class='subheader'>Disk Free Space</div>";
					$html[] = "<div class='h3'>".$data['disk_free_space']."</div>";
				$html[] = "</div>";
			$html[] = "</div>";

		$html[] = "</div>";

		$html[] = "<div class='card'>";
			$html[] = "<div class='card-body'>";
			$html[] = "<div class='table-responsive'>";
				$html[] = "<table class='table table-md table-vcenter card-table caption-top'>";
					$html[] = "<thead>";
						$html[] = "<th class='w-1 text-center'>#</th>";
						$html[] = "<th>Disk Name</th>";
						$html[] = "<th>In Use</th>";
						$html[] = "<th></th>";
					$html[] = "</thead>";
					$html[] = "<tbody>";

						$i = 0;
						foreach($data['disk_space'] as $disk => $arr) {
							$html[] = "<tr>";
								$html[] = "<td class='text-center'>" . ($i+1) . "</td>";
								$html[] = "<td class='text-left fw-bold'>" . ucwords(str_replace("_", " ", $disk)) . " Folder</td>";
								$html[] = "<td class='text-left fw-bold'>" . $arr['size'] . "</td>";
								$html[] = "<td class='text-left'>";
									if($arr['task_run_url'] != null) {
										$html[] = "<span class='btn btn-sm btn-outline-warning btn-run-task' data-url='".url($arr['task_run_url'])."'><i class='ti ti-sun me-1'></i> Optimize</span>";
									}
								$html[] = "</td>";
							$html[] = "</tr>";
							$i++;
						}
					$html[] = "</tbody>";
				$html[] = "</table>";
			$html[] = "</div>";
			$html[] = "</div>";
		$html[] = "</div>";

	$html[] = "</div>";
$html[] = "</div>";