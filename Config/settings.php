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

}



