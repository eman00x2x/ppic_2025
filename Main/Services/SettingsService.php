<?php

namespace EO\Services;

use Pecee\Http\Exceptions\MalformedUrlException;
use EO\Handlers\Exceptions\ValidationException;
use EO\Handlers\Exceptions\ResourceNotFoundException;
use EO\Service as Service;
use EO\Model\SettingsModel as Settings;

class SettingsService extends Service
{
	private $settingsId = 1;

	function __construct() 
	{
		parent::__construct();
	}

	function getSettings(): array 
	{
		$result = Settings::checkTableIfExists('ppic_settings');

		if($result == "") {
			return $this->dummy();
		}

		self::$collections = Settings::getId($this->settingsId);
		$items = self::$collections->getItems();

		return $items->first()->toArray();
	}

	function create(array $data) {}

	function update(array $data) 
	{
		$data['show_vat'] = (isset($data['show_vat']) ? 1 : 0);
		$data['is_maintenance'] = (isset($data['is_maintenance']) ? 1 : 0);
		$data['enable_kyc_verification'] = (isset($data['enable_kyc_verification']) ? 1 : 0);
		$data['enable_premium'] = (isset($data['enable_premium']) ? 1 : 0);

		try{
			Settings::modify($data, $this->settingsId);
			$this->log([
				'type' => 'info',
				'message' => "Settings update succeeded",
				'data' => $data
			]);
		}catch(\Exception $e) {
			$this->log([
				"type" => "warning",
				"message" => "Settings update failed",
				"data" => [
					"error" => $e->getMessage(),
					"data" => $data
				]
			]);
			throw new \Exception($e->getMessage());
		}
			
		return $data;
	}

	function dummy()
	{
		return [
			"is_maintenance" => false,
			"site_name" => "STAR",
			"contact_info" => [
				"mobile_number" => "09175223499",
				"email" => "myorg@email.com",
				"office_address" => "Bambang",
				"contact_page_text" => ""
			],
			"analytics" => "",
			"header_script" => "",
			"data_privacy" => "",
			"terms" => "",
			"about" => "",
			"show_vat" => 1,
			"email_address_responder" => [
				"email" => "it@1premiereland.ph",
				"password" => "1Plmc7211",
				"host" => "mail.1premiereland.ph",
				"port" => "587"
			]
		];

	}

}