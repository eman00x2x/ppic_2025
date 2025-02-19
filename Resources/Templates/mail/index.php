<?php

use EO\View;

$html[] = "<!doctype html>";
$html[] = "<html lang='en'>";
$html[] = "<head>";
	
	$html[] = "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />";
	$html[] = "<meta charset='utf-8'>";
	$html[] = "<meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>";
	$html[] = "<meta http-equiv='X-UA-Compatible' content='ie=edge'/>";

	  $html[] = View\DocumentRenderer::fetchHead( \EO\Factories\Factory::Document() );
	
$html[] = "</head>";
$html[] = "<body>";

	$html[] = "<div class='container'>";
		
		$html[] = View::include('template');
		
	$html[] = "</div>";
$html[] = "</body>";
$html[] = "</html>";

echo implode('', $html);