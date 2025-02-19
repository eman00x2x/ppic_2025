<?php

use EO\View;

View::setMasterTemplate(path: "/website/template.php");

/** Document Header Configuration */
View::setDocumentHeader(
	data: [
		'title' => CONFIG['site_name'] . '',
		'description' => '',
		'url' => DOMAIN . url(),
		'image' => '',
		'modified_at' => DATE_NOW
	]
);

View::define( name: "vertical_list", path: "/website/properties/vertical.list.template.php", data: $data );
View::define( name: "contact_form", path: "/website/includes/contact.form.php", data: $data );

$html[] = "<div class='modal' id='watchVideoModal' tabindex='-1' role='dialog'>";
	$html[] = "<div class='modal-dialog modal-lg modal-dialog-centered' role='document'>";
		$html[] = "<div class='modal-content'>";
			$html[] = "<div class='modal-body'>";
				$html[] = "<button type='button' class='close mb-3 text-white' data-dismiss='modal' aria-label='Close'>";
					$html[] = "<span aria-hidden='true'>&times;</span>";
				$html[] = "</button>";
				
				$html[] = "<div class='video_player_wrap'>";
					$html[] = "<div class='embed-responsive embed-responsive-16by9'>";
						$html[] = "<iframe id='player_vid' class='video embed-responsive-item'  src='' frameborder='0' allow='autoplay; encripted-media' allowfullscreen></iframe>";
					$html[] = "</div>";
				$html[] = "</div>";
			$html[] = "</div>";
		$html[] = "</div>";
	$html[] = "</div>";
$html[] = "</div>";


$html[] = "<div class='big-image py-3 position-relative' style='background-image: url(".CDN."/images/big-image.jpeg)'>";
	$html[] = "<div class='container'>";
		$html[] = "<div class='spacing'></div>";

		$html[] = "<div class='row justify-content-center px-2'>";
			$html[] = "<div class='col-md-8 col-12'>";
				$html[] = "<div class='home-text-title'>";
					$html[] = "<h1 class='fw-bold fs-48 lh-1 mb-0'>FIND YOUR DREAM HOME</h1>";
					$html[] = "<p class=''>We have the best properties for you.</p>";
				$html[] = "</div>";
				
				$html[] = "<div class='py-2'>";
					$html[] = "<form id='homeSearch' action='".url("/")."' method='GET' class='w-100'>";

						$html[] = "<div class='text-start'>";
							$html[] = "<div class='btn-group' role='group' aria-label='checkbox toggle button group'>";
								foreach($data['collections']['listing_type'] as $listing_type) {
									$html[] = "<input type='radio' class='btn-check' name='listing_type' id='listing_type_".strtolower($listing_type)."' value='".$listing_type."' autocomplete='off' ".($listing_type == "For Sale" ? "checked" : "").">";
									$html[] = "<label class='btn btn-outline-primary rounded-0 fs-18' for='listing_type_".strtolower($listing_type)."'>".$listing_type."</label>";
								}
							$html[] = "</div>";
						$html[] = "</div>";

						$html[] = "<div class='input-group border rounded-0'>";
							$html[] = "<select name='category' id='category' class='form-select category rounded-0 fs-18' aria-label='Property Category'>";
								foreach($data['collections']['categories'] as $group => $categories) {
									$html[] = "<optgroup label='$group'>";
									foreach($categories as $category) {
										$html[] = "<option value='".$category."'>".$category."</option>";
									}
									$html[] = "</optgroup>";
								}
							$html[] = "</select>";
							$html[] = "<input type='text' class='form-control homeSearchInput rounded-0 fs-18' id='search' name='search' placeholder='Search for properties' aria-label='Search for properties' aria-describedby='button-addon2'>";
							$html[] = "<button class='btn btn-primary btn-homeSearch fs-18' type='button' id='button-addon2'><i class='ti ti-search me-1'></i> Search</button>";
						$html[] = "</div>";

					$html[] = "</form>";
				$html[] = "</div>";
					
			$html[] = "</div>";
		$html[] = "</div>";
	$html[] = "</div>";
$html[] = "</div>";

$html[] = "<div class='page-body'>";
	$html[] = "<div class='pt-5 mb-4 properties featured-properties'>";
		$html[] = "<div class='container'>";
			$html[] = "<div class='text-center mb-4 p-5'>";
				$html[] = "<h2 class='fs-32'>Latest Properties For Sale</h2>";
				$html[] = "<p>Discover your dream home or next investment from our latest listings. Don't miss out on these exceptional opportunities!</p>";
			$html[] = "</div>";

			$html[] = View::include("vertical_list");
			
			$html[] = "<p class='text-center mt-3'>";
				$html[] = "<a href='".url("buy")."' class='btn btn-outline-primary'>View All Property Listings</a>";
			$html[] = "</p>";
			
		$html[] = "</div>";
	$html[] = "</div>";

	if($data['articles']) {
		$html[] = "<div class='pt-5 mb-4 home-articles'>";
			$html[] = "<div class='container'>";
				$html[] = "<div class='text-center mb-4 p-5'>";
					$html[] = "<h2 class='fs-32'>Real Estate Articles</h2>";
					$html[] = "<p>Stay informed with expert insights, market trends, and tips for buying, selling, and investing in real estate.</p>";
				$html[] = "</div>";
				$html[] = "<div class='row'>";

				for($i=0; $i<count($data['articles']); $i++) {
					$html[] = "<div class='col-12 col-md-3 col-lg-3 col-xl-3 mb-3'>";

						$html[] = "<div class='card'>";
							$html[] = "<div class='p-image img-responsive img-responsive-21x9 card-img-top bg-primary-lt' style='height:200px; background-image: url(".$data['articles'][$i]['banner'].");'></div>";
							$html[] = "<div class='card-body mb-0 pb-2'>";
								$html[] = "<div class='p-description' style='height:90px;'>";
									$html[] = "<h3 class='card-title'>".$data['articles'][$i]['short_title']."</h3>";
									$html[] = "<p class='card-text text-muted fs-14'>".$data['articles'][$i]['short_desc']."</p>";
								$html[] = "</div>";
							$html[] = "</div>";
							$html[] = "<div class='card-footer pt-0 mt-0 border-0'>";
								$html[] = "<a href='".url("web.view.article", [ 'name' => $data['articles'][$i]['name'], 'id' => $data['articles'][$i]['article_id'] ])."' class='stretched-link w-100' title='".$data['articles'][$i]['title']."'></a>";
							$html[] = "</div>";
						$html[] = "</div>";

					$html[] = "</div>";
				}
				$html[] = "</div>";
				
				$html[] = "<div class='text-center'>";
					$html[] = "<a href='".url("web.articles")."' class='btn btn-outline-primary btn-md mt-5'>View all articles</a>";
				$html[] = "</div>";
				
				
			$html[] = "</div>";
		$html[] = "</div>";
	}

	$html[] = "<div class='mt-5 py-5 border-top bg-light'>";
		$html[] = "<div class='container'>";
			$html[] = "<div class='row justify-content-center'>";
				$html[] = "<div class='col-md-4 col-12'>";
					$html[] = "<div class='text-center mb-4'>";
						$html[] = "<h2 class='fs-32'>Let us help you</h2>";
						$html[] = "<p>Use the form below to send us a message.</p>";
					$html[] = "</div>";

					$html[] = View::include("contact_form");
				$html[] = "</div>";
			$html[] = "</div>";
		$html[] = "</div>";
	$html[] = "</div>";

	/* $html[] = "<div class='pt-5 mb-4'>";
		$html[] = "<div class='container'>";
			$html[] = "<h2 class='text-center mb-4 p-5'>Latest Videos</h2>";
			$html[] = "<div class='row'>";
			
				if($data['videos']) {
					for($i=0; $i<count($data['videos']); $i++) {
						
						$url_metadata = explode("=",$data['videos'][$i]['url']);
						$yt_id = $url_metadata[1];
						
						$thumbnail = "http://i3.ytimg.com/vi/".$yt_id."/hqdefault.jpg";
						$embed = "https://www.youtube.com/embed/$yt_id";
						
						$html[] = "<div class='col-md-4'>";
							$html[] = "<div class='video-wrap text-center my-2' data-embed='$embed' style='cursor:pointer;'>";
								$html[] = "<div class='yt-thumb' style='background-image: url(\"$thumbnail\")'></div>";
								$html[] = "<span class='d-block my-2'>".$data['videos'][$i]['title']."</span>";
							$html[] = "</div>";
						$html[] = "</div>";
					
					}
				}
			
			$html[] = "</div>";
			
			$html[] = "<p class='text-center mt-3'>";
				$html[] = "<a href='".url("videos")."' class='btn btn-sm btn-outline-primary'>View All Videos</a>";
			$html[] = "</p>";
			
		$html[] = "</div>";
	$html[] = "</div>";

	$html[] = "<div class='pt-5 mb-4'>";
		$html[] = "<div class='container'>";
			$html[] = "<h2 class='text-center mb-4 p-5'>Latest Property For Sale</h2>";
			$html[] = "<div class='row'>";
			
				if($data['properties']) {
					for($i=0; $i<count($data['properties']); $i++) {
						
						$file = ROOT.DS."images/listings/".$data['properties'][$i]['thumb_img'];
						if(file_exists($file)) {
							$thumb_img = CDN."images/website/listings/".$data['properties'][$i]['thumb_img'];
						}else {
							$thumb_img = CDN."images/community-default-logo.png";
						}
						
						$html[] = "<div class='col-12 col-md-3 mb-3'>";
							$html[] = "<a href='".$data['properties'][$i]['url']."' title='".$data['properties'][$i]['title']."' class=' text-dark'>";
								$html[] = "<div class='image-container border' style=\"background-image:url('$thumb_img');\"></div>";
								$html[] = "<ul class='list-group'>";
									$html[] = "<li class='list-group-item'>".$data['properties'][$i]['title']."</li>";
									$html[] = "<li class='list-group-item '><span class='glyphicon glyphicon-map-marker'></span> ".$data['properties'][$i]['short_address']."</li>";
									$html[] = "<li class='list-group-item'><span class='font-weight-bold'>&#8369; ".$data['properties'][$i]['price_tag']."</span></li>";
								$html[] = "</ul>";
							$html[] = "</a>";
						$html[] = "</div>";
						
					}
				}
			
				
			$html[] = "</div>";
			
			$html[] = "<p class='text-center'>";
				$html[] = "<a href='".url("buy")."' class='btn btn-sm btn-outline-primary'>View All Property Listings</a>";
			$html[] = "</p>";
			
		$html[] = "</div>";
	$html[] = "</div>";

	$html[] = "<div class='pt-5 mb-4'>";
		$html[] = "<div class='container'>";
			$html[] = "<h2 class='text-center mb-4 p-5'>Best Places To Live In The Metro</h2>";
			$html[] = "<div class='row'>";
				
				$html[] = "<div class='col-md-6 mb-4'>";
					$html[] = "<a href='".url("popular-location","Pasig City")."'>";
						$html[] = "<div class='image-container' style=\"border:1px solid #e1e1e1; height:250px; background-position:bottom; background-image:url('".CDN."/images/website/popular_locations/pasig-city-cover.jpg');\" >";
							$html[] = "<span style='position: absolute; top: 45%; left: 50%; margin-left: -50px; border:2px solid #FFF; color:#FFF; background: rgba(57, 60, 67, 0.6); font-weight:bold; padding:10px;'>PASIG CITY</span>";
						$html[] = "</div>";
					$html[] = "</a>";
				$html[] = "</div>";
				
				$html[] = "<div class='col-md-6 mb-4'>";
					$html[] = "<a href='".url("popular-location","Quezon City")."'>";
						$html[] = "<div class='image-container' style=\"border:1px solid #e1e1e1; height:250px; background-position:bottom; background-image:url('".CDN."/images/website/popular_locations/quezon-city-cover.jpg');\" >";
							$html[] = "<span style='position: absolute; top: 45%; left: 50%; margin-left: -50px; border:2px solid #FFF; color:#FFF; background: rgba(57, 60, 67, 0.6); font-weight:bold; padding:10px;'>QUEZON CITY</span>";
						$html[] = "</div>";
					$html[] = "</a>";
				$html[] = "</div>";
				
				$html[] = "<div class='col-md-6 mb-4'>";
					$html[] = "<a href='".url("popular-location","Pasay City")."'>";
						$html[] = "<div class='image-container' style=\"border:1px solid #e1e1e1; height:250px; background-position:bottom; background-image:url('".CDN."/images/website/popular_locations/pasay-city-cover.jpg');\" >";
							$html[] = "<span style='position: absolute; top: 45%; left: 50%; margin-left: -50px; border:2px solid #FFF; color:#FFF; background: rgba(57, 60, 67, 0.6); font-weight:bold; padding:10px;'>PASAY CITY</span>";
						$html[] = "</div>";
					$html[] = "</a>";
				$html[] = "</div>";
				
				$html[] = "<div class='col-md-6 mb-4'>";
					$html[] = "<a href='".url("popular-location","Paranaque City")."'>";
						$html[] = "<div class='image-container' style=\"border:1px solid #e1e1e1; height:250px; background-position:bottom; background-image:url('".CDN."/images/website/popular_locations/paranaque-city-cover.jpg');\" >";
							$html[] = "<span style='position: absolute; top: 45%; left: 50%; margin-left: -50px; border:2px solid #FFF; color:#FFF; background: rgba(57, 60, 67, 0.6); font-weight:bold; padding:10px;'>".("PARAÑAQUE")." CITY</span>";
						$html[] = "</div>";
					$html[] = "</a>";
				$html[] = "</div>";
				
				
			$html[] = "</div>";
		$html[] = "</div>";
	$html[] = "</div>";

	$html[] = "<div class=''>";
		$html[] = "<div class='pb-5 mb-4'>";
			$html[] = "<div class='container'>";
				$html[] = "<h2 class='mb-4 pt-5'>Articles</h2>";
				
				if($data['articles']) {
					$html[] = "<div class='row'>";
					for($i=0; $i<count($data['articles']); $i++) {
						$html[] = "<div class='col-md-3'>";
							$html[] = "<a href='".url()."' style='text-decoration:none; color:inherit;' title='".$data['articles'][$i]['title']."'>";
								$html[] = "<div class='article-img-container mb-3' style=\"background:url(".CDN."/images/articles/".$data['articles'][$i]['banner'].") center no-repeat; background-size:cover; width:100%;height:200px;\"></div>";
								$html[] = "<p class=''>".$data['articles'][$i]['title']."</p>";
								$html[] = "<p class='text-muted'>".$data['articles'][$i]['content']."</p>";
							$html[] = "</a>";
						$html[] = "</div>";
					}
					$html[] = "</div>";
					
					$html[] = "<div class='text-center'>";
						$html[] = "<a href='".url("articles")."' class='btn btn-outline-primary btn-md mt-5'>View all articles</a>";
					$html[] = "</div>";
				}
				
			$html[] = "</div>";
		$html[] = "</div>";
	$html[] = "</div>";

	$html[] = "<div class='pt-3 pb-5 mb-4'>";
		$html[] = "<div class='container'>";
			$html[] = "<div class='row'>";
				$html[] = "<div class='col-12 col-md-6 m-auto'>";
				
					$html[] = "<h2 class='text-center'>Let us help you</h2>";
					$html[] = "<p class='text-center mb-5'>Use the form below to send us a message.</p>";
		
					$html[] = "<form id='sendInquiryMessage' action='' method='POST'>";
							
						$html[] = "<input type='hidden' name='url_send_message' id='url_send_message' value='".url("thankyou")."' />";
						$html[] = "<input type='hidden' name='task' id='task' value='message' />";
						$html[] = "<input type='hidden' name='to_email' id='to_email' value='info@philproperties.ph' />";
						$html[] = "<input type='hidden' name='user_id' id='user_id' value='2' />";
						$html[] = "<input type='hidden' name='date_added' id='date_added' value='".date("Y-m-d H:i:s",DATE_NOW)."' />";
						$html[] = "<input type='hidden' name='agent_name' id='agent_name' value='Philproperties' />";
					
						$html[] = "<div class='form-group'>";
							$html[] = "<label class='sr-only' for='fullname'>Fullname</label>";
							$html[] = "<div class='row'>";
								$html[] = "<div class='col-6'>";
									$html[] = "<input type='text' class='form-control' name='firstname'' id='firstname' value='' placeholder='Firstname' required />";
								$html[] = "</div>";
								$html[] = "<div class='col-6'>";
									$html[] = "<input type='text' class='form-control' name='lastname'' id='lastname' value='' placeholder='Lastname' required />";
								$html[] = "</div>";
							$html[] = "</div>";
						$html[] = "</div>";
						
						$html[] = "<div class='form-group'>";
							$html[] = "<label class='sr-only' for='contactno'>Contact Number</label>";
							$html[] = "<input type='text' class='form-control' name='phone' id='phone' value='' placeholder='Contact Number' required />";
						$html[] = "</div>";
						
						$html[] = "<div class='form-group'>";
							$html[] = "<label class='sr-only' for='email'>Email</label>";
							$html[] = "<input type='email' class='form-control' name='email' id='email' value='' placeholder='Email' required />";
						$html[] = "</div>";
						
						$html[] = "<div class='form-group'>";
							$html[] = "<label class='sr-only' for='message'>Message</label>";
							$html[] = "<textarea class='form-control' name='message' id='message' placeholder='message'></textarea>";
						$html[] = "</div>";
						
						$captcha = rand(999, 9999);
						
						$html[] = "<div class='form-group'>";
							$html[] = "<div class='text-center'>";
								$html[] = "<input type='hidden' name='sNumber'  id='sNumber' value='$captcha' />
								<p class='m-1'>Security Number: <span class='captcha'><b>$captcha</b></span><br/>
								Enter the security number below </p>";
								$html[] = "<input type='text' name='captcha' class='form-control' id='captcha' value='' placeholder='Enter Security Code' required autocomplete='off' />";
							$html[] = "</div>";
						$html[] = "</div>";
						
						$html[] = "<div class='response'></div>";
						
						$html[] = "<div class='text-center'>";
							$html[] = "<input type='submit' name='btn-send-message' id='' value='Send Message' class='btn btn-primary btn-send-message' />";
						$html[] = "</div>";
						
					$html[] = "</form>";
		
				$html[] = "</div>";
			$html[] = "</div>";
		$html[] = "</div>";
	$html[] = "</div>"; */
$html[] = "</div>";