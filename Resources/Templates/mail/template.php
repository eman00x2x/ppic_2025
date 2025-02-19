<?php
$html[] = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>";
	$html[] = "<html>";
	$html[] = "<head>";
		$html[] = "<meta http-equiv='x-ua-compatible' content='ie=edge'>";
		$html[] = "<meta name='x-apple-disable-message-reformatting'>";
		$html[] = "<meta name='viewport' content='width=device-width, initial-scale=1'>";
		$html[] = "<meta name='format-detection' content='telephone=no, date=no, address=no, email=no'>";
		$html[] = "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
		$html[] = "<style type='text/css'>";
			$html[] = "body,table,td{font-family:Helvetica,Arial,sans-serif !important}.ExternalClass{width:100%}.ExternalClass,.ExternalClass p,.ExternalClass span,.ExternalClass font,.ExternalClass td,.ExternalClass div{line-height:150%}a{text-decoration:none}*{color:inherit}a[x-apple-data-detectors],u+#body a,#MessageViewBody a{color:inherit;text-decoration:none;font-size:inherit;font-family:inherit;font-weight:inherit;line-height:inherit}img{-ms-interpolation-mode:bicubic}table:not([class^=s-]){font-family:Helvetica,Arial,sans-serif;mso-table-lspace:0pt;mso-table-rspace:0pt;border-spacing:0px;border-collapse:collapse}table:not([class^=s-]) td{border-spacing:0px;border-collapse:collapse}@media screen and (max-width: 600px){*[class*=s-lg-]>tbody>tr>td{font-size:0 !important;line-height:0 !important;height:0 !important}}";
		$html[] = "</style>";
	$html[] = "</head>";

	$html[] = "<body bgcolor='#f7fafc' style='outline: 0; width: 100%; min-width: 100%; height: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;  line-height: 24px; font-weight: normal; font-size: 16px; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; color: #000000; margin: 0; padding: 0; border-width: 0;' >";

		$html[] = "<table class='body' valign='top' role='presentation' border='0' cellpadding='0' cellspacing='0' style='outline: 0; width: 100%; min-width: 100%; height: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;  line-height: 24px; font-weight: normal; font-size: 16px; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; color: #000000; margin: 0; padding: 0; border-width: 0;'>";
		$html[] = "<tbody>";
			$html[] = "<tr>";
			$html[] = "<td valign='top' style='line-height: 24px; font-size: 16px; margin: 0;' align='left' >";
			$html[] = "<table class='container' role='presentation' border='0' cellpadding='0' cellspacing='0' style='width: 100%;'>";
				$html[] = "<tbody>";
					$html[] = "<tr>";
						$html[] = "<td align='center' style='line-height: 24px; font-size: 16px; margin: 0; padding: 0 16px;'>";
						
							$html[] = "<!--[if (gte mso 9)|(IE)]>";
							$html[] = "<table align='center' role='presentation'>";
							$html[] = "<tbody>";
								$html[] = "<tr>";
									$html[] = "<td width='600'>";
							$html[] = "<![endif]-->";

										$html[] = "<table align='center' role='presentation' border='0' cellpadding='0' cellspacing='0' style='width: 100%; max-width: 600px; margin: 0 auto;'>";
											$html[] = "<tbody>";
												$html[] = "<tr>";
													$html[] = "<td class='' style='line-height: 24px; font-size: 16px; margin: 0;' align='left'>";
														$html[] = "<div style=' line-height: 1.5; max-width: 767px; color: #323232; font-size: 14px; margin: 0 auto;'>";
															
															$html[] = "<div style='padding: 20px;' align='center'>";
																$html[] = "<img src='" . CDN . "/images/philproperties-logo.png' alt='" . CONFIG['site_name'] . "' >";
															$html[] = "</div>";
													
															$html[] = $data['content'];

															$html[] = "<div style='font-size: 13px; color: #6C7A89; padding: 10px 0px;' align='center'>";
																$html[] = "<br>";
																$html[] = "<p style='line-height: 24px; font-size: 16px; width: 100%; margin: 0;' align='center'>You received this email, because you are registered in <a href=''%20.%20DOMAIN%20.%20'' style='color: #0d6efd;'>" . DOMAIN . "</a>.</p>";
																$html[] = "<p style='line-height: 24px; font-size: 16px; width: 100%; margin: 0;' align='center'>This email message is automated, please do not reply, thus no one will received your message. </p>";
																$html[] = "<br>";
															$html[] = "</div>";
													
														$html[] = "</div>";
													$html[] = "</td>";
												$html[] = "</tr>";
											$html[] = "</tbody>";
										$html[] = "</table>";

							$html[] = "<!--[if (gte mso 9)|(IE)]>";
									$html[] = "</td>";
								$html[] = "</tr>";
							$html[] = "</tbody>";
							$html[] = "</table>";
							$html[] = "<![endif]-->";
						
						$html[] = "</td>";
					$html[] = "</tr>";
				$html[] = "</tbody>";
			$html[] = "</table>";
			$html[] = "</td>";
			$html[] = "</tr>";
		$html[] = "</tbody>";
		$html[] = "</table>";

	$html[] = "</body>";
$html[] = "</html>";