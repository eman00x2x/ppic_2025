<?php

use EO\View;

/** Document Header Configuration */
View::setDocumentHeader(
	data: [
		"title" => "Password Reset Email",
		"description" => "Password Reset Email"
	]
);

View::setMasterTemplate(path: "/mail/template.php");

/** BODY */
$html[] = "<table valign='top' role='presentation' border='0' cellpadding='0' cellspacing='0' style='outline: 0; width: 100%; min-width: 100%; height: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; line-height: 24px; font-weight: normal; font-size: 16px; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; color: #000000; margin: 0; padding: 0; border-width: 0;' bgcolor='#ffffff'>";
$html[] = "<tbody>";
	$html[] = "<tr>";
		$html[] = "<td valign='top' style='border-width: 2px; border-color: #ECECEC; border-style: solid;  line-height: 24px; font-size: 16px; margin: 0;' align='left'>";
			$html[] = "<div style='width: 90%; background-color: #FFF; text-wrap: wrap; margin: 0 auto; padding: 20px;'>";
				$html[] = "<h1 style='font-weight: 500; vertical-align: baseline; font-size: 26px; line-height: 43.2px; margin: 0; padding: 0;' align='left'>Password Reset Request</h1>";
				$html[] = "<br/><p style='line-height: 24px; font-size: 16px; width: 100%; margin: 0;' align='left'>Hi ".$data['username']."!</p>";
				$html[] = "<p style='line-height: 24px; font-size: 16px; width: 100%; margin: 0;' align='left'>You have requested a Password reset link through our system. Please click the link below to reset your password. This link will be available for the next 24 hours</p>";
				$html[] = "<br/><a href='". $data['url'] ."' style='color: #0d6efd;'>Reset your password</a><br/><br/>";
				$html[] = "<p style='line-height: 24px; font-size: 16px; width: 100%; margin: 0;' align='left'>or if you are unable to click on the link, copy and paste the following link into your browser:</p>";
				$html[] = "<p style='padding: 10px; max-width:600px; overflow-wrap: break-word;' align='left'>". $data['url'] ."</p>";
				$html[] = "</div>";
		$html[] = "</td>";
	$html[] = "</tr>";
$html[] = "</tbody>";
$html[] = "</table>";

if(isset($data['web_url'])) {
	$html[] = "<p align='center' style='margin-bottom:0;'><a href='". $data['web_url'] ."'>View this email in your browser</a></p>";
}