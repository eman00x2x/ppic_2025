<?php

use EO\View;

View::define( name: "header", path: "/authenticated/header.php", data: [] );
View::define( name: "menuTop", path: "/authenticated/menuTop.php", data: [] );
View::define( name: "menuBottom", path: "/authenticated/menuBottom.php", data: [] );
View::define( name: "adminMenu", path: "/authenticated/admin/menu.php", data: [] );
View::define( name: "userMenu", path: "/authenticated/users/menu.php", data: [] );

$html[] = "<!doctype html>";
$html[] = "<html lang='en'>";

    $html[] = "<head>";
        $html[] = View::include("header");
    $html[] = "</head>";
    $html[] = "<body>";

		$html[] = "<div class='offcanvas offcanvas-end' tabindex='-1' id='offcanvasEnd' aria-labelledby='offcanvasEndLabel' aria-modal='true' role='dialog'>";
			$html[] = "<div class='p-5'>";
				$html[] = "<div class='d-flex align-items-center gap-3'>";
					$html[] = "<div class='loader'></div>";
					$html[] = "<p class='mb-0'>Please wait while retrieving content...</p>";
				$html[] = "</div>";
			$html[] = "</div>";
		$html[] = "</div>";

		$html[] = "<div class='page'>";

				$html[] = View::include("menuTop");
				$html[] = View::include("userMenu");

				if(request()->authenticated['account']['account_type'] == "Administrator") {
					$html[] = View::include("adminMenu");
				}

				$html[] = View::include("menuBottom");
			
			$html[] = "<div class='header'>";
				$html[] = "<div class='navbar navbar-expand-md d-print-none'>";
					$html[] = "<div class='container-fluid'>";
						$html[] = "<button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbar-menu' aria-controls='navbar-menu' aria-expanded='false' aria-label='Toggle navigation'></button>";
						$html[] = "<h1 class='navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3'><a href='#'></a></h1>";

						$html[] = "<div class='navbar-nav flex-row order-md-last'>";
							$html[] = "<div class='nav-item dropdown d-md-flex me-3'>";

								$html[] = "<a class='nav-link px-0 hide-theme-dark cursor-pointer show' data-bs-toggle='dropdown' aria-expanded='true'>";
									$html[] = "<i class='ti ti-bell'></i>";
								$html[] = "</a>";

								$html[] = "<div class='dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card' data-bs-popper='static'>";
									$html[] = "<div class='notification-wrapper border-bottom'>";
										$html[] = "<a href='#' class='dropdown-item'>";
											$html[] = "<div class='w-50 d-flex flex-column'>";
												$html[] = "<span class='small fw-bold'>Lorem Ipsum</span>";
												$html[] = "<span class='small text-truncate'>Good day Sir Manny. I am lightning</span>";
											$html[] = "</div>";
											$html[] = "<span class='w-50 small text-muted d-block text-wrap text-end'>February 22, 2024 1:14 am</span>";
										$html[] = "</a>";
									$html[] = "</div>";
									$html[] = "<span class='btn dropdown-item text-center text-muted-dark border-bottom'>Mark all as read</span>";
									$html[] = "<a href='#' class='btn dropdown-item text-center text-muted-dark border-bottom'>View all notification</a>";
								$html[] = "</div>";
							$html[] = "</div>";

							$html[] = "<div class='nav-item dropdown'>";
								$html[] = "<div class='nav-link d-flex lh-1 text-reset p-0 cursor-pointer' data-bs-toggle='dropdown' aria-expanded='false'>";
									$html[] = "<span class='avatar avatar-sm' style='background-image: url(".request()->authenticated['account']['photo'].")'></span>";
									$html[] = "<div class='d-none d-xl-block ps-2'>";
										$html[] = "<small class='text-muted d-block mb-1'>Logged as</small> <span class='text-default'>Administrator</span>";
									$html[] = "</div>";
								$html[] = "</div>";

								$html[] = "<div class='dropdown-menu dropdown-menu-end dropdown-menu-arrow'>";
									$html[] = "<a class='ajax dropdown-item' href='".url("accounts.edit", ["id" => request()->authenticated['account']['account_id']])."'>";
										$html[] = "<i class='dropdown-icon ti ti-user me-2'></i> Profile";
									$html[] = "</a>";
									$html[] = "<div class='dropdown-divider m-0 p-0'></div>";
									$html[] = "<a class='dropdown-item doLogOut' href='?logout=me'><i class='dropdown-icon ti ti-logout-2 me-2'></i> Sign out</a>";
								$html[] = "</div>";
							$html[] = "</div>";
						$html[] = "</div>";
					$html[] = "</div>";
				$html[] = "</div>";
			$html[] = "</div>";

			$html[] = "<div class='page-wrapper'>";
				$html[] = $content;
			$html[] = "</div>";

		$html[] = "</div>";

    $html[] = "</body>";
$html[] = "</html>";