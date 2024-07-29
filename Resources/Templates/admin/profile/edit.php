<?php

$document->setTitle("Update Profile");
$document->addScript( CDN . "js/uploader.js" );
$document->addScriptDeclaration('

	function validateInput(input) {
		let message = [];

		const data = input.reduce(function (obj, item) {
			obj[item.name] = item.value;
			return obj;
		}, {});

		validate.extend(validate.validators.datetime, {
			parse: function(value, options) {
				return new Date(value).valueOf()
			},
			// Input is a unix timestamp
			format: function(value, options) {
				return new Date(value)
			}
		});

		const validator = validate(
			{
				firstname: data.firstname,
				lastname: data.lastname,
				birthdate: data.birthdate
			},
			{
				firstname: {
					presence: { allowEmpty: false },
					type: "string"
				},
				lastname: {
					presence: { allowEmpty: false },
					type: "string"
				},
				birthdate: {
					datetime: {
						dateOnly: true
					}
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

$html[] = "<form id='uploadForm' class='d-none' action='".url("ProfilesController@upload")."' method='POST' enctype='multipart/form-data'>";
	$html[] = "<input type='hidden' name='csrf_token' value='".csrf_token()."' />";
	$html[] = "<center>";
		$html[] = "<input type='file' name='browseFile' id='browseFile' />";
	$html[] = "</center>";
$html[] = "</form>";

$html[] = "<div class='page-header d-print-none'>";
    $html[] = "<div class='container-xl'>";
        
        $html[] = "<div class='row g-2 align-items-center'>";
            
            $html[] = "<div class='col'>";
                $html[] = "<div class='page-pretitle'></div>";
                $html[] = "<h2 class='page-title'>Profile</h2>";
            $html[] = "</div>";

            $html[] = "<div class='col-auto ms-auto d-print-none'>";
                $html[] = "<div class='btn-list'>";
                    $html[] = "<a href='".url("accounts.edit", ["id" => $data['account_id']])."' class='btn btn-primary'><i class='ti ti-plus me-1'></i> Back to Account</a>";
                $html[] = "</div>";
            $html[] = "</div>";

        $html[] = "</div>";

        $html[] = "<div class='response'></div>";
        
    $html[] = "</div>";
$html[] = "</div>";

$html[] = "<div class='page-body'>";
    $html[] = "<div class='container-xl'>";

        $html[] = "<form id='form' action='".url("profile.save.update", ["id" => $data['profile_id']])."' method='POST'>";

            $html[] = "<input type='hidden' name='csrf_token' value='".csrf_token()."' />";
            $html[] = "<input type='hidden' name='profile_image' id='photo' value='".$data['profile_image']."' />";

            $html[] = "<div class='card mb-3'>";
                $html[] = "<div class='card-body'>";
                    $html[] = "<div class='mb-3'>";
                        $html[] = "<span class='avatar avatar-xxxl photo-preview browseFile cursor-pointer' style='background-image:url(".$data['profile_image'].")'></span>";
                        $html[] = "<span class='form-hint d-block mt-2'>Click to Upload Photo</span>";
						$html[] = "<span class='photo-upload-loader d-block'></span>";
                    $html[] = "</div>";

                    $html[] = "<div class='form-floating mb-3'>";
                        $html[] = "<input type='text' name='firstname' id='firstname' value='".$data['name']['firstname']."' class='form-control' />";
                        $html[] = "<label for='firstname'>Firstname</label>";
                    $html[] = "</div>";

                    $html[] = "<div class='form-floating mb-3'>";
                        $html[] = "<input type='text' name='middlename' id='middlename' value='".$data['name']['middlename']."' class='form-control' />";
                        $html[] = "<label for='middlename'>Middlename</label>";
                    $html[] = "</div>";

                    $html[] = "<div class='form-floating mb-3'>";
                        $html[] = "<input type='text' name='lastname' id='lastname' value='".$data['name']['lastname']."' class='form-control' />";
                        $html[] = "<label for='lastname'>Lastname</label>";
                    $html[] = "</div>";

                    $html[] = "<div class='form-floating mb-3'>";
                        $html[] = "<input type='text' name='suffix' id='suffix' value='".$data['name']['suffix']."' class='form-control' />";
                        $html[] = "<label for='suffix'>Suffix</label>";
                    $html[] = "</div>";

                    $html[] = "<div class='form-floating mb-3'>";
                        $html[] = "<input type='date' name='birthdate' id='birthdate' value='".date("Y-m-d", strtotime($data['birthdate']))."' class='form-control' />";
                        $html[] = "<label for='birthdate'>Birth Date</label>";
                    $html[] = "</div>";
                $html[] = "</div>";
            $html[] = "</div>";

            $html[] = "<div class='card mb-3'>";
				$html[] = "<div class='card-header'>";
					$html[] = "<h3 class='card-title'>Your Websites and Social Media Profiles Link</h3>";
					$html[] = "<div class='card-actions'>";
						$html[] = "<span class='btn btn-primary btn-more' data-container='socials'>add more</span>";
					$html[] = "</div>";
				$html[] = "</div>";
				$html[] = "<div class='card-body socials-container'>";

					if(!isset($data['socials'])) {
						$data['socials'] = [""];
					}

					$count = count($data['socials']) > 0 ? count($data['socials']) : 1;
					for($i=0; $i<$count; $i++) {
						$html[] = "<div class='mb-2 socials-container-$i'>";
							$html[] = "<div class='input-group input-group-flat'>";
								$html[] = "<div class='form-floating'>";
									$html[] = "<input type='text' name='socials[]' id='socials-$i' class='form-control' value='".$data['socials'][$i]."' />";
									$html[] = "<label for='socials-$i' class='fs-12'>Website or Social Media Profile Links</label>";
								$html[] = "</div>";
								$html[] = "<span class='input-group-text text-secondary cursor-pointer btn-remove' data-container='.socials-container-$i'><i class='ti ti-trash fs-16'></i></span>";
							$html[] = "</div>";
						$html[] = "</div>";
					}
				$html[] = "</div>";
				$html[] = "<input type='hidden' id='socials-fields-count' value='$i' />";
			$html[] = "</div>";

            $html[] = "<div class='card mb-3'>";
				$html[] = "<div class='card-header'>";
					$html[] = "<h3 class='card-title'>Your Skills</h3>";
					$html[] = "<div class='card-actions'>";
						$html[] = "<span class='btn btn-primary btn-more' data-container='skills'>add more</span>";
					$html[] = "</div>";
				$html[] = "</div>";
				$html[] = "<div class='card-body skills-container'>";
                   
                    $count = count($data['skills']) > 0 ? count($data['skills']) : 1;
                    for($i=0; $i<$count; $i++) {
                        $html[] = "<div class='mb-2 skills-container-$i'>";
                            $html[] = "<div class='input-group input-group-flat'>";
                                $html[] = "<div class='form-floating'>";
                                    $html[] = "<input type='text' name='skills[]' id='skills-$i' class='form-control' value='".$data['skills'][$i]."' />";
                                    $html[] = "<label for='skills-$i' class='fs-12'>Skill</label>";
                                $html[] = "</div>";
                                $html[] = "<span class='input-group-text text-secondary cursor-pointer btn-remove' data-container='.skills-container-$i'><i class='ti ti-trash fs-16'></i></span>";
                            $html[] = "</div>";
                        $html[] = "</div>";
                    }
                    
				$html[] = "</div>";
				$html[] = "<input type='hidden' id='skills-fields-count' value='$i' />";
			$html[] = "</div>";

            $html[] = "<div class='col-sm-12 col-lg-6 col-md-12'>";
				$html[] = "<div class='card mb-3'>";
					$html[] = "<div class='card-header'>";
						$html[] = "<h3 class='card-title'>Your Affiliation</h3>";
						$html[] = "<div class='card-actions'>";
							$html[] = "<span class='btn btn-primary btn-more' data-container='affiliation'>add more</span>";
						$html[] = "</div>";
					$html[] = "</div>";
					$html[] = "<div class='card-body affiliation-container'>";

                        
                            $count = count($data['affiliation']) > 0 ? count($data['affiliation']) : 1;
                            for($i=0; $i<$count; $i++) {
                                $html[] = "<div class='".($i==0 ? "" : "mb-4 border-bottom")." affiliation-container-$i'>";

                                    $html[] = "<div class='form-floating mb-3 w-100'>";
                                        $html[] = "<input type='text' name='affiliation[$i][organization]' id='affiliation-organization-$i' class='form-control' value='".$data['affiliation'][$i]['organization']."' />";
                                        $html[] = "<label for='affiliation-organization-$i'>Organization Name</label>";
                                    $html[] = "</div>";
                                    
                                    $html[] = "<div class='row'>";
                                        $html[] = "<div class='col-lg-6 col-md-12 col-sm-12'>";
                                            $html[] = "<div class='form-floating mb-3 w-100'>";
                                                $html[] = "<input type='text' name='affiliation[$i][title]' id='affiliation-title-$i' class='form-control' value='".$data['affiliation'][$i]['title']."' />";
                                                $html[] = "<label for='affiliation-title-$i'>Title</label>";
                                            $html[] = "</div>";
                                        $html[] = "</div>";
                                        $html[] = "<div class='col-lg-6 col-md-12 col-sm-12'>";
                                            $html[] = "<div class='d-flex gap-3 justify-content-between'>";
                                                $html[] = "<div class='form-floating mb-3'>";
                                                    $html[] = "<input type='date' name='affiliation[$i][date][from]' id='affiliation-date-$i' class='form-control' style='width:130px;' value='".$data['affiliation'][$i]['date']['from']."' />";
                                                    $html[] = "<label for='affiliation-date-$i'>From</label>";
                                                $html[] = "</div>";
                                                $html[] = "<div class='form-floating mb-3'>";
                                                    $html[] = "<input type='date' name='affiliation[$i][date][to]' id='affiliation-date-$i' class='form-control' style='width:130px;' value='".$data['affiliation'][$i]['date']['to']."' />";
                                                    $html[] = "<label for='affiliation-date-$i'>To</label>";
                                                $html[] = "</div>";
                                            $html[] = "</div>";
                                        $html[] = "</div>";
                                    $html[] = "</div>";
                                    $html[] = "<div class='form-floating mb-3'>";
                                        $html[] = "<textarea name='affiliation[$i][description]' id='affiliation-description-$i' class='form-control' style='height:150px; width:100%'>".$data['affiliation'][$i]['description']."</textarea>";
                                        $html[] = "<label for='affiliation-description-$i'>Summary of your professional role and responsibilities</label>";
                                    $html[] = "</div>";

                                    $html[] = "<p class='fs-12 text-end'>";
                                        $html[] = "<span class='btn btn-sm btn-secondary btn-remove' data-container='.affiliation-container-$i'><i class='ti ti-trash fs-14 me-1'></i> remove</span>";
                                    $html[] = "</p>";
                                $html[] = "</div>";
                            }
                       
					$html[] = "</div>";
					$html[] = "<input type='hidden' id='affiliation-fields-count' value='$i' />";
				$html[] = "</div>";
			$html[] = "</div>";

            $html[] = "<div class='col-sm-12 col-lg-6 col-md-12'>";
				$html[] = "<div class='card mb-3'>";
					$html[] = "<div class='card-header'>";
						$html[] = "<h3 class='card-title'>Education</h3>";
						$html[] = "<div class='card-actions'>";
							$html[] = "<span class='btn btn-primary btn-more' data-container='education'>add more</span>";
						$html[] = "</div>";
					$html[] = "</div>";
					$html[] = "<div class='card-body education-container'>";

                            $count = count($data['education']) > 0 ? count($data['education']) : 1;
                            for($i=0; $i<$count; $i++) {
                                $html[] = "<div class='".($i==0 ? "" : "mb-4 border-bottom")." education-container-$i'>";
                                    $html[] = "<div class='form-floating mb-3 w-100'>";
                                        $html[] = "<input type='text' name='education[$i][school]' id='education-school-$i' class='form-control' value='".$data['education'][$i]['school']."' />";
                                        $html[] = "<label for='education-school-$i'>School Name</label>";
                                    $html[] = "</div>";

                                    $html[] = "<div class='row'>";
                                        $html[] = "<div class='col-lg-6 col-md-12 col-sm-12'>";
                                            $html[] = "<div class='form-floating mb-3 w-100'>";
                                                $html[] = "<input type='text' name='education[$i][degree]' id='education-degree-$i' class='form-control' value='".$data['education'][$i]['degree']."' />";
                                                $html[] = "<label for='education-degree-$i'>Degree</label>";
                                            $html[] = "</div>";
                                        $html[] = "</div>";
                                        $html[] = "<div class='col-lg-6 col-md-12 col-sm-12'>";
                                            $html[] = "<div class='d-flex gap-3 justify-content-between'>";
                                                $html[] = "<div class='form-floating mb-3'>";
                                                    $html[] = "<input type='date' name='education[$i][date][from]' id='education-date-$i' class='form-control' style='width:130px;' value='".$data['education'][$i]['date']['from']."' />";
                                                    $html[] = "<label for='education-date-$i'>From</label>";
                                                $html[] = "</div>";
                                                $html[] = "<div class='form-floating mb-3'>";
                                                    $html[] = "<input type='date' name='education[$i][date][to]' id='education-date-$i' class='form-control' style='width:130px;' value='".$data['education'][$i]['date']['to']."' />";
                                                    $html[] = "<label for='education-date-$i'>To</label>";
                                                $html[] = "</div>";
                                            $html[] = "</div>";
                                        $html[] = "</div>";
                                    $html[] = "</div>";

                                    $html[] = "<p class='fs-12 text-end'>";
                                        $html[] = "<span class='btn btn-sm btn-secondary btn-remove' data-container='.education-container-$i'><i class='ti ti-trash fs-14 me-1'></i> remove</span>";
                                    $html[] = "</p>";

                                $html[] = "</div>";
                            }
                       
					$html[] = "</div>";

					$html[] = "<input type='hidden' id='education-fields-count' value='$i' />";

				$html[] = "</div>";
			$html[] = "</div>";

            $html[] = "<div class='text-end'>";
                $html[] = "<span class='btn btn-primary btn-save'><i class='ti ti-device-floppy me-1'></i> Save Profile</span>";
            $html[] = "</div>";

        $html[] = "</form>";

    $html[] = "</div>";
$html[] = "</div>";