<?php

$html[] = "<div class='page-header d-print-none'>";
    $html[] = "<div class='container-xl'>";
        $html[] = "<div class='row g-2 align-items-center text-primary'>";
            
            $html[] = "<div class='col'>";
                $html[] = "<h2 class='page-title'>".$data['title']."</h2>";
				$html[] = "<div class='page-pretitle'>".($data['description'] ?? "")."</div>";
            $html[] = "</div>";

			if(isset($data['btn'])) {
				$html[] = "<div class='col-auto ms-auto d-print-none'>";
					$html[] = "<div class='btn-list'>";
						foreach($data['btn'] as $btn) {
							$html[] = $btn;
						}
					$html[] = "</div>";
				$html[] = "</div>";
			}

        $html[] = "</div>";

        $html[] = "<div class='response'></div>";
        
    $html[] = "</div>";
$html[] = "</div>";