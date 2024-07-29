<?php

$document->setTitle("New Account");
$document->addScript( CDN . "js/uploader.js" );
$document->addScript( CDN . "vendor/validatejs-0.13.1/validate.min.js");
$document->addScriptDeclaration('
	function validateInput(input) {
		let message = [];
		const data = input.reduce(function (obj, item) {
			obj[item.name] = item.value;
			return obj;
		}, {});
		const validator = validate(
			{
				email: data.email,
				username: data.username,
				password: data.password
			},
			{
				email: {
					presence: { allowEmpty: false },
					type: "string",
					format: {
						pattern: /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/,
						message: "must be a valid email address."
					}
				},
				username: {
					presence: { allowEmpty: false },
					type: "string",
					length: { minimum: 6 },
				},
				password: {
					presence: { allowEmpty: false }
				}
			}
		);
		if (validator !== undefined) {
			for (key in validator) {
				message.push(validator[key]);
			}
			return message.join(", ");
		}
		return false;
	}
');

$html[] = "<div class='page-header d-print-none'>";
    $html[] = "<div class='container-xl'>";

        $html[] = "<div class='row g-2 align-items-center'>";
            
            $html[] = "<div class='col'>";
                $html[] = "<div class='page-pretitle'></div>";
                $html[] = "<h2 class='page-title'>Accounts</h2>";
            $html[] = "</div>";

            $html[] = "<div class='col-auto ms-auto d-print-none'>";
                
            $html[] = "</div>";

        $html[] = "</div>";

        $html[] = "<div class='response'></div>";
        
    $html[] = "</div>";
$html[] = "</div>";

$html[] = "<form id='uploadForm' class='d-none' action='".url("ProfilesController@upload")."' method='POST' enctype='multipart/form-data'>";
    $html[] = "<input type='hidden' name='csrf_token' value='".csrf_token()."' />";
    $html[] = "<input type='file' name='browseFile' id='browseFile' value='' class='form-control' />";
$html[] = "</form>";

$html[] = "<div class='page-body'>";
    $html[] = "<div class='container-xl'>";

        $html[] = "<form id='form' action='".url("accounts.save.new")."' method='POST'>";

            $html[] = "<input type='hidden' name='csrf_token' value='".csrf_token()."' />";
            $html[] = "<input type='hidden' name='organization_id' value='1' />";
            $html[] = "<input type='hidden' name='profile_image' id='photo' value='' />";

            $html[] = "<div class='card mb-3'>";
                $html[] = "<div class='card-body'>";
                    $html[] = "<div class='row'>";
                        $html[] = "<div class='col-md-6 col-lg-6 col-sm-12 col-12'>";

                            $html[] = "<h3 class='card-title'>Account Details</h3>";

                            $html[] = "<div class='form-floating mb-3'>";
                                $html[] = "<select name='account_type' id='account_type' class='form-select'>";
                                    foreach($data['account_types'] as $type) {
                                        $html[] = "<option value='$type'>$type</option>";
                                    }
                                $html[] = "</select>";
                                $html[] = "<label for='account_type'>Account Type</label>";
                            $html[] = "</div>";

                            $html[] = "<div class='form-floating mb-3'>";
                                $html[] = "<input type='text' name='username' id='username' value='' class='form-control'  />";
                                $html[] = "<label for='username'>Username</label>";
                            $html[] = "</div>";

                            $html[] = "<div class='form-floating mb-3'>";
                                $html[] = "<input type='email' name='email' id='email' value='' class='form-control'  />";
                                $html[] = "<label for='email'>Email</label>";
                            $html[] = "</div>";

                            $html[] = "<div class='form-floating mb-3'>";
                                $html[] = "<select name='status' id='status' class='form-select'>";
                                    foreach($data['statuses'] as $statuses) {
                                        $html[] = "<option value='$statuses'>".ucwords(str_replace("_", " ", $statuses))."</option>";
                                    }
                                $html[] = "</select>";
                                $html[] = "<label for='status'>Status</label>";
                            $html[] = "</div>";

                        $html[] = "</div>";
                        $html[] = "<div class='col-md-6 col-lg-6 col-sm-12 col-12'>";
                            
                            $html[] = "<div class='d-flex justify-content-between align-items-center mb-3'>";
                                $html[] = "<h3 class='card-title'>Profile</h3>";
                            $html[] = "</div>";

                            $html[] = "<div class='d-flex gap-3 flex-wrap'>";
                                $html[] = "<div class=''>";
                                    $html[] = "<span class='avatar avatar-xxxl photo-preview browseFile cursor-pointer' style='background-image:url(".CDN."images/blank-profile.png)'></span>";

                                $html[] = "</div>";
                                $html[] = "<div class=''>";
                                    $html[] = "<div class='form-floating mb-2'>";
                                        $html[] = "<input type='text' name='name[firstname]' id='firstname' value='' class='form-control' />";
                                        $html[] = "<label for='firstname'>Firstname</label>";
                                    $html[] = "</div>";

                                    $html[] = "<div class='form-floating mb-2'>";
                                        $html[] = "<input type='text' name='name[middlename]' id='middlename' value='' class='form-control' />";
                                        $html[] = "<label for='middlename'>Middlename</label>";
                                    $html[] = "</div>";

                                    $html[] = "<div class='form-floating mb-2'>";
                                        $html[] = "<input type='text' name='name[lastname]' id='lastname' value='' class='form-control' />";
                                        $html[] = "<label for='lastname'>Lastname</label>";
                                    $html[] = "</div>";

                                    $html[] = "<div class='form-floating mb-2'>";
                                        $html[] = "<input type='text' name='name[suffix]' id='suffix' value='' class='form-control' />";
                                        $html[] = "<label for='suffix'>Suffix</label>";
                                    $html[] = "</div>";

                                    $html[] = "<div class='form-floating mb-2'>";
                                        $html[] = "<input type='date' name='birthdate' id='birthdate' value='' class='form-control' />";
                                        $html[] = "<label for='birthdate'>Birth Date</label>";
                                    $html[] = "</div>";
                                $html[] = "</div>";
                            $html[] = "</div>";
                            

                        $html[] = "</div>";
                    $html[] = "</div>";
                $html[] = "</div>";
            $html[] = "</div>";

            $html[] = "<div class='card mb-3'>";
                $html[] = "<div class='card-body'>";
                    $html[] = "<div class='row'>";
                        $html[] = "<div class='col-md-6 col-lg-6 col-sm-12 col-12'>";
                            $html[] = "<h3 class='card-title mb-0'>Account Credentials</h3>";
                            $html[] = "<span class='form-hint'>The account password.</span>";
                            
                            $html[] = "<div class='form-floating mb-3 mt-3'>";
                                $html[] = "<input type='password' name='password' id='password' value='' class='form-control' />";
                                $html[] = "<label for='password'>Password</label>";
                            $html[] = "</div>";

                            $html[] = "<div class='form-floating mb-3'>";
                                $html[] = "<input type='password' name='confirm_password' id='confirm_password' value='' class='form-control' />";
                                $html[] = "<label for='confirm_password'>Confirm Password</label>";
                            $html[] = "</div>";
                        $html[] = "</div>";
                    $html[] = "</div>";
                $html[] = "</div>";
            $html[] = "</div>";

            $html[] = "<div class='text-end'>";
                $html[] = "<span class='btn btn-primary btn-save'><i class='ti ti-device-floppy me-1'></i> Save Account</span>";
            $html[] = "</div>";

        $html[] = "</form>";

    $html[] = "</div>";
$html[] = "</div>";

