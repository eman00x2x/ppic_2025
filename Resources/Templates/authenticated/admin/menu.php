<?php 

	/** Videos */
	$html[] = "<li class='nav-item'>";
		$html[] = "<a class='nav-link' href='" . url('videos') . "'>";
			$html[] = "<i class='ti ti-brand-youtube nav-link-icon d-lg-inline-block fs-22'></i>";
			$html[] = "<span class='nav-link-title'>Videos</span>";
		$html[] = "</a>";
	$html[] = "</li>";

if(can('view_articles')) {
	$html[] = "<li class='nav-item '>";
		$html[] = "<a class='nav-link dropdown-toggle' href='#' data-bs-toggle='dropdown' data-bs-auto-close='false' role='button' aria-expanded='false'>";
			$html[] = "<i class='ti ti-book nav-link-icon d-lg-inline-block fs-22'></i>";
			$html[] = "<span class='nav-link-title'>Articles</span>";
		$html[] = "</a>";
		$html[] = "<div class='dropdown-menu '>";
			$html[] = "<div class='dropdown-menu-columns'>";
				$html[] = "<div class='dropdown-menu-column'>";
					$html[] = "<a class='dropdown-item' href='" . url('articles') . "'>Article List</a>";
					$html[] = "<a class='dropdown-item' href='" . url('articles.add') . "'>Create Article</a>";
				$html[] = "</div>";
			$html[] = "</div>";
		$html[] = "</div>";
	$html[] = "</li>";
}

if(can('manage_accounts')) {
	$html[] = "<li class='nav-item '>";
		$html[] = "<a class='nav-link dropdown-toggle' href='#' data-bs-toggle='dropdown' data-bs-auto-close='false' role='button' aria-expanded='false'>";
			$html[] = "<i class='ti ti-users nav-link-icon d-lg-inline-block fs-22'></i>";
			$html[] = "<span class='nav-link-title'>Accounts</span>";
		$html[] = "</a>";
		$html[] = "<div class='dropdown-menu '>";
			$html[] = "<div class='dropdown-menu-columns'>";
				$html[] = "<div class='dropdown-menu-column'>";
					$html[] = "<a class='dropdown-item' href='" . url('accounts') . "'>Account List</a>";
					$html[] = "<a class='dropdown-item' href='" . url('accounts.add') . "'>New Account</a>";
				$html[] = "</div>";
			$html[] = "</div>";
		$html[] = "</div>";
	$html[] = "</li>";
}

if(can('manage_settings')) {
	$html[] = "<li class='nav-item '>";
		$html[] = "<a class='nav-link dropdown-toggle' href='#' data-bs-toggle='dropdown' data-bs-auto-close='false' role='button' aria-expanded='false'>";
			$html[] = "<i class='ti ti-settings nav-link-icon d-lg-inline-block fs-22'></i>";
			$html[] = "<span class='nav-link-title'>Settings</span>";
		$html[] = "</a>";
		$html[] = "<div class='dropdown-menu '>";
			$html[] = "<div class='dropdown-menu-columns'>";
				
				if(can('update_system_settings')) {
					$html[] = "<a class='dropdown-item' href='" . url('settings', ['page' => 'system-settings']) . "'>System</a>";
				}

				if(can('update_web_settings')) {
					$html[] = "<a class='dropdown-item' href='" . url('webSettings', ['page' => 'common-settings']) . "'>Web Settings</a>";
				}

			$html[] = "</div>";
		$html[] = "</div>";
	$html[] = "</li>";
}

if(can('access_administration')) {
	$html[] = "<li class='nav-item '>";
		$html[] = "<a class='nav-link dropdown-toggle' href='#' data-bs-toggle='dropdown' data-bs-auto-close='false' role='button' aria-expanded='false'>";
			$html[] = "<i class='ti ti-server-cog nav-link-icon d-lg-inline-block fs-22'></i>";
			$html[] = "<span class='nav-link-title'>Administration</span>";
		$html[] = "</a>";
		$html[] = "<div class='dropdown-menu '>";
			$html[] = "<div class='dropdown-menu-columns'>";
				$html[] = "<div class='dropdown-menu-column'>";
					$html[] = "<a class='dropdown-item' href='" . url('administration.cronTasks') . "'>Cron Tasks</a>";
					$html[] = "<a class='dropdown-item' href='" . url('administration') . "'>Database Query</a>";
					$html[] = "<a class='dropdown-item' href='" . url('administration.databaseBackupFiles') . "'>Database Backups</a>";
					$html[] = "<a class='dropdown-item' href='" . url('logs') . "'>Logs</a>";
				$html[] = "</div>";
			$html[] = "</div>";
		$html[] = "</div>";
	$html[] = "</li>";
}