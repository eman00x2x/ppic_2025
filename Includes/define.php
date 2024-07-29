<?php

defined("ACCESS")or die("Restricted page!");

date_default_timezone_set("Asia/Manila");

define("DATE_NOW",strtotime("Now"));
define("LIST_LIMIT",20);
define("DEVELOPMENT", true);

define("ROOT","D:/wamp64/www/sales-training-system");
define("DS",DIRECTORY_SEPARATOR);

define("SESSION_SAVE_PATH", ROOT."/Public/Sessions");
ini_set('session.save_path', SESSION_SAVE_PATH);

if(DEVELOPMENT === true) {
	define("ADMIN_ALIAS","/sales-training-system/Admin");
	define("MANAGE_ALIAS","/sales-training-system/Manage");
	define("WEB_ALIAS","/sales-training-system/Website");
}else {
	define("ADMIN_ALIAS","");
	define("MANAGE_ALIAS","");
	define("WEB_ALIAS","");
}

define("DOMAIN",	"http://localhost");
define("CDN",		DOMAIN."/sales-training-system/Cdn/");
define("WEBDOMAIN",	DOMAIN."/sales-training-system/Website/");
define("ADMIN",		DOMAIN."/sales-training-system/Admin/");
define("MANAGE",	DOMAIN."/sales-training-system/Manage/");


define("CONFIG", [
    "site_name" => "MLS",
    "email_address_responder" => [
        "email" => "email",
        "password" => "pwd",
        "host" => "host",
        "port" => "port"
    ],
    "enable_premium" => false,
    "show_vat" => false,
    "analytics" => "",
    "header_script" => "",
    "websocket" => [
        "ip_address" => "127.0.0.1",
        "port" => "8080"
    ]
]);

/* define("SITE_NAME", CONFIG['site_name']); */
define("SITE_NAME", CONFIG['site_name']);

/** Set the Email Address to use by the system to send email notifications to users */
define("EMAIL_ADDRESS_RESPONDER", CONFIG['email_address_responder']);

/** Enable premium set this to true */
define("PREMIUM", CONFIG['enable_premium']);

/** if you want to include vat computation in INVOICE set this to true */
define("VAT", CONFIG['show_vat']);

/** API CREDENTIALS */

$credential = require_once(ROOT . "/credentials");

/** PAYPAL */
define("PAYPAL_CLIENT_ID", $credential['PAYPAL']['client_id']);
define("PAYPAL_CLIENT_SECRET", $credential['PAYPAL']['client_secret']);
define("PAYPAL_ENVIRONMENT", "sandbox");

/** XENDIT */
define("XENDIT_API_KEY", $credential['XENDIT']['api_key']);

define("CURRENCY", "PHP");

/** ANALYTICS AND CUSTOM META TAGS */
define("ANALYTICS", CONFIG['analytics']);
define("HEADER_SCRIPT", CONFIG['header_script']);

define("VRSN","v1.0");

if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	define("IS_AJAX",true);
}else {
	define("IS_AJAX",false);
}