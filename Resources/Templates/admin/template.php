<!doctype html>
<html lang="en">

    <head>
        <?php require_once("header.php"); ?>
    </head>
    <body>

		<div class='modal' id='modal' aria-labelledby='modal' aria-hidden='true'>
			<div class='modal-dialog modal-lg'>
				<div class='modal-content'>
					<div class='modal-body'>
						<div class='response-modal'></div>
					</div>
				</div>
			</div>
		</div>

		<div class='offcanvas offcanvas-end' tabindex='-1' id='offcanvasEnd' aria-labelledby='offcanvasEndLabel' aria-modal='true' role='dialog'>
			<div class='p-5'>
				<div class='d-flex align-items-center gap-3'>
					<div class='loader'></div>
					<p class='mb-0'>Please wait while retrieving content...</p>
				</div>
			</div>
		</div>

		<div class='page'>

			<aside class="sidebar navbar navbar-vertical navbar-expand-lg navbar-transparent" style="background-color: #1c1c39 !important;" data-bs-theme="dark">

				<div class="container-fluid">
					<button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
						aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
					<h1 class="navbar-brand navbar-brand-autodark">
						<a href=".">
						<img src="../Cdn/images/philproperties-logo.png" width="110" height="32" alt=""
							class="navbar-brand-image">
						</a>
					</h1>
					<div class="collapse navbar-collapse" id="sidebar-menu">
						<ul class="navbar-nav pt-lg-3 mx-lg-2">
							<!-- DASHBOARD -->
							<li class="nav-item fw-bold">
								<a class="nav-link text-white" href="./">
									<i class="ti ti-dashboard nav-link-icon d-lg-inline-block"></i>
									<span class="nav-link-title">Dashboard</span>
								</a>
							</li>
							<!-- ORGANIZATION -->
							<li class="nav-item fw-bold">
								<a class="nav-link text-white" href="<?php echo url("OrganizationsController@index"); ?>">
									<i class="ti ti-building nav-link-icon d-lg-inline-block"></i>
									<span class="nav-link-title">Organization</span>
								</a>
							</li>
							<!-- ACCOUNTS -->
							<li class="nav-item fw-bold">
								<a class="nav-link text-white" href="<?php echo url("AccountsController@index"); ?>">
									<i class="ti ti-users nav-link-icon d-lg-inline-block"></i>
									<span class="nav-link-title">Accounts</span>
								</a>
							</li>
							<!-- PREMIUM GROUPS -->
							<li class="nav-item fw-bold">
								<a class="nav-link text-white" href="<?php echo url("PremiumGroupsController@index"); ?>">
									<i class="ti ti-users nav-link-icon d-lg-inline-block"></i>
									<span class="nav-link-title">Premium Groups</span>
								</a>
							</li>
							<!-- E-BOOKS -->
							<li class="nav-item fw-bold">
								<a class="nav-link text-white" href="<?php echo url("EbooksController@index"); ?>">
									<i class="ti ti-book nav-link-icon d-lg-inline-block"></i>
									<span class="nav-link-title">E-Books</span>
								</a>
							</li>
							<!-- VIDEOS -->
							<li class="nav-item fw-bold">
								<a class="nav-link text-white" href="<?php echo url("VideosController@index"); ?>">
									<i class="ti ti-brand-youtube nav-link-icon  d-lg-inline-block"></i>
									<span class="nav-link-title">Videos</span>
								</a>
							</li>
							<!-- PREMIUMS -->
							<li class="nav-item fw-bold">
								<a class="nav-link text-white" href="<?php echo url("PremiumsController@index"); ?>">
									<i class="ti ti-brand-prisma nav-link-icon d-lg-inline-block"></i>
									<span class="nav-link-title">Premiums</span>
								</a>
							</li>
						</ul>
					</div>
				</div>

			</aside>

			<div class="header">
				<div class="navbar navbar-expand-md d-print-none">
					<div class="container-fluid">
						<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation"></button>
						<h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3"><a href="#"></a></h1>
						
						<div class="navbar-nav flex-row order-md-last">
							<div class="nav-item dropdown d-md-flex me-3">
								
								<a class="nav-link px-0 hide-theme-dark cursor-pointer show" data-bs-toggle="dropdown" aria-expanded="true">
									<i class="ti ti-bell"></i>
								</a>

								<div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card" data-bs-popper="static">
									<div class="notification-wrapper border-bottom">
										<a href="#" class="dropdown-item">
											<div class="w-50 d-flex flex-column">
												<span class="small fw-bold">Lorem Ipsum</span>
												<span class="small text-truncate">Good day Sir Manny. I am lightning</span>
											</div>
											<span class="w-50 small text-muted d-block text-wrap text-end">February 22, 2024 1:14 am</span>
										</a>
									</div>
									<span class="btn dropdown-item text-center text-muted-dark border-bottom">Mark all as read</span>
									<a href="#" class="btn dropdown-item text-center text-muted-dark border-bottom">View all notification</a>
								</div>
							</div>

							<div class="nav-item dropdown">
								<div class="nav-link d-flex lh-1 text-reset p-0 cursor-pointer" data-bs-toggle="dropdown" aria-expanded="false">
									<span class="avatar avatar-sm" style="background-image: url(<?php echo request()->authenticated['account']['profile_image']; ?>)"></span>
									<div class="d-none d-xl-block ps-2">
										<small class="text-muted d-block mb-1">Logged as</small> <span class="text-default">Administrator</span>
									</div>
								</div>

								<div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
									<a class="ajax dropdown-item" href="<?php
										echo url("accounts.edit", ["id" => request()->authenticated['account']['account_id']])
									?>">
										<i class="dropdown-icon ti ti-user me-2"></i> Profile
									</a>
									<div class="dropdown-divider m-0 p-0"></div>
									<a class="dropdown-item doLogOut" href="?logout"><i class="dropdown-icon ti ti-logout-2 me-2"></i> Sign out</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		
			<div class="page-wrapper">
				<?php echo $content; ?>
			</div>

		</div>

    </body>
</html>