<?php

$html[] = "<div class='mt-2'>";
	$html[] = "<div class='d-flex'>";

		$html[] = "<div class='bg-white p-1 ps-2 rounded-start-2'>";
			$html[] = "<span class='text-dark fs-11 fw-bold text-muted'>Share: </span>";
		$html[] = "</div>";

		/** Linkedin */
		$html[] = "<div class='bg-white p-1'>";
			$html[] = "<a href='https://www.linkedin.com/shareArticle?mini=true&url=".$data['url']."' target='_blank' title='Share to Linkedin'>";
				$html[] = "<img src='".CDN."/images/social/linkedin.png' style='width: 18px;' alt='Linkedin' />";
			$html[] = "</a>";
		$html[] = "</div>";
		
		/** Facebook */
		$html[] = "<div class='bg-white p-1'>";
			$html[] = "<a href='http://www.facebook.com/sharer.php?u=".$data['url']."' target='_blank' title='Share to Facebook'>";
				$html[] = "<img src='".CDN."/images/social/facebook.png' style='width: 18px;' alt='Facebook'>";
			$html[] = "</a>";
		$html[] = "</div>";

		/** Twitter */
		$html[] = "<div class='bg-white p-1'>";
			$html[] = "<a href='http://twitter.com/share?url=".$data['url']."' target='_blank' title='Share to Twitter'>";
				$html[] = "<img src='".CDN."/images/social/twitter.png' style='width: 18px;' alt='Twitter' />";
			$html[] = "</a>";
		$html[] = "</div>";
		
		/** Whatsapp */
		$html[] = "<div class='bg-white p-1'>";
			$html[] = "<a href='whatsapp://send?text=".$data['url']."' target='_blank' title='Share to Whatsapp'>";
				$html[] = "<img src='".CDN."/images/social/whatsapp.png'  style='width: 18px;' alt='Whatsapp' />";
			$html[] = "</a>";
		$html[] = "</div>";
		
		/** Telegram */
		$html[] = "<div class='bg-white p-1'>";
			$html[] = "<a href='https://t.me/share/url?url=".$data['url']."' target='_blank' title='Share to Telegram'>";
				$html[] = "<img src='".CDN."/images/social/telegram.png'  style='width: 18px;' alt='Telegram' />";
			$html[] = "</a>";
		$html[] = "</div>";

		/** Email */
		$html[] = "<div class='bg-white p-1 pe-2 rounded-end-2'>";
			$html[] = "<a href='mailto:?subject=Check out this article&body=".$data['url']."' target='_blank' title='Share to Email'>";
				$html[] = "<img src='".CDN."/images/social/email.png'  style='width: 18px;' alt='Email' />";
			$html[] = "</a>";
		$html[] = "</div>";

	$html[] = "</div>";
$html[] = "</div>";