<?php

use EO\View;

View::setMasterTemplate(path: "/authenticated/template.php");

/** Document Header Configuration */
View::setDocumentHeader(
	data: [
		'title' => 'System Settings',
		'description' => 'System Settings',
		'scripts' => [
			CDN . "/js/vendor/tabler/dist/libs/tinymce/tinymce.min.js?1724397760",
			CDN . "/js/main/app/settings.js"
		]
	]
);

/** Document Top Configuration */
View::define(
	name: "document_top",
	path: "/authenticated/includes/document_top.php", 
	data: [
		"title" => "<i class='ti ti-settings-cog me-2'></i> Settings"
	]
);

$html[] = View::include("document_top");

$html[] = "<div class='page-body mb-5 pb-5'>";
	$html[] = "<div class='container-xl'>";

		$html[] = "<div class='card'>";
            $html[] = "<div class='row g-0'>";
                $html[] = "<div class='col-12 col-md-3 border-end'>";
                    $html[] = "<div class='card-body'>";
                        $html[] = "<h4 class='subheader'>Settings</h4>";
                        $html[] = "<div class='list-group list-group-transparent'>";
                            if(can("update_system_settings")) { $html[] = "<a href='".url("SettingsController@index", ["page" => "system-settings"])."' class='list-group-item list-group-item-action d-flex align-items-center 		".(url()->contains("/system-settings") 			? "active" : "")."'><i class='ti ti-settings-cog me-2'></i> System Settings</a>"; }
                            if(can("update_data_privacy")) { $html[] = "<a href='".url("SettingsController@index", ["page" => "data-privacy"])."' class='list-group-item list-group-item-action d-flex align-items-center 			".(url()->contains("/data-privacy") 			? "active" : "")."'><i class='ti ti-lock-square me-2'></i> Data Privacy Content</a>"; }
                            if(can("update_terms")) { $html[] = "<a href='".url("SettingsController@index", ["page" => "terms"])."' class='list-group-item list-group-item-action d-flex align-items-center 					".(url()->contains("/terms") 					? "active" : "")."'><i class='ti ti-script me-2'></i> Terms of Service Content</a>"; }
                        $html[] = "</div>";
                    $html[] = "</div>";
                $html[] = "</div>";
                $html[] = "<div class='col-12 col-md-9'>";
                    
                    $html[] = "<div class='card-body'>";
						$html[] = "<div class='row'>";
							$html[] = "<div class='col-md-8 col'>";
							
								$html[] = "<form id='form' action='".url("SettingsController@save")."' method='POST'>";
									
									if(url()->contains("/system-settings") && can("update_system_settings")) {

										$html[] = "<h2 class='mb-4'>System Settings</h2>";

										$html[] = "<div class='mb-5 border rounded-3 p-5'>";
											$html[] = "<h3 class='card-title'>Website Name</h3>";
											$html[] = "<p class='card-subtitle'>Please provide the name of your website, this will appear to all content including Terms and Condition and Data Privacy.</p>";
											$html[] = "<div class='row g-2'>";
												$html[] = "<div class='col-md'>";
													$html[] = "<input type='text' name='site_name' class='form-control' value='".$data['site_name']."' placeholder='Email website name' />";
												$html[] = "</div>";
											$html[] = "</div>";
										$html[] = "</div>";

										$html[] = "<div class='mb-5 border rounded-3 p-5'>";
											$html[] = "<h3 class='card-title'>Email Address Responder</h3>";
											$html[] = "<p class='card-subtitle'>Please provide the email address designated as the responder for sending email notifications to users. Connection details, including SMTP username and password, are required.</p>";
											
											$html[] = "<div class='form-floating mt-4'>";
												$html[] = "<input type='text' name='email_address_responder[email]' id='email_address_responder_email' value='".$data['email_address_responder']['email']."' class='form-control' />";
												$html[] = "<label for='email_address_responder_email'>Email Address</label>";
											$html[] = "</div>";
											$html[] = "<p class='mt-1 mx-1 fs-12 form-hint'>Username to use for SMTP authentication</p>";

											$html[] = "<div class='form-floating mt-4'>";
												$html[] = "<input type='password' name='email_address_responder[password]' id='email_address_responder_password' value='".$data['email_address_responder']['password']."' class='form-control' />";
												$html[] = "<label for='email_address_responder_password'>Password</label>";
											$html[] = "</div>";
											$html[] = "<p class='mt-1 mx-1 fs-12 form-hint'>Password to use for SMTP authentication</p>";

											$html[] = "<div class='form-floating mt-4'>";
												$html[] = "<input type='text' name='email_address_responder[host]' id='email_address_responder_host' value='".$data['email_address_responder']['host']."' class='form-control' />";
												$html[] = "<label for='email_address_responder_host'>Mail Server</label>";
											$html[] = "</div>";
											$html[] = "<p class='mt-1 mx-1 fs-12 form-hint'>Set the hostname of the mail server, usually mail.server.com</p>";

											$html[] = "<div class='form-floating mt-4'>";
												$html[] = "<input type='text' name='email_address_responder[port]' id='email_address_responder_port' value='".$data['email_address_responder']['port']."' class='form-control' />";
												$html[] = "<label for='email_address_responder_port'>Mail Server Port</label>";
											$html[] = "</div>";
											$html[] = "<p class='mt-1 mx-1 fs-12 form-hint'>Set the SMTP port number - likely to be 25, 465 or 587</p>";
											
										$html[] = "</div>";

										$html[] = "<div class='mb-5 border rounded-3 p-5'>";
											$html[] = "<label class='form-check form-switch cursor-pointer mb-0'>";
												$html[] = "<h3 class='card-title' style='margin-left:-40px;'>Maintenance</h3>";
												$html[] = "<p class='card-subtitle' style='margin-left:-40px;'>Set this option to enable or disable if your site is going in maintenance mode</p>";
											
												$html[] = "<input type='checkbox' name='is_maintenance' class='form-check-input' value='1' ".($data['is_maintenance'] == 1 ? "checked" : "")." />";
												$html[] = "<span class='form-check-label'>Enable Maintenance</span>";
											$html[] = "</label>";

										$html[] = "</div>";
									}

									if(url()->contains("/data-privacy") && can("update_data_privacy")) {

										$html[] = "<h2 class='mb-4'>Data Privacy Content</h2>";
										$html[] = "<div class='mb-5'>";
											$html[] = "<p class='card-subtitle mb-2'>Please insert your data privacy content to ensure compliance with regulations and protect user privacy.</p>";
											$html[] = "<textarea id='text-container' name='data_privacy' class='form-control'>".$data['data_privacy']."</textarea>";
										$html[] = "</div>";
									}

									if(url()->contains("/terms") && can("update_terms")) {

										$html[] = "<h2 class='mb-4'>Terms of Service Content</h2>";
										$html[] = "<div class='mb-5'>";
											$html[] = "<p class='card-subtitle mb-2'>Please insert your terms of service content to outline the terms and conditions governing the use of our services.</p>";
											$html[] = "<textarea id='text-container' name='terms' class='form-control'>".$data['terms']."</textarea>";
										$html[] = "</div>";
									}

								$html[] = "</form>";

							$html[] = "</div>";
						$html[] = "</div>";
                    $html[] = "</div>";

                $html[] = "</div>";
            $html[] = "</div>";
        $html[] = "</div>";

	$html[] = "</div>";
$html[] = "</div>";

$html[] = "<div class='btn-save-container fixed-bottom bg-white py-3 border-top'>";
	$html[] = "<div class='row g-0 justify-content-center'>";
		$html[] = "<div class='col-lg-8 col-md-8 col-sm-12 col-12'>";

			$html[] = "<div class='container-xl'>";
				$html[] = "<div class='text-end'>";
					$html[] = "<span class='btn btn-outline-primary btn-save'><i class='ti ti-device-floppy me-2'></i> Save Settings</span>";
				$html[] = "</div>";
			$html[] = "</div>";

		$html[] = "</div>";
	$html[] = "</div>";
$html[] = "</div>";