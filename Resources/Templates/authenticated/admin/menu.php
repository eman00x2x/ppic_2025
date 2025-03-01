<?php 

	/** Videos */
	$html[] = "<li class='nav-item'>";
		$html[] = "<a class='nav-link' href='" . url('videos') . "'>";
			$html[] = "<i class='ti ti-brand-youtube nav-link-icon d-lg-inline-block fs-22'></i>";
			$html[] = "<span class='nav-link-title'>Videos</span>";
		$html[] = "</a>";
	$html[] = "</li>";

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

if(can('access_administration')) {
	$html[] = "<li class='nav-item '>";
		$html[] = "<a class='nav-link dropdown-toggle' href='#' data-bs-toggle='dropdown' data-bs-auto-close='false' role='button' aria-expanded='false'>";
			$html[] = "<i class='ti ti-server-cog nav-link-icon d-lg-inline-block fs-22'></i>";
			$html[] = "<span class='nav-link-title'>Administration</span>";
		$html[] = "</a>";
		$html[] = "<div class='dropdown-menu '>";
			$html[] = "<div class='dropdown-menu-columns'>";
				$html[] = "<div class='dropdown-menu-column'>";
					$html[] = "<a class='dropdown-item' href='" . url('logs') . "'>Logs</a>";
					$html[] = "<a class='dropdown-item' href='" . url('administration.viewErrorLogFile') . "'>Error Log File</a>";
					$html[] = "<a class='dropdown-item' href='" . url('administration.cronTasks') . "'>Cron Tasks</a>";
					$html[] = "<a class='dropdown-item' href='" . url('administration.diskSpaces') . "'>Disks Usage</a>";
					$html[] = "<a class='dropdown-item' href='" . url('administration') . "'>Database Query</a>";
					$html[] = "<a class='dropdown-item' href='" . url('administration.databaseBackupFiles') . "'>Database Backups</a>";
				$html[] = "</div>";
			$html[] = "</div>";
		$html[] = "</div>";
	$html[] = "</li>";
}