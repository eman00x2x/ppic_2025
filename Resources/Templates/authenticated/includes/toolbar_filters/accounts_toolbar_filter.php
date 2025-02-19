<?php
$html[] = "<div class='modal modal-blur' id='modalFilterForm' data-bs-backdrop='static' data-bs-keyboard='false' tabindex='-1' role='dialog' aria-labelledby='modal' aria-hidden='true'>";
	$html[] = "<div class='modal-dialog' role='document'>";
		$html[] = "<div class='modal-content'>";
			$html[] = "<div class='modal-status bg-info pt-1'></div>";
			$html[] = "<div class='modal-body'>";

				$html[] = "<span class='btn-close' data-bs-dismiss='modal'></span>";

				$html[] = "<form id='filterForm' action='' method='post'>";
					$html[] = "<div class=''>";
						$html[] = "<h1 class='text-info'><i class='ti ti-filter me-1'></i> Filter Results</h1>";

						$html[] = "<div class='mb-3'>";
							$html[] = "<div class='form-floating'>";
								$html[] = "<select id='status' class='form-select' name='status'>";
									$html[] = "<option value=''>Any</option>";
									$html[] = "<option value='active'>Active</option>";
									$html[] = "<option value='pending_activation'>Pending Activation</option>";
									$html[] = "<option value='ban'>Banned</option>";
								$html[] = "</select>";
								$html[] = "<label for='status'>Status</label>";
							$html[] = "</div>";
						$html[] = "</div>";

						$html[] = "<div class='mb-3'>";
							$html[] = "<div class='form-floating'>";
								$html[] = "<select id='account_type' class='form-select' name='account_type'>";
									$html[] = "<option value=''>Any</option>";
									$html[] = "<option value='Administrator'>Administrator</option>";
									$html[] = "<option value='Registered User'>Registered User</option>";
								$html[] = "</select>";
								$html[] = "<label for='account_type'>Account Type</label>";
							$html[] = "</div>";
						$html[] = "</div>";

						$html[] = "<div class='mb-3'>";
							$html[] = "<div class='d-flex gap-2'>";
								$html[] = "<div class='form-floating'><input id='registered_at_from' class='form-control' autocomplete='off' name='registered_at[from]' type='date' value='' /><label for='registered_at_from'>Registered Date From</label></div>";
								$html[] = "<div class='form-floating'><input id='registered_at_to' class='form-control' autocomplete='off' name='registered_at[to]' type='date' value='' /><label for='registered_at_to'>Registered Date To</label></div>";
							$html[] = "</div>";
						$html[] = "</div>";

					$html[] = "</div>";
				$html[] = "</form>";

			$html[] = "</div>";

			$html[] = "<div class='modal-footer'>";
				$html[] = "<span class='btn' data-bs-dismiss='modal'><i class='ti ti-x me-1'></i> Close</span>";
				$html[] = "<span class='btn btn-primary btn-filter'><i class='ti ti-filter me-1'></i> Submit Filter</span>";
			$html[] = "</div>";
			$html[] = "<div class='bg-info pt-1'></div>";

		$html[] = "</div>";
	$html[] = "</div>";
$html[] = "</div>";