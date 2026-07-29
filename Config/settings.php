<?php

namespace Config;

use EO\Services\SettingsService;

final class Settings
{
	private SettingsService $settingsService;

	public function __construct() 
	{ 
		$this->settingsService = new SettingsService();
	}

	function initialize()
	{
		$result = $this->settingsService->getSettings();
		// $result = $this->defaultSettings();

		define("CONFIG", $result);

		define("MAINTENANCE", (CONFIG['is_maintenance'] == 1 ? true : false));

		/** Set the site name */
		define("SITE_NAME", CONFIG['site_name']);

		/** Set the Email Address to use by the system to send email notifications to users */
		define("EMAIL_ADDRESS_RESPONDER", CONFIG['email_address_responder']);

		/** ANALYTICS AND CUSTOM META TAGS */
		define("ANALYTICS", CONFIG['analytics']);
		define("HEADER_SCRIPT", CONFIG['header_script']);

		/** PAYPAL */
		define("PAYPAL_CLIENT_ID", $_ENV['PAYPAL_CLIENT_ID']);
		define("PAYPAL_CLIENT_SECRET", $_ENV['PAYPAL_CLIENT_SECRET']);
		define("PAYPAL_ENVIRONMENT", $_ENV['PAYPAL_ENVIRONMENT']);

		/** XENDIT */
		define("XENDIT_API_KEY", $_ENV['XENDIT_API_KEY']);

		define("CURRENCY", "PHP");
	}
	
	function defaultSettings() {

		return [
			"site_name" => "Philproperties International Corp.",
			"is_maintenance" => 0,
			"contact_info" => [
    "mobile_number" => "09175223499",
    "email" => "info@philproperties.ph",
    "office_address" =>
        "Suite 932 Mega Plaza Bldg. ADB Avenue, Ortigas Center Pasig City",
    "contact_page_text" =>
        "Donec a lobortis diam. Sed eu accumsan lectus. Nunc viverra eros non dui euismod interdum viverra vitae libero. Vestibulum fringilla, eros id volutpat mattis, ipsum ipsum elementum elit, quis posuere erat nisl ac augue. Etiam nec vehicula massa. Donec eget eros non tellus suscipit lobortis. Pellentesque dapibus ante augue, sed luctus nunc laoreet vel.",
],
			"show_vat" => 0,
			"email_address_responder" => [
    "email" => "noreply@philproperties.ph",
    "password" => "))wby$6#bEc*",
    "host" => "mail.philproperties.ph",
    "port" => "587",
],
			"privileges" => [
    "max_post" => "15",
    "max_users" => "2",
    "mls_access" => "1",
    "chat_access" => "1",
    "featured_ads" => "0",
    "handshake_limit" => "1",
    "comparative_analysis_access" => "0",
],
			"terms" => "",
			"about" => "",
			"refund_policy" => "",
			"community_guidelines" => "",
			"analytics" => "",
			"header_script" => ""
		];

	}

}



