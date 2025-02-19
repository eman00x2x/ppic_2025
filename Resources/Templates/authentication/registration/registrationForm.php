<?php

use EO\View;

View::setMasterTemplate(path: "/authentication/template.php");

View::setDocumentHeader(
	data: [
		"title" => "Account Registration",
		"description" => "Account Registration",
		"scripts" => [
			CDN . "/js/vendor/validatejs-0.13.1/validate.min.js",
			CDN . "/js/main/app/registration.js"
		]
	]
);

$html[] = "<div class='d-flex flex-column'>";
	$html[] = "<div class='page page-center mt-5'>";
		$html[] = "<div class='container container-normal py-5 mt-5'>";

			$html[] = "<div class='row align-items-center g-4'>";
				$html[] = "<div class='col-lg'>";
					$html[] = "<div class='container-tight'>";

						$html[] = "<div class='text-center mb-4'>";
							/* $html[] = "<a href='#' class='navbar-brand'><span class='d-block fs-30 fw-bold'><i class='ti ti-building-skyscraper'></i> </span></a>";
							$html[] = "<span class='d-block'><b>Admin Account Authentication</b></span>"; */
						$html[] = "</div>";

						$html[] = "<div class='card card-md'>";
							$html[] = "<div class='card-body'>";

                                $html[] = "<h2 class='card-title align-middle'><i class='ti ti-user-square me-1 fs-22'></i> Account Registration</h2>";

                                $html[] = "<div class='response mb-3'></div>";
								
								$html[] = "<form id='form' class='border-0' action='".url("registration.save")."' method='POST'>";
									$html[] = "<input type='hidden' name='user_agent' id='user_agent' value='' />";
									$html[] = "<input type='hidden' name='photo' id='photo' value='"  .CDN . "/images/blank-profile.png' />";

                                    $html[] = "<div class='mb-3 '>";
                                        $html[] = "<label class='form-label'>Email</label>";
                                        $html[] = "<input type='email' class='form-control' name='email' id='email' placeholder='Enter email' autocomplete='off' tabindex='3'>";
                                    $html[] = "</div>";

									$html[] = "<div class='mb-3 '>";
										$html[] = "<label class='form-label'>Username</label>";
										$html[] = "<input type='text' class='form-control' name='username' id='username'  placeholder='Enter username' autocomplete='off' tabindex='1'>";
									$html[] = "</div>";

									$html[] = "<div class='mb-3 '>";
										$html[] = "<label class='form-label'>Password</label>";
										$html[] = "<input type='password' class='form-control' name='password' id='password' placeholder='Password' tabindex='2'>";
									$html[] = "</div>";

                                    $html[] = "<div class='mb-3 '>";
										$html[] = "<label class='form-label'>Confirm Password</label>";
										$html[] = "<input type='password' class='form-control' name='confirm_password' id='confirmPassword' placeholder='Confirm Password' tabindex='2'>";
									$html[] = "</div>";

									$html[] = "<div class='mb-3'>";
										$html[] = "<label class='form-check'>";
											$html[] = "<input type='checkbox' class='form-check-input' name='agree_terms' id='agreeTerms' value='1'>";
											$html[] = "<span class='form-check-label'>Agree to <a href='".url('web.terms')."' tabindex='-1'>Terms and Conditions</a>.</span>";
										$html[] = "</label>";
									$html[] = "</div>";

									$html[] = "<div class='form-footer'>";
										$html[] = "<span class='btn btn-primary w-100 btn-save'>Create Account</span>";
									$html[] = "</div>";
								$html[] = "</form>";

							$html[] = "</div>";
						$html[] = "</div>";

					$html[] = "</div>";
				$html[] = "</div>";
				$html[] = "<div class='col-lg'>";
					$html[] = "<div class='col-lg d-none d-lg-block'>";
						$html[] = "<img src='".CDN."/images/folders.avif' />";
					$html[] = "</div>";
				$html[] = "</div>";
			$html[] = "</div>";

		$html[] = "</div>";
	$html[] = "</div>";
$html[] = "</div>";