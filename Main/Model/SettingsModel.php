<?php

namespace EO\Model;

use Pecee\Exceptions\InvalidArgumentException;
use EO\Interfaces\IModel as IModel;

/**
 * Class SettingsModel
 * This class represents the Account Model and implements IModel interface.
 */
class SettingsModel extends \EO\Model implements IModel 
{
	protected $table = 'settings';
	protected $primaryKey = 'id';

	protected $properties = [
		"id",
		"site_name",
		"is_maintenance",
		"contact_info",
		"show_vat",
		"email_address_responder",
		"enable_kyc_verification",
		"enable_premium",
		"privileges",
		"analytics",
		"header_script",
		"data_privacy",
		"terms",
		"about",
		"refund_policy",
		"community_guidelines",
		"modified_at"
	];
}