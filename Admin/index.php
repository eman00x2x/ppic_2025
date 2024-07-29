<?php

use Pecee\SimpleRouter\SimpleRouter as Router;

if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();

define("BASE",dirname(__FILE__));
define("ACCESS", 1);

require_once("../Includes/define.php");
require_once("../Includes/functions.php");
require_once(ROOT . "/Vendor/autoload.php");
require_once(ROOT . "/Vendor/pecee/simple-router/helpers.php");

spl_autoload_register("autoloader");

require_once(ROOT . "/Resources/routes.php");

Router::enableMultiRouteRendering(false);
$content = Router::start();

if(IS_AJAX === true) {
	echo $content;
}else {

	/* if(request()->template == "") {
		request()->template = "Templates/login.template.php";
	} */

	require_once(request()->template);
	
}

ob_flush();
flush();