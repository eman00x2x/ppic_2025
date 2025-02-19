<?php

namespace EO\Http\Controllers\Website;

use EO\View;
use EO\Http\BaseController;

class PagesController extends BaseController
{
	function about() {
		$data['about'] = CONFIG['about'];
		return View::set("/website/about/about.php")->bind(data: $data);
	}

	function contact() {
		return View::set("/website/contact/contact.php");
	}

	function terms() {
		return View::set("/website/legal/terms.php");
	}

	function privacy() {
		return View::set("/website/legal/privacy.php");
	}
	
}