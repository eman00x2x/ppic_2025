<?php

$html[] = "<div class='d-flex flex-column'>";
	$html[] = "<div class='page page-center'>";
		$html[] = "<div class='container container-tight py-4'>";
			$html[] = "<div class='text-center mb-4 mt-5'>";
				$html[] = "<a href='".WEBDOMAIN."' class='navbar-brand'><span class='d-block fs-30 fw-bold'><i class='ti ti-building-skyscraper'></i> MLS</span></a>";
				$html[] = "<span class='d-block'><b>MLS Account Authentication</b></span>";
			$html[] = "</div>";

			$html[] = "<div class='card card-md'>";
				$html[] = "<div class='card-body'>";
					$html[] = "<h2 class='card-title card-title-lg text-center mb-4'>Authenticate Your Account</h2>";
                    $html[] = "<p class='my-4 text-center'>Please confirm your account by entering the authorization code sent to <strong>+1 856-672-8552</strong>.</p>";

					$html[] = "<form class='border-0' action='' method='POST'>";
                        $html[] = "<input type='hidden' name='csrf_token' value='".csrf_token()."' />";

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
                                $html[] = "<a href='' class='btn w-100'>Cancel</a>";
							    $html[] = "<button type='submit' class='btn btn-primary w-100'>Verify</button>";
						    $html[] = "</div>";
						$html[] = "</div>";

					$html[] = "</form>";
				$html[] = "</div>";
			$html[] = "</div>";

            $html[] = "<div class='text-center text-secondary mt-3'>";
                $html[] = "<p class='px-4'>It may take a minute to receive your code. Haven't received it? <a href=''>Resend a new code.</a></p>";
				$html[] = "<p>Don't have account yet? <a href='".url("/register")."' tabindex='-1'>Sign up</a></p>";
			$html[] = "</div>";

		$html[] = "</div>";
	$html[] = "</div>";
$html[] = "</div>";