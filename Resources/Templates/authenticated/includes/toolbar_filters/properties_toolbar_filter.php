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
						
						$html[] = "<div class='d-flex gap-2'>";
							$html[] = "<div class='mb-3 flex-fill'>";
								$html[] = "<div class='form-floating'>";
									$html[] = "<select id='listing_type' class='form-select' name='listing_type'>";
										$html[] = "<option value=''>Any</option>";
										foreach($data['listing_types'] as $listing_type) {
											$html[] = "<option value='$listing_type'>$listing_type</option>";
										}
									$html[] = "</select>";
									$html[] = "<label for='listing_type'>Listing Type</label>";
								$html[] = "</div>";
							$html[] = "</div>";
							
							$html[] = "<div class='mb-3 flex-fill'>";
								$html[] = "<div class='form-floating'>";
									$html[] = "<select id='status' class='form-select' name='status'>";
										$html[] = "<option value=''>Any</option>";
										$html[] = "<option value='1'>Available</option>";
										$html[] = "<option value='2'>Sold</option>";
									$html[] = "</select>";
									$html[] = "<label for='status'>Status</label>";
								$html[] = "</div>";
							$html[] = "</div>";
						$html[] = "</div>";

						$html[] = "<div class='text-center text-muted mb-2'>&mdash;AND&mdash;</div>";
						$html[] = "<div class='mb-3'>";
							$html[] = "<div class='form-floating'>";
								$html[] = "<select id='category' class='form-select tomselected' name='category'>";
									$html[] = "<option value=''>Any</option>";
									foreach($data['categories'] as $key => $categories) {
										$html[] = "<optgroup label='$key'>";
										foreach($categories as $category) {
											$html[] = "<option value='$category'>$category</option>";
										}
										$html[] = "</optgroup>";
									}
								$html[] = "</select>";
								$html[] = "<label for='category'>Category</label>";
							$html[] = "</div>";
						$html[] = "</div>";
						$html[] = "<div class='text-center text-muted mb-2'>&mdash;AND&mdash;</div>";
						$html[] = "<div class='mb-3'>";
							$html[] = "<label class=''>Price Range</label>";
							$html[] = "<div class='mt-2'>";
								$html[] = "<div class='slider-display' data-min='1000000' data-max='300000000' data-steps='1000000' data-input-from-id='priceFrom' data-input-to-id='priceTo'></div>";
							$html[] = "</div>";
						$html[] = "</div>";
						$html[] = "<div class='text-center text-muted mb-2'>&mdash;AND&mdash;</div>";
						$html[] = "<div class='mb-3'>";
							$html[] = "<div class='d-flex gap-2'>";
								$html[] = "<div class='w-100 form-floating'><input id='created_at_from' class='form-control' autocomplete='off' name='created_at[from]' type='date' value='' /><label for='created_at_from'>Created Date From</label></div>";
								$html[] = "<div class='w-100 form-floating'><input id='created_at_to' class='form-control' autocomplete='off' name='created_at[to]' type='date' value='' /><label for='created_at_to'>Created Date To</label></div>";
							$html[] = "</div>";
						$html[] = "</div>";
						$html[] = "<div class='text-center text-muted mb-2'>&mdash;OR&mdash;</div>";
						$html[] = "<div class='mb-3'>";
							$html[] = "<div class='d-flex gap-2'>";
								$html[] = "<div class='w-100 form-floating'><input id='modified_at_from' class='form-control' autocomplete='off' name='modified_at[from]' type='date' value='' /><label for='modified_at_from'>Modified Date From</label></div>";
								$html[] = "<div class='w-100 form-floating'><input id='modified_at_to' class='form-control' autocomplete='off' name='modified_at[to]' type='date' value='' /><label for='modified_at_to'>Modified Date To</label></div>";
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