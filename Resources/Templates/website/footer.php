<?php

$html[] = "<div class='footer text-white px-2'>";
	$html[] = "<div class='container'>";
		$html[] = "<div class='row'>";
			$html[] = "<div class='col-md-4 col-lg-4 col-12'>";
				$html[] = "<div class='mb-3'>";
					$html[] = "<h3>PHILPROPERTIES INT'L CORP</h3>";
					$html[] = "<ul class='list-group list-group-flush'>";
						$html[] = "<li class='list-group-item px-0 py-0 border-0'><a href='".url("about")."' class='text-white'>About Us</a></li>";
						$html[] = "<li class='list-group-item px-0 py-0 border-0'><a href='".url("web.agents")."' class='text-white'>Our Team</a></li>";
						$html[] = "<li class='list-group-item px-0 py-0 border-0'><a href='".url("")."' class='text-white'>Careers</a></li>";
						$html[] = "<li class='list-group-item px-0 py-0 border-0'><a href='".url("contact")."' class='text-white'>Contact Us</a></li>";
					$html[] = "</ul>";
				$html[] = "</div>";
			$html[] = "</div>";

			/* $html[] = "<div class='col-md-4 col-lg-4 col-12'>";
				$html[] = "<div class='mb-3'>";
					$html[] = "<h3>USEFUL RESOURCES</h3>";
					$html[] = "<ul class='list-group list-group list-group-flush'>";
						$html[] = "<li class='list-group-item px-0 py-0 border-0'><a href=''  class='text-white'>Reservation Procedure</a></li>";
						$html[] = "<li class='list-group-item px-0 py-0 border-0'><a href=''  class='text-white'>Processes in Investing on a Real Estate Property</a></li>";
						$html[] = "<li class='list-group-item px-0 py-0 border-0'><a href=''  class='text-white'>Practical Tips For Buying A Piece of Real Estate</a></li>";
						$html[] = "<li class='list-group-item px-0 py-0 border-0'><a href=''  class='text-white'>Be A Successful Real Estate Practitioner</a></li>";
					$html[] = "</ul>";
				$html[] = "</div>";
			$html[] = "</div>";
 */
			$html[] = "<div class='col-md-4 col-lg-4 col-12'>";
				$html[] = "<div class='mb-3'>";
					$html[] = "<h3>OFFICE ADDRESS</h3>";
					$html[] = "<p><i class='ti ti-map-pin me-1'></i> ".CONFIG['contact_info']['office_address']."</p>";
				$html[] = "</div>";
			$html[] = "</div>";
		$html[] = "</div>";

		$html[] = "<p class='text-center mt-4'>Copyright &copy; 2006 - ".date("Y").". <a href='".url("/")."' class='text-white'>Philproperties International Corporation</a>. All rights reserved</p>";
	$html[] = "</div>";
$html[] = "</div>";