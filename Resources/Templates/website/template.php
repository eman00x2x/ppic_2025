<?php

use EO\View;

View::define( name: "menu", path: "/website/menu.php", data: [] );
View::define( name: "footer", path: "/website/footer.php", data: [] );

$html[] = "<!doctype html>";
$html[] = "<html lang='en'>";

	$html[] = "<head>";
		
		$html[] = "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />";
		$html[] = "<meta charset='utf-8'>";
		$html[] = "<meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>";
		$html[] = "<meta http-equiv='X-UA-Compatible' content='ie=edge'/>";
		$html[] = "<meta name='inDevelopment' content='".DEVELOPMENT."' />";
		$html[] = "<meta name='csrf-token' content='".csrf_token()."' />";
		$html[] = "<meta name='session-id' content='".$_SESSION['id']."' />";

		$html[] = "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css' />";

		$html[] = "<link href='" . CDN . "/js/vendor/tabler/dist/css/tabler.min.css' rel='stylesheet' />";
		$html[] = "<link href='" . CDN . "/js/vendor/tabler/dist/css/tabler-vendors.min.css?1695847769' rel='stylesheet' />";
		$html[] = "<link href='" . CDN . "/css/main/global.style.css' rel='stylesheet' />";
		$html[] = "<link href='" . CDN . "/css/main/site.style.css' rel='stylesheet'>";

		$html[] = "<script src='" . CDN . "/js/vendor/tabler/dist/js/tabler.min.js'></script>";
		$html[] = "<script type='text/javascript' src='".CDN."/js/vendor/jquery-3.7.1/jquery-3.7.1.min.js'></script>";
		
		$html[] = "<script type='text/javascript' src='".CDN."/js/main/eo.js'></script>";
		$html[] = "<script type='text/javascript'>";
			$html[] = "eo.settings({ domain: '".DOMAIN."', cdn: '".CDN."' });";
		$html[] = "</script>";
		
		$html[] = "<script type='text/javascript' src='".CDN."/js/main/script.js'></script>";
		$html[] = "<script type='text/javascript' src='".CDN."/js/main/app/website.js'></script>";
		$html[] = "<script type='text/javascript' src='".CDN."/js/main/recordTraffic.js'></script>";

		$html[] = \EO\View\DocumentRenderer::fetchHead( \EO\Factories\Factory::Document() );

		$html[] = CONFIG['analytics'];
		$html[] = CONFIG['header_script'];

	$html[] = "</head>";

	$html[] = "<body>";

		$html[] = View::include("menu");
		$html[] = "<div class='page-wrap'>";
			$html[] = $content;
		$html[] = "</div>";
		$html[] = View::include("footer");

	$html[] = "</body>";
$html[] = "</html>";