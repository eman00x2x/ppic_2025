<?php

use EO\View;

View::setMasterTemplate(path: "/website/template.php");

/** Document Header Configuration */
View::setDocumentHeader(
	data: [
		'title' => CONFIG['site_name'] . ' Articles',
		'description' => '',
		'url' => DOMAIN . url(),
		'image' => '',
		'modified_at' => DATE_NOW
	]
);

$html[] = "<div class='page-body'>";
    $html[] = "<div class='container-xl'>";

		$html[] = "<div class=''>";
			$html[] = "<div class='row'>";
				$html[] = "<div class='col-lg-9 col-md-8 col-sm-12 col-12 order-md-1 order-2'>";

					if($data['articles']) {
						$html[] = "<div class=''>";
							$html[] = "<h1 class=''><i class='ti ti-news me-1'></i> Articles</h1>";
							$html[] = "<div class='row'>";
							
								for($i=0; $i<count($data['articles']); $i++) {
									$html[] = "<div class='col-lg-4 col-md-6 col-sm-12 col-12'>";
										$html[] = "<div class='card mb-4'>";
											$html[] = "<div class='p-image img-responsive img-responsive-21x9 card-img-top bg-primary-lt' style='height:200px; background-image: url(".$data['articles'][$i]['banner'].");'></div>";
											$html[] = "<div class='card-body mb-0 pb-2'>";
												$html[] = "<div class='p-description' style='height:90px;'>";
													$html[] = "<h3 class='card-title mb-1' title='".$data['articles'][$i]['title']."'>".$data['articles'][$i]['short_title']."</h3>";
													$html[] = "<p class='text-muted fs-14'>".$data['articles'][$i]['short_desc']."</p>";
												$html[] = "</div>";
											$html[] = "</div>";
											$html[] = "<div class='card-footer pt-0 mt-0 border-0'>";
												$html[] = "<a href='".url("web.view.article", [ 'name' => $data['articles'][$i]['name'], 'id' => $data['articles'][$i]['article_id'] ])."' class='stretched-link w-100'></a>";
											$html[] = "</div>";
										$html[] = "</div>";
									$html[] = "</div>";
								}
							
							$html[] = "</div>";
						$html[] = "</div>";
						
						$html[] = View::getPaginationTemplate();

					}else {
						$html[] = "<div class=''>";
							$html[] = "<div class='empty'>";
								$html[] = "<div class='empty-image mb-4'>";
									$html[] = "<img src='".CDN."images/undraw_quitting_time_dm8t.svg' height='128' />";
								$html[] = "</div>";
								$html[] = "<p class='empty-title'>No results found</p>";
								$html[] = "<p class='empty-subtitle text-secondary'>Try adjusting your search or filter to find what you're looking for.</p>";
							$html[] = "</div>";
						$html[] = "</div>";
					}
				
				$html[] = "</div>";
				$html[] = "<div class='col-lg-3 col-md-4 col-sm-12 col-12 order-md-2 order-1'>";
					$html[] = "<div class='mb-4 sticky-md-top'>";
						$html[] = "<div class=''>";
							$html[] = "<div class='card bg-primary text-white'>";
								$html[] = "<div class='card-body'>";
									$html[] = "<div class=''>";
										$html[] = "<h3 class='card-title text-white mb-1'>Categories</h3>";
										$html[] = "<div class='list-group list-group-flush'>";
											if($data['total_article_per_category']) {
												for($i=0; $i<count($data['total_article_per_category']); $i++) {
													$html[] = "<a class='list-group-item d-flex justify-content-between text-decoration-none p-2 text-white' href='".url("web.articles", null, ["category" => strtolower($data['total_article_per_category'][$i]['category'])])."'>";
														$html[] = "<span>".$data['total_article_per_category'][$i]['category']."</span>";
														$html[] = "<span class='badge bg-light text-primary ms-2'>".$data['total_article_per_category'][$i]['total']."</span>";
													$html[] = "</a>";
												}
											}
										$html[] = "</div>";
									$html[] = "</div>";
								$html[] = "</div>";
							$html[] = "</div>";
						$html[] = "</div>";
					$html[] = "</div>";

				$html[] = "</div>";
			$html[] = "</div>";
		$html[] = "</div>";

    $html[] = "</div>";
$html[] = "</div>";