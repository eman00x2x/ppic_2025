<?php

use EO\View;

View::define( name: "header", path: "/authenticated/header.php", data: [] );

$html[] = "<!doctype html>";
$html[] = "<html lang='en'>";

    $html[] = "<head>";        
		$html[] = View::include("header");
    $html[] = "</head>";
    $html[] = "<body>";
        
        $html[] = $content;

    $html[] = "</body>";
$html[] = "</html>";
