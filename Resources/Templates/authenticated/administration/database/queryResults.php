<?php

use EO\View;

$html[] = "<div class='table-responsive'>";
	$html[] = "<table class='table table-bordered table-striped table-sm table-hover'>";
	$html[] = "<thead>";
		$html[] = "<tr>";
			foreach($data['fields'] as $field) {
				$html[] = "<th class='text-center'>$field</th>";
			}
		$html[] = "</tr>";
	$html[] = "</thead>";
	$html[] = "<tbody>";
		foreach($data['results'] as $key => $result) {
			$html[] = "<tr>";
				foreach($result as $field => $value) {
					$html[] = "<td class='text-center'>$value</td>";
				}
			$html[] = "</tr>";
		}
	$html[] = "</tbody>";
	$html[] = "</table>";
$html[] = "</div>";