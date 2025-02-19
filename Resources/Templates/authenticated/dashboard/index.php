<?php

use EO\View;
use EO\Auth\Auth;

View::setMasterTemplate(path: "/authenticated/template.php");

/** Document Header Configuration */
View::setDocumentHeader(
	data: [
		"title" => "Dashboard",
		"description" => "",
		"scripts" => [
			CDN . "/js/vendor/google/chart.js",
			CDN . "/js/main/app/charts.js",
			CDN . "/js/main/app/dashboard.js"
		]
	]
);

/** Document Top Configuration */
View::define(
	name: "document_top",
	path: "/authenticated/includes/document_top.php", 
	data: [
		"title" => "<i class='ti ti-dashboard me-2  fs-32'></i> Dashboard",
		"description" => "Account Overview"
	]
);

$html[] = View::include("document_top");

$html[] = "<div class='page-body'>";
	$html[] = "<div class='container-xl'>";

		$html[] = "<div class='row row-dec row-cards'>";

			$html[] = "<div class='col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12'>";
				$html[] = "<div class='card'>";
					$html[] = "<div class='card-body'>";
						$html[] = "<div class='daily-traffics-overview-chart'>";

							$html[] = "<div class='d-flex justify-content-between'>";
								$html[] = "<div class=''>";
									$html[] = "<h3 class='card-title m-0'>Daily Traffics</h3>";
									$html[] = "<p class='p-0 text-muted'>Total Traffics Per Day</p>";
								$html[] = "</div>";
							
								$html[] = "<div class=''>";
									$html[] = "<select class='form-select select-filter' data-target='totalTrafficsPerDay'>";
										$html[] = "<option value='last-7-days'>Last 7 days</option>";
										$html[] = "<option value='last-30-days'>Last 30 days</option>";
										$html[] = "<option value='last-60-days'>Last 60 days</option>";
										$html[] = "<option value='last-90-days'>Last 90 days</option>";
									$html[] = "</select>";
								$html[] = "</div>";
							$html[] = "</div>";

							$html[] = "<div class='totalTrafficsPerDayLoader'></div>";
							$html[] = "<div id='totalTrafficsPerDay' data-url='".url("ChartsController@getTotalTrafficsPerDay", ["accountId" => Auth::isAdmin() ? "null" : Auth::user()->id])."'></div>";
						$html[] = "</div>";
					$html[] = "</div>";
				$html[] = "</div>";
			$html[] = "</div>";

			$html[] = "<div class='col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12'>";
				$html[] = "<div class='card'>";
					$html[] = "<div class='card-body'>";
						$html[] = "<div class='top-traffics-overview-chart'>";

							$html[] = "<div class='d-flex justify-content-between'>";
								$html[] = "<div class=''>";
									$html[] = "<h3 class='card-title m-0'><a href='".url("traffics.top")."'>Top Traffics</a></h3>";
									$html[] = "<p class='p-0 text-muted'>Top Traffics Last 30 Days</p>";
								$html[] = "</div>";
							
								$html[] = "<div class=''>";
									$html[] = "<select class='form-select select-filter' data-target='totalTrafficsPerUrlLoader'>";
										$html[] = "<option value='last-30-days'>Last 30 days</option>";
										$html[] = "<option value='last-60-days'>Last 60 days</option>";
										$html[] = "<option value='last-90-days'>Last 90 days</option>";
									$html[] = "</select>";
								$html[] = "</div>";
							$html[] = "</div>";

							$html[] = "<div class='totalTrafficsPerUrlLoader'></div>";
							$html[] = "<div id='totalTrafficsPerUrl' style='overflow:auto; height:300px;'>";
								$html[] = "<table class='table table-sm'>";
								$html[] = "<tr>";
									$html[] = "<th>Urls</th>";
									$html[] = "<th class='text-center align-middle'>Total</th>";
								$html[] = "</tr>";

								if($data['topUrls']) {
									foreach($data['topUrls'] as $key => $traffic) {
										$html[] = "<tr>";
											$html[] = "<td>".$traffic['page']." <span class='text-muted d-block fs-12'>".$traffic['url']."</span></td>";
											$html[] = "<td class='text-center  align-middle'>".$traffic['count']."</td>";
										$html[] = "</tr>";
									}
								}
								$html[] = "</table>";
							$html[] = "</div>";
						$html[] = "</div>";
					$html[] = "</div>";
				$html[] = "</div>";
			$html[] = "</div>";

			$html[] = "<div class='col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12'>";
				$html[] = "<div class='card '>";
					$html[] = "<div class='card-body'>";
						$html[] = "<div class='leads-overview-chart '>";

							$html[] = "<div class='d-flex justify-content-between'>";
								$html[] = "<div class=''>";
									$html[] = "<h3 class='card-title m-0'>Daily Leads</h3>";
									$html[] = "<p class='p-0 text-muted'>Total leads per day</p>";
								$html[] = "</div>";
							
								$html[] = "<div class=''>";
									$html[] = "<select class='form-select select-filter' data-target='totalLeadsPerDay'>";
										$html[] = "<option value='last-7-days'>Last 7 days</option>";
										$html[] = "<option value='last-30-days'>Last 30 days</option>";
										$html[] = "<option value='last-60-days'>Last 60 days</option>";
										$html[] = "<option value='last-90-days'>Last 90 days</option>";
									$html[] = "</select>";
								$html[] = "</div>";
							$html[] = "</div>";

							$html[] = "<div class='getTotalLeadsPerDayLoader'></div>";
							$html[] = "<div id='getTotalLeadsPerDay' class='w-100' data-url='".url("ChartsController@getTotalLeadsPerDay", ["accountId" => (Auth::isAdmin() ? "null" : Auth::user()->id)])."'></div>";
						$html[] = "</div>";
					$html[] = "</div>";
				$html[] = "</div>";
			$html[] = "</div>";

			$html[] = "<div class='col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12'>";
				$html[] = "<div class='card '>";
					$html[] = "<div class='card-body'>";
						$html[] = "<div class='properties-category-overview-chart '>";

							$html[] = "<div class='d-flex justify-content-between'>";
								$html[] = "<div class=''>";
									$html[] = "<h3 class='card-title m-0'>Properties Per Category</h3>";
									$html[] = "<p class='p-0 text-muted'>Total properties posted per category</p>";
								$html[] = "</div>";
							
								$html[] = "<div class=''>";
									$html[] = "<select class='form-select select-filter' data-target='totalPropertiesPerCategory'>";
										$html[] = "<option value='last-7-days'>Last 7 days</option>";
										$html[] = "<option value='last-30-days'>Last 30 days</option>";
										$html[] = "<option value='last-60-days'>Last 60 days</option>";
										$html[] = "<option value='last-90-days'>Last 90 days</option>";
									$html[] = "</select>";
								$html[] = "</div>";
							$html[] = "</div>";

							$html[] = "<div class='totalPropertiesPerCategoryLoader'></div>";
							$html[] = "<div id='totalPropertiesPerCategory' class='w-100' data-url='".url("ChartsController@getTotalPropertiesPerCategory", ["accountId" => (Auth::isAdmin() ? "null" : Auth::user()->id)])."'></div>";
						$html[] = "</div>";
					$html[] = "</div>";
				$html[] = "</div>";
			$html[] = "</div>";

			/* $html[] = "<div class='col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12'>";
				$html[] = "<div class='card'>";
					$html[] = "<div class='card-body'>";
						$html[] = "<div class='user-overview-chart'>";

							$html[] = "<div class='d-flex justify-content-between'>";
								$html[] = "<div class=''>";
									$html[] = "<h3 class='card-title m-0'>Listing Type Overview</h3>";
									$html[] = "<p class='p-0 text-muted'>Total listing per type</p>";
								$html[] = "</div>";
							
								$html[] = "<div class=''>";
									$html[] = "<select class='form-select select-filter' data-target='totalPropertiesPerListingType'>";
										$html[] = "<option value='last-7-days'>Last 7 days</option>";
										$html[] = "<option value='last-30-days'>Last 30 days</option>";
										$html[] = "<option value='last-60-days'>Last 60 days</option>";
										$html[] = "<option value='last-90-days'>Last 90 days</option>";
									$html[] = "</select>";
								$html[] = "</div>";
							$html[] = "</div>";

							$html[] = "<div class='totalPropertiesPerListingTypeLoader'></div>";
							$html[] = "<div id='totalPropertiesPerListingType' class='' data-url='".url("ChartsController@getTotalPropertiesPerListingType", ["accountId" => (Auth::isAdmin() ? "null" : Auth::user()->id)])."'></div>";
						$html[] = "</div>";
					$html[] = "</div>";
				$html[] = "</div>";
			$html[] = "</div>";
			
			$html[] = "<div class='col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12'>";
				$html[] = "<div class='card '>";
					$html[] = "<div class='card-body'>";
						$html[] = "<div class='properties-status-overview-chart '>";

							$html[] = "<div class='d-flex justify-content-between'>";
								$html[] = "<div class=''>";
									$html[] = "<h3 class='card-title m-0'>Properties Per Status</h3>";
									$html[] = "<p class='p-0 text-muted'>Total properties posted per status</p>";
								$html[] = "</div>";
							
								$html[] = "<div class=''>";
									$html[] = "<select class='form-select select-filter' data-target='totalPropertiesPerStatus'>";
										$html[] = "<option value='last-7-days'>Last 7 days</option>";
										$html[] = "<option value='last-30-days'>Last 30 days</option>";
										$html[] = "<option value='last-60-days'>Last 60 days</option>";
										$html[] = "<option value='last-90-days'>Last 90 days</option>";
									$html[] = "</select>";
								$html[] = "</div>";
							$html[] = "</div>";

							$html[] = "<div class='totalPropertiesPerStatusLoader'></div>";
							$html[] = "<div id='totalPropertiesPerStatus' class='w-100' data-url='".url("ChartsController@getTotalPropertiesPerStatus", ["accountId" => (Auth::isAdmin() ? "null" : Auth::user()->id)])."'></div>";
						$html[] = "</div>";
					$html[] = "</div>";
				$html[] = "</div>";
			$html[] = "</div>";

			if(Auth::isAdmin()) {
				$html[] = "<div class='col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12'>";
					$html[] = "<div class='card '>";
						$html[] = "<div class='card-body'>";
							$html[] = "<div class='properties-postings-overview-chart '>";

								$html[] = "<div class='d-flex justify-content-between'>";
									$html[] = "<div class=''>";
										$html[] = "<h3 class='card-title m-0'>Daily Postings</h3>";
										$html[] = "<p class='p-0 text-muted'>Total postings per day</p>";
									$html[] = "</div>";
								
									$html[] = "<div class=''>";
										$html[] = "<select class='form-select select-filter' data-target='getMonthlyPostings'>";
											$html[] = "<option value='last-7-days'>Last 7 days</option>";
											$html[] = "<option value='last-30-days'>Last 30 days</option>";
											$html[] = "<option value='last-60-days'>Last 60 days</option>";
											$html[] = "<option value='last-90-days'>Last 90 days</option>";
										$html[] = "</select>";
									$html[] = "</div>";
								$html[] = "</div>";

								$html[] = "<div class='getMonthlyPostingsLoader'></div>";
								$html[] = "<div id='getMonthlyPostings' class='w-100' data-url='".url("ChartsController@getMonthlyPostings", ["accountId" => (Auth::isAdmin() ? "null" : Auth::user()->id)])."'></div>";
							$html[] = "</div>";
						$html[] = "</div>";
					$html[] = "</div>";
				$html[] = "</div>";
			} */

		$html[] = "</div>";

	$html[] = "</div>";
$html[] = "</div>";