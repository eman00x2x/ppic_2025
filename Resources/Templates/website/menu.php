<?php

$html[] = "<div class='modal ' id='menuModalToggle' aria-labelledby='menuModalToggleLabel' tabindex='-1'>";
	$html[] = "<div class='modal-dialog modal-fullscreen'>";
		$html[] = "<div class='modal-content'>";

			$html[] = "<div class='container'>";
				$html[] = "<div class='modal-header'>";
					$html[] = "<h1 class='modal-title fs-5 py-3' id='menuModalToggleLabel'>";
						$html[] = "<a href=''><img src='".CDN."/images/philproperties-logo.png' alt='Philproperties Intl Logo' /></a>";
					$html[] = "</h1>";
					$html[] = "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
				$html[] = "</div>";

				$html[] = "<div class='modal-body pt-4'>";
					$html[] = "<div class='row'>";
						$html[] = "<div class='col-12 col-md-4 col-lg-4 col-xl-4'>";
							$html[] = "<h4 class='fw-bold'>Main</h4>";
							$html[] = "<div class='list-group list-group-flush mb-3'>";
								$html[] = "<a class='list-group-item list-group-item-action p-2' href='".url("web")."'><i class='ti ti-home me-1'></i> Home</a>";
								$html[] = "<a class='list-group-item list-group-item-action p-2' href='".url("web.videos")."'><i class='ti ti-brand-youtube me-1'></i> Videos</a>";
								$html[] = "<a class='list-group-item list-group-item-action p-2' href='".url("web.articles")."'><i class='ti ti-news me-1'></i> Articles</a>";
								$html[] = "<a class='list-group-item list-group-item-action p-2' href='".url("")."'><i class='ti ti-arrows-join-2 me-1'></i> Join Us</a>";
								$html[] = "<a class='list-group-item list-group-item-action p-2' href='".url("web.about")."'><i class='ti ti-user-heart me-1'></i> About Us</a>";
								$html[] = "<a class='list-group-item list-group-item-action p-2' href='".url("web.contact")."'><i class='ti ti-address-book me-1'></i> Contact Us</a>";
								$html[] = "<a class='list-group-item list-group-item-action p-2' href='".url("login")."'><i class='ti ti-login-2 me-1'></i> Account Login</a>";
							$html[] = "</div>";
						$html[] = "</div>";

						$html[] = "<div class='col-12 col-md-4 col-lg-4 col-xl-4'>";
							$html[] = "<h4 class='fw-bold'>Posted Properties</h4>";
							$html[] = "<div class='list-group list-group-flush'>";
								$html[] = "<a class='list-group-item list-group-item-action p-2' href='".url("/buy")."'><i class='ti ti-home-dollar me-1'></i> Property For Sale</a>";
								$html[] = "<a class='list-group-item list-group-item-action p-2' href='".url("/rent")."'><i class='ti ti-home-move me-1'></i> Property For Rent</a>";
								/* $html[] = "<a class='list-group-item list-group-item-action p-2' href='".url("")."'>Be Our Agent</a>";
								$html[] = "<a class='list-group-item list-group-item-action p-2' href='".url("")."'>Sell Your Property</a>"; */
							$html[] = "</div>";
						$html[] = "</div>";
					$html[] = "</div>";
				$html[] = "</div>";
			$html[] = "</div>";

		$html[] = "</div>";
	$html[] = "</div>";
$html[] = "</div>";

$html[]= "<nav class='navbar navbar-expand-lg bg-white px-2'>";
	$html[]= "<div class='container'>";
		$html[]= "<a class='navbar-brand' href='".url("/")."'><img src='".CDN."/images/philproperties-logo.png' alt='Philproperties Intl Logo' /></a>";
		
		$html[]= "<button class='navbar-toggler' type='button' data-bs-target='#menuModalToggle' data-bs-toggle='modal' aria-label='Toggle navigation'><span class='navbar-toggler-icon'></span></button>";
		
		$html[]= "<div class='collapse navbar-collapse ms-5' id='navbarSupportedContent'>";
			$html[]= "<ul class='navbar-nav me-auto ms-5 mb-2 mb-lg-0 fs-16'>";
				$html[]= "<li class='nav-item'><a class='nav-link ".(trim(url()->getPath(), "/") == "buy" ? "active fw-bold" : "")."' href='".url("/buy")."'>For Sale</a></li>";
				$html[]= "<li class='nav-item'><a class='nav-link ".(trim(url()->getPath(), "/") == "rent" ? "active fw-bold" : "")."' href='".url("/rent")."'>For Rent</a></li>";
				/* $html[]= "<li class='nav-item'><a class='nav-link' href='#'>Be Our Agent</a></li>";
				$html[]= "<li class='nav-item'><a class='nav-link' href='#'>Sell Your Property</a></li>"; */
			$html[]= "</ul>";

			$html[] = "<div class='d-flex'>";
				$html[]= "<a class='cursor-pointer text-dark' data-bs-target='#menuModalToggle' data-bs-toggle='modal'><span class='navbar-toggler-icon me-1'></span> Menu</a>";
			$html[]= "</div>";
		$html[]= "</div>";

	$html[]= "</div>";
$html[]= "</nav>";