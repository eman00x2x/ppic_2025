<?php

use EO\View;

/** Document Header Configuration */
View::setDocumentHeader(
	data: [
		"title" => "Two Factor Auth Email",
		"description" => "Two Factor Auth Email"
	]
);

View::setMasterTemplate(path: "/mail/template.php");

/** BODY */
$html[] = "<table valign='top' role='presentation' border='0' cellpadding='0' cellspacing='0' style='outline: 0; width: 100%; min-width: 100%; height: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; line-height: 24px; font-weight: normal; font-size: 16px; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; color: #000000; margin: 0; padding: 0; border-width: 0;' bgcolor='#ffffff'>";
$html[] = "<tbody>";
	$html[] = "<tr>";
		$html[] = "<td valign='top' style='border-width: 2px; border-color: #ECECEC; border-style: solid;  line-height: 24px; font-size: 16px; margin: 0;' align='left'>";
			$html[] = "<div style='width: 90%; background-color: #FFF; text-wrap: wrap; margin: 0 auto; padding: 20px;'>";
				$html[] = "<h1 style='font-weight: 500; vertical-align: baseline; font-size: 16px; margin: 0; padding: 0;' align='left'>Your Authentication Code from ".CONFIG['site_name']."</h1>";
				$html[] = "<p style='line-height: 24px; font-size: 26px; width: 100%; margin: 0;' align='left'>Your Authorization Code is: ". $data['authorization_code'] ."</p>";
			$html[] = "</div>";
		$html[] = "</td>";
	$html[] = "</tr>";
$html[] = "</tbody>";
$html[] = "</table>";