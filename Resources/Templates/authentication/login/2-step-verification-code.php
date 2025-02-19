<?php
use EO\View;

View::setMasterTemplate(path: "/authentication/template.php");

View::setDocumentHeader(
	data: [
		"title" => "Two Factor Authentication",
		"description" => "Two Factor Authentication",
		"scripts" => [
			CDN . "/js/vendor/validatejs-0.13.1/validate.min.js",
			CDN . "/js/main/app/twoFactorAuthentication.js"
		]
	]
);

$html[] = "<div class='d-flex flex-column'>";
	$html[] = "<div class='page page-center'>";
		$html[] = "<div class='container container-tight py-4'>";
			
			$html[] = "<div class='mt-5 pt-5'>&nbsp;</div>";
			$html[] = "<div class='card card-md'>";
				$html[] = "<div class='card-body'>";
					$html[] = "<h2 class='card-title card-title-lg text-center mb-4'>Authenticate Your Account</h2>";
                    $html[] = "<p class='my-4 text-center'>Please confirm your account by entering the authorization code sent to <strong>".$data['email']."</strong>.</p>";

					$html[] = "<form id='form' class='' action='".url("verifyAuthorizationCode")."' method='POST'>";

					$html[] = "<div class='response'></div>";
                        
                        $html[] = "<div class='my-5'>";
                            $html[] = "<div class='row g-4'>";
                                $html[] = "<div class='col'>";
                                    $html[] = "<div class='row g-2'>";
                                        $html[] = "<div class='col'>";
                                            $html[] = "<input type='text' class='form-control form-control-lg text-center py-3' maxlength='1' inputmode='numeric' pattern='[0-9]*' data-code-input />";
                                        $html[] = "</div>";
                                        $html[] = "<div class='col'>";
                                            $html[] = "<input type='text' class='form-control form-control-lg text-center py-3' maxlength='1' inputmode='numeric' pattern='[0-9]*' data-code-input />";
                                        $html[] = "</div>";
                                        $html[] = "<div class='col'>";
                                            $html[] = "<input type='text' class='form-control form-control-lg text-center py-3' maxlength='1' inputmode='numeric' pattern='[0-9]*' data-code-input />";
                                        $html[] = "</div>";
                                    $html[] = "</div>";
                                $html[] = "</div>";
                                $html[] = "<div class='col'>";
                                    $html[] = "<div class='row g-2'>";
                                        $html[] = "<div class='col'>";
                                            $html[] = "<input type='text' class='form-control form-control-lg text-center py-3' maxlength='1' inputmode='numeric' pattern='[0-9]*' data-code-input />";
                                        $html[] = "</div>";
                                        $html[] = "<div class='col'>";
                                            $html[] = "<input type='text' class='form-control form-control-lg text-center py-3' maxlength='1' inputmode='numeric' pattern='[0-9]*' data-code-input />";
                                        $html[] = "</div>";
                                        $html[] = "<div class='col'>";
                                            $html[] = "<input type='text' class='form-control form-control-lg text-center py-3' maxlength='1' inputmode='numeric' pattern='[0-9]*' data-code-input />";
                                        $html[] = "</div>";
                                    $html[] = "</div>";
                                $html[] = "</div>";
                            $html[] = "</div>";
                        $html[] = "</div>";

						$html[] = "<div class='form-footer'>";
                            $html[] = "<div class='btn-list flex-nowrap'>";
                                $html[] = "<a href='".url("/dashboard")."' class='btn w-100'>Cancel</a>";
							    $html[] = "<span class='btn btn-primary  btn-verify-code w-100'>Verify</span>";
						    $html[] = "</div>";
						$html[] = "</div>";

					$html[] = "</form>";
				$html[] = "</div>";
			$html[] = "</div>";

            $html[] = "<div class='text-center text-secondary mt-3'>";
                $html[] = "<p class='px-4'>It may take a minute to receive your code. Haven't received it? <span class='text-primary btn-send-code cursor-pointer' data-url='".url("sendAuthorizationCode")."'>Resend code.</span></p>";
			$html[] = "</div>";

		$html[] = "</div>";
	$html[] = "</div>";
$html[] = "</div>";