<?php

if(MAINTENANCE === false) {

	/** DASHBOARD */
	$html[] = "<li class='nav-item'>";
		$html[] = "<a class='nav-link' href='" . url('dashboard') . "'>";
			$html[] = "<i class='ti ti-dashboard nav-link-icon d-lg-inline-block fs-22'></i>";
			$html[] = "<span class='nav-link-title'>Dashboard</span>";
		$html[] = "</a>";
	$html[] = "</li>";

	/** PROPERTIES */
	$html[] = "<li class='nav-item '>";
		$html[] = "<a class='nav-link dropdown-toggle' href='#' data-bs-toggle='dropdown' data-bs-auto-close='false' role='button' aria-expanded='false'>";
			$html[] = "<i class='ti ti-building nav-link-icon d-lg-inline-block fs-22'></i>";
			$html[] = "<span class='nav-link-title'>Properties</span>";
		$html[] = "</a>";
		$html[] = "<div class='dropdown-menu '>";
			$html[] = "<div class='dropdown-menu-columns'>";
				$html[] = "<div class='dropdown-menu-column'>";
					$html[] = "<a class='dropdown-item' href='" . url('properties') . "'>Property List</a>";
					$html[] = "<a class='dropdown-item' href='" . url('properties.add') . "'>Post Property</a>";
				$html[] = "</div>";
			$html[] = "</div>";
		$html[] = "</div>";
	$html[] = "</li>";

	/** LEADS */
	$html[] = "<li class='nav-item '>";
		$html[] = "<a class='nav-link dropdown-toggle' href='#' data-bs-toggle='dropdown' data-bs-auto-close='false' role='button' aria-expanded='false'>";
			$html[] = "<i class='ti ti-users-group nav-link-icon d-lg-inline-block fs-22'></i>";
			$html[] = "<span class='nav-link-title'>Leads</span>";
		$html[] = "</a>";
		$html[] = "<div class='dropdown-menu '>";
			$html[] = "<div class='dropdown-menu-columns'>";
				$html[] = "<div class='dropdown-menu-column'>";
					$html[] = "<a class='dropdown-item' href='" .url('leads') ."'>Leads List</a>";
					$html[] = "<a class='dropdown-item' href='" .url('leads.groups') ."'>Groups</a>";
					$html[] = "<a class='dropdown-item' href='" . url('leads.add') . "'>Add Leads</a>";
				$html[] = "</div>";
			$html[] = "</div>";
		$html[] = "</div>";
	$html[] = "</li>";

	if(can('access_traffics')) {
		/** TRAFFICS */
		$html[] = "<li class='nav-item '>";
			$html[] = "<a class='nav-link dropdown-toggle' href='#' data-bs-toggle='dropdown' data-bs-auto-close='false' role='button' aria-expanded='false'>";
				$html[] = "<i class='ti ti-traffic-lights nav-link-icon d-lg-inline-block fs-22'></i>";
				$html[] = "<span class='nav-link-title'>Traffics</span>";
			$html[] = "</a>";
			$html[] = "<div class='dropdown-menu '>";
				$html[] = "<div class='dropdown-menu-columns'>";
					$html[] = "<div class='dropdown-menu-column'>";
						$html[] = "<a class='dropdown-item' href='" . url('traffics') . "'>Traffics</a>";
					$html[] = "</div>";
				$html[] = "</div>";
			$html[] = "</div>";
		$html[] = "</li>";
	}
	
}