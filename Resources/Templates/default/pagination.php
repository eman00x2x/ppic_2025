<?php

use EO\View;

$currentPage = View::$collections['currentPage'];
$totalEntries = View::$collections['totalRows'];
$startingNumber = View::$collections['itemStartingNumber'];
$rowsPerPage = View::$collections['perPage'];
$totalPages = View::$collections['totalPages'];

$totalNumbers = (($startingNumber - 1) + $rowsPerPage);
if($totalNumbers > $totalEntries) { 
	$totalNumbers = $totalEntries;
}

if($totalEntries > 20) {
	$showing = $startingNumber . " to " . $totalNumbers;
}else {
	$showing = $startingNumber;
}

$range = 2;

$html[] = "<div class='d-flex flex-column flex-md-row justify-content-center align-items-center justify-content-md-between mt-3 '>";
	if($totalEntries > 0) {
		$html[] = "<p class='text-muted'>Showing {$showing} of {$totalEntries}</p>";
	}

	$html[] = "<div class='page-buttons btn-group'>";

		$html[] = "<div class='pagination-container mt-2'>";
			$html[] = "<nav aria-label='Page navigation'>";
				$html[] = "<ul class='pagination justify-content-center d-print-none'>";

					if(isset($data['links'])) {
						
						if ($currentPage < 1) $currentPage = 1;
						if ($currentPage > $totalPages) $currentPage = $totalPages;

						if ($currentPage > 1) {
							$html[] = "<li class='page-item'>";
								$html[] = "<a class='page-link' href='".$data['links'][0]['link']."' aria-label='Previous'>".$data['links'][0]['value']."</a>";
							$html[] = "</li>";
						}

						if ($currentPage > $range + 1) {
							$html[] = "<li class='page-item'>";
								$html[] = "<a class='page-link' href='".$data['links'][1]['link']."'>".$data['links'][1]['value']."</a>";
							$html[] = "</li>";

							if ($currentPage > $range + 2) {
								$html[] = "<li class='page-item disabled'><a class='page-link'>...</a></li>";
							}
						}

						for ($i = max(1, $currentPage - $range); $i <= min($totalPages, $currentPage + $range); $i++) {
							if ($i == $currentPage) {
								$html[] = "<li class='page-item active'>";
									$html[] = "<a class='page-link'>".$data['links'][$i]['value']."</a>";
								$html[] = "</li>";
							} else {
								$html[] = "<li class='page-item'>";
									$html[] = "<a class='page-link' href='".$data['links'][$i]['link']."'>".$data['links'][$i]['value']."</a>";
								$html[] = "</li>";
							}
						}

						if ($currentPage < $totalPages - $range) {
							if ($currentPage < $totalPages - $range - 1) {
								$html[] = "<li class='page-item disabled'><a class='page-link'>...</a></li>";
							}
							$html[] = "<li class='page-item'>";
								$html[] = "<a class='page-link' href='".$data['links'][$totalPages]['link']."'>$totalPages</a>";
							$html[] = "</li>";
						}

						if ($currentPage < $totalPages) {
							$html[] = "<li class='page-item'>";
								$html[] = "<a class='page-link' href='".$data['links'][($totalPages + 1)]['link']."' aria-label='Next'>".$data['links'][($totalPages + 1)]['value']."</a>";
							$html[] = "</li>";
						}

					}

				$html[] = "</ul>";
			$html[] = "</nav>";
		$html[] = "</div>";
	
	$html[] = "</div>";
$html[] = "</div>";