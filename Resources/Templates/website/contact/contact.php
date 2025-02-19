<?php

use EO\View;

View::setMasterTemplate(path: "/website/template.php");

View::setDocumentHeader(
	data: [
		'title' => "Contact " . CONFIG['site_name'],
		'description' => '',
		'url' => DOMAIN . url(),
		'image' => '',
		'modified_at' => DATE_NOW,
		"scripts" => [
			CDN . "/js/vendor/validatejs-0.13.1/validate.min.js",
		]
	]
);

View::define( name: "contact_form", path: "/website/includes/contact.form.php", data: $data );

$html[] = "<div class='page-body bg-white'>";
    $html[] = "<div class='container'>";

        $html[] = "<div class='row'>";
           $html[] = "<div class='col-lg-6 col-md-6 col-sm-12 col-12'>";

				$html[] = "<h1>Contact Us</h1>";
				$html[] = "<div>";
					$html[] = CONFIG['contact_info']['contact_page_text'];
				$html[] = "</div>";

				$html[] = "<div class='p-5 ps-0'>";
					$html[] = "<h2>Contact Form</h2>";

					$html[] = View::include("contact_form");
				$html[] = "</div>";

            $html[] = "</div>";
        $html[] = "</div>";
    $html[] = "</div>";
$html[] = "</div>";