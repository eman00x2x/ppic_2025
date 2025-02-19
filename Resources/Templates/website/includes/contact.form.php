<?php

$html[] = "<div class=''>";

	$html[] = "<div class='response mb-3'></div>";

	$html[] = "<form id='inquiry-form' action='".url("web.save.leads")."' method='POST'>";

		$html[] = "<input type='hidden' name='source' id='source' value='".SITE_NAME." Contact Page' class='form-control text-dark' />";
		$html[] = "<input type='hidden' name='requirements' id='requirements' value='{}' class='form-control text-dark' />";
		$html[] = "<input type='hidden' name='reference' id='reference' value='{}' class='form-control text-dark' />";
		$html[] = "<input type='hidden' name='account_id' id='account_id' value='2' class='form-control text-dark' />";
		$html[] = "<input type='hidden' name='viewing_date' id='viewing_date' value='' class='form-control text-dark' />";
		$html[] = "<input type='hidden' name='send_to' id='send_to' value='".CONFIG['contact_info']['email']."' class='form-control text-dark' />";

		$html[] = "<div class='form-floating mb-3'>";
			$html[] = "<input type='text' name='name' id='name' value='' class='form-control text-dark' placeholder='Name' />";
			$html[] = "<label for='name' class='text-dark'>Name</label>";
		$html[] = "</div>";

		$html[] = "<div class='form-floating mb-3 '>";
			$html[] = "<input type='text' name='contact_number' id='contact_number' value='' class='form-control text-dark' placeholder='Contact Number' />";
			$html[] = "<label for='name' class='text-dark'>Contact Number</label>";
		$html[] = "</div>";

		$html[] = "<div class='form-floating mb-3 '>";
			$html[] = "<input type='email' name='email' id='email' value='' class='form-control text-dark' placeholder='Email Address' />";
			$html[] = "<label for='name' class='text-dark'>Email Address</label>";
		$html[] = "</div>";

		$html[] = "<div class='form-floating mb-3 '>";
			$html[] = "<textarea name='message' class='form-control text-dark'></textarea>";
			$html[] = "<label for='name' class='text-dark'>Message</label>";
		$html[] = "</div>";

		$html[] = "<div class='d-flex justify-content-between align-items-center gap-2  mb-3'>";
			$html[] = "<div class='flex-grow-1'>";
				$html[] = "<div class='form-floating'>";
					$html[] = "<input type='text' name='security_code' value='' class='form-control text-dark' placeholder='Enter Security Code' />";
					$html[] = "<label for='name' class='text-dark'>Enter Security Code</label>";
				$html[] = "</div>";
			$html[] = "</div>";
			$html[] = "<div class='flex-fill align-self-stretch text-center bg-primary text-white pt-2 rounded'>";
				$html[] = "<input type='hidden' name='generated_security_code' id='generated_security_code' value='' class='form-control text-dark' placeholder='Enter Security Code' />";
				$html[] = "<span id='securityCodeText' class='fw-bold fs-18'><span class='spinner-border spinner-border-sm'></span></span><span class='d-block fs-9 text-muted'>Security Code</span>";
			$html[] = "</div>";
		$html[] = "</div>";

		$html[] = "<p class='text-muted fs-12'>By clicking send message, you accept our <a href='".url("web.terms")."'>Terms and Condition</a> and <a href='".url("web.privacy")."'>Privacy Policy</a> page.</p>";

		$html[] = "<span class='btn btn-primary btn-send-message w-100'><i class='ti ti-send me-1'></i> Send Message</span>";
	$html[] = "</div>";

$html[] = "</div>";