<?php

use EO\View;

/** Document Header Configuration */
View::setDocumentHeader(
	data: [
		"title" => "Inquiry Email",
		"description" => "Inquiry Email"
	]
);

View::setMasterTemplate(path: "/mail/template.php");

/** BODY */
$html[] = "<table valign='top' role='presentation' border='0' cellpadding='0' cellspacing='0' style='outline: 0; width: 100%; min-width: 100%; height: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; line-height: 24px; font-weight: normal; font-size: 16px; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; color: #000000; margin: 0; padding: 0; border-width: 0;' bgcolor='#ffffff'>";
$html[] = "<tbody>";
	$html[] = "<tr>";
		$html[] = "<td valign='top' style='border-width: 2px; border-color: #ECECEC; border-style: solid;  line-height: 24px; font-size: 16px; margin: 0;' align='left'>";
			$html[] = "<div style='width: 90%; background-color: #FFF; text-wrap: wrap; margin: 0 auto; padding: 20px;'>";
				$html[] = "<h1 style='font-weight: 500; vertical-align: baseline; font-size: 26px; line-height: 43.2px; margin: 0; padding: 0;' align='left'>New Inquiry!</h1>";
				$html[] = "<table class='table' border='0' cellpadding='0' cellspacing='0' style='width: 100%; max-width: 100%;'>";
				
				if(isset($data['reference']['thumb_img'])) {
					$html[] = "<tr>";
						$html[] = "<td colspan='2' style='line-height: 24px; font-size: 16px; border-top-width: 1px; border-top-color: #e2e8f0; border-top-style: solid; margin: 0; padding: 12px;' align='left' valign='top'>";
							$html[] = "<table class='table table-lg' border='0' cellpadding='0' cellspacing='0' style='width: 100%; max-width: 100%;'>";
							$html[] = "<tr>";
								$html[] = "<td style='width: 120px; line-height: 24px; font-size: 16px; margin: 0;' align='left'><img src='".$data['reference']['thumb_img']."' style='padding:20px; height: auto; line-height: 100%; outline: none; text-decoration: none; display: block; border-style: none; border-width: 0; width: 120px;'></td>";
								$html[] = "<td style='line-height: 24px; font-size: 16px; margin: 0;' align='left'>";
									$html[] = "<a href='".$data['reference']['url']."' style='color: #0d6efd;'>Solar-equipped House and Lot for Sale in SJDM City near Altaraza MRT7</a>";
									$html[] = "<span class='d-block' style='display: block;'>&#8369;".$data['reference']['price']."</span>";
								$html[] = "</td>";
							$html[] = "</tr>";
							$html[] = "</table>";
						$html[] = "</td>";
					$html[] = "</tr>";
				}

				$html[] = "<tr>";
					$html[] = "<td style='line-height: 24px; font-size: 16px; margin: 0; width: 130px;' align='left'>Date</td>";
					$html[] = "<td style='line-height: 24px; font-size: 16px; margin: 0;' align='left'>".$data['created_at']."</td>";
				$html[] = "</tr>";
				$html[] = "<tr>";
					$html[] = "<td style='line-height: 24px; font-size: 16px; margin: 0;' align='left'>Name</td>";
					$html[] = "<td style='line-height: 24px; font-size: 16px; margin: 0;' align='left'>".$data['name']."</td>";
				$html[] = "</tr>";
				$html[] = "<tr>";
					$html[] = "<td style='line-height: 24px; font-size: 16px; margin: 0;' align='left'>Email Address</td>";
					$html[] = "<td style='line-height: 24px; font-size: 16px; margin: 0;' align='left'>".$data['email']."</td>";
				$html[] = "</tr>";
				$html[] = "<tr>";
					$html[] = "<td style='line-height: 24px; font-size: 16px; margin: 0;' align='left'>Contact Number</td>";
					$html[] = "<td style='line-height: 24px; font-size: 16px; margin: 0;' align='left'>".$data['contact_number']."</td>";
				$html[] = "</tr>";
				$html[] = "<tr>";
					$html[] = "<td style='line-height: 24px; font-size: 16px; margin: 0;' align='left'>Message</td>";
					$html[] = "<td style='line-height: 24px; font-size: 16px; margin: 0;' align='left'><p class='max-width: 600px;'>".$data['message']."</p></td>";
				$html[] = "</tr>";
				$html[] = "</table>";
			$html[] = "</div>";
		$html[] = "</td>";
	$html[] = "</tr>";
$html[] = "</tbody>";
$html[] = "</table>";