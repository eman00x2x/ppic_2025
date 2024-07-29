<?php

$html[] = "<div class='page-header d-print-none text-white'></div>";

$html[] = "<div class='page page-center mt-5'>";
	$html[] = "<div class='container-tight py-4'>";
		$html[] = "<div class='empty'>";
			$html[] = "<div class='empty-header'>404</div>";
			$html[] = "<p class='empty-title'>Oops… You just found an error page</p>";
			$html[] = "<p class='empty-subtitle text-secondary'>We are sorry but the page you are looking for was not found</p>";
			$html[] = "<div class='empty-action'>";
				$html[] = "<a href='".url("/")."' class='btn btn-primary'>";
				$html[] = "<svg xmlns='http://www.w3.org/2000/svg' class='icon' width='24' height='24' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' fill='none' stroke-linecap='round' stroke-linejoin='round'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M5 12l14 0' /><path d='M5 12l6 6' /><path d='M5 12l6 -6' /></svg>Take me home</a>";
			$html[] = "</div>";
		$html[] = "</div>";
	$html[] = "</div>";
$html[] = "</div>";