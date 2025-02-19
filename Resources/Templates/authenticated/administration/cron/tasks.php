<?php

use EO\View;

View::setMasterTemplate(path: "/authenticated/template.php");

/** Document Header Configuration */
View::setDocumentHeader(
	data: [
		'title' => 'Database Administration',
		'description' => 'Cron Tasks',
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
		"title" => "<i class='ti ti-calendar-due me-2  fs-32'></i> Cron Tasks",
		"description" => "Run Cron Tasks"
	]
);

$html[] = View::include("document_top");

$html[] = "<div class='page-body'>";
    $html[] = "<div class='container-xl'>";

		$html[] = "<div class='card'>";
			$html[] = "<div class='card-body'>";
			$html[] = "<div class='table-responsive'>";
				$html[] = "<table class='table table-md table-vcenter card-table caption-top'>";
					$html[] = "<thead>";
						$html[] = "<th class='w-1 text-center'>#</th>";
						$html[] = "<th>Task</th>";
						$html[] = "<th>Description</th>";
						$html[] = "<th>Schedule</th>";
						$html[] = "<th></th>";
					$html[] = "</thead>";
					$html[] = "<tbody>";

						for($i = 0; $i < count($data['tasks']); $i++) {
							$taskNameSpace = explode("\\", $data['tasks'][$i]['task']);
							$taskName = end($taskNameSpace);
							$description = $data['tasks'][$i]['description'];
							$time = $data['tasks'][$i]['time'];

							$html[] = "<tr>";
								$html[] = "<td class='text-center'>" . ($i+1) . "</td>";
								$html[] = "<td class='text-left fw-bold'>" . $taskName . "</td>";
								$html[] = "<td class='text-left'>" . $description . "</td>";
								$html[] = "<td class='text-left'>" . $time . "</td>";
								$html[] = "<td class='text-left'><span class='btn btn-outline-warning btn-run-task' data-url='".url('administration.cronTaskRun', ['task' => $taskName])."'><i class='ti ti-sun me-1'></i> Run Task</span></td>";
							$html[] = "</tr>";
						}
					$html[] = "</tbody>";
				$html[] = "</table>";
			$html[] = "</div>";
			$html[] = "</div>";
		$html[] = "</div>";

	$html[] = "</div>";
$html[] = "</div>";