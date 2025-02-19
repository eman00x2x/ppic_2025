<?php

use EO\View;

View::setMasterTemplate(path: "/website/template.php");

View::define( name: "social_media_share", path: "/website/includes/social.media.share.php", data: $data );

/** Document Header Configuration */
View::setDocumentHeader(
	data: [
		'title' => $data['title'],
		'description' => $data['short_desc'],
		'url' => $data['url'],
		'image' => $data['banner'],
		'modified_at' => $data['modified_at']
	]
);

$social_media_share = View::include("social_media_share", $data);

$html[] = "<div class='page-body bg-white'>";
     $html[] = "<div class='container-xl'>";

		$html[] = "<div class='row'>";
			$html[] = "<div class='col-md-8 col-12'>";
			
				$html[] = "<div class='article'>";
					
					$html[] = "<div class='d-flex flex-wrap justify-content-between'>";

						$html[] = "<div class=''>";
							$html[] = "<h1 class='mb-1'>".$data['title']."</h1>";
							$html[] = "<p class='m-0 p-0 text-muted fs-12'>Last updated: ".date("F d, Y", $data['created_at'])."</p>";

							

						$html[] = "</div>";

						$html[] = "<div class='share-buttons'>";
							$html[] = $social_media_share;
						$html[] = "</div>";
					$html[] = "</div>";


					$html[] = "<div class='mt-4 fs-16'>";
						$html[] = $data['content'];

						$html[] = "<div class='share-buttons'>";
							$html[] = $social_media_share;
						$html[] = "</div>";

					$html[] = "</div>";
				$html[] = "</div>";

			$html[] = "</div>";
			$html[] = "<div class='col-md-4 col-12'>";

			$html[] = "</div>";
		$html[] = "</div>";

    $html[] = "</div>";
$html[] = "</div>";