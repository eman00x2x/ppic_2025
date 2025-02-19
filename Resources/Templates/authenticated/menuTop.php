<?php

$html[] = "<aside class='navbar navbar-vertical navbar-expand-lg' data-bs-theme='dark'>";

	$html[] = "<div class='container-fluid'>";
		$html[] = "<button class='navbar-toggler ' type='button' data-bs-toggle='collapse' data-bs-target='#sidebar-menu' aria-controls='sidebar-menu' aria-expanded='false' aria-label='Toggle navigation'>";
			$html[] = "<span class='navbar-toggler-icon'></span>";
		$html[] = "</button>";
		$html[] = "<h1 class='navbar-brand navbar-brand-autodark'>";
			$html[] = "<a href='.'>";
				$html[] = "<img src='" . CDN . "/images/philproperties-logo.png' alt='' class='navbar-brand-image'>";
			$html[] = "</a>";
		$html[] = "</h1>";
		$html[] = "<div class='collapse navbar-collapse' id='sidebar-menu'>";
			$html[] = "<ul class='navbar-nav pt-lg-3 mx-lg-2'>";