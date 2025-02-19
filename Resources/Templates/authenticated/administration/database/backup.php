<?php

use EO\View;

View::setMasterTemplate(path: "/authenticated/template.php");

/** Document Header Configuration */
View::setDocumentHeader(
	data: [
		'title' => 'Database Backup',
		'description' => 'List of Database Backup',
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
		"title" => "<i class='ti ti-file-database me-2  fs-32'></i> Database Backup",
		"description" => "List of Database Backup",
		"btn" => [
			"<span class='btn btn-dark btn-backup cursor-pointer' data-url='".url("administration.backupDatabase")."'><i class='ti ti-database-export me-2'></i> Backup Database</span>"
		]
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
					$html[] = "<th>Filename</th>";
					$html[] = "<th></th>";
					$html[] = "<th></th>";
					$html[] = "<th></th>";
				$html[] = "</thead>";
				$html[] = "<tbody>";
					if($data['backup_files']) {
						$i=0;
						foreach($data['backup_files'] as $key => $file) { $i++;
							$html[] = "<tr>";
								$html[] = "<td class='text-center text-muted'>$i</td>";
								$html[] = "<td class='w-100'>$file</td>";
								$html[] = "<td class='text-center'>";
									$html[] = "<a href='".url("administration.downloadBackup", null, ["file" => $file])."' class='btn btn-sm btn-outline-primary'><i class='ti ti-download me-1'></i> Download</a>";
								$html[] = "</td>";
								$html[] = "<td class='text-center'>";
									$html[] = "<span data-file='$file' data-url='".url("administration.restoreBackup", null, ["file" => $file])."' class='btn btn-sm btn-outline-warning btn-restore-backup'><i class='ti ti-database-import me-1'></i> Restore</span>";
								$html[] = "</td>";
								$html[] = "<td class='text-center'>";
									$html[] = "<span data-file='$file' data-url='".url("administration.deleteBackup", null, ["file" => $file])."' class='btn btn-sm btn-outline-danger btn-delete-backup'><i class='ti ti-trash me-1'></i> Delete</span>";
								$html[] = "</td>";
							$html[] = "</tr>";
						}
					}
				$html[] = "</tbody>";
				$html[] = "</table>";

			$html[] = "</div>";
			$html[] = "</div>";
		$html[] = "</div>";

	$html[] = "</div>";
$html[] = "</div>";
