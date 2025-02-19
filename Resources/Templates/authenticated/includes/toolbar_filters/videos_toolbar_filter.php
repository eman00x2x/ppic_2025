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
								$html[] = "<select id='category' class='form-select' name='category'>";
									$html[] = "<option value=''>Any</option>";
									$html[] = "<option value='News'>News</option>";
									$html[] = "<option value='Tips'>Tips</option>";
									$html[] = "<option value='Finance'>Finance</option>";
									$html[] = "<option value='Blog'>Blog</option>";
									$html[] = "<option value='Investments'>Investments</option>";
								$html[] = "</select>";
								$html[] = "<label for='category'>Category</label>";
							$html[] = "</div>";
						$html[] = "</div>";
						$html[] = "<div class='text-center text-muted mb-2'>&mdash;AND&mdash;</div>";
						$html[] = "<div class='mb-3'>";
							$html[] = "<div class='d-flex gap-2'>";
								$html[] = "<div class='w-100 form-floating'><input id='created_at_from' class='form-control' autocomplete='off' name='created_at[from]' type='date' value='' /><label for='created_at_from'>Created Date From</label></div>";
								$html[] = "<div class='w-100 form-floating'><input id='created_at_to' class='form-control' autocomplete='off' name='created_at[to]' type='date' value='' /><label for='created_at_to'>Created Date To</label></div>";
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