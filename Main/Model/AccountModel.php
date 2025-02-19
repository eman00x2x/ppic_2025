<?php

namespace EO\Model;

use Pecee\Exceptions\InvalidArgumentException;
use EO\Model;
use EO\Interfaces\IModel;

/**
 * Class AccountModel
 * This class represents the Account Model and implements IModel interface.
 */
class AccountModel extends Model implements IModel
{
	protected $table = 'accounts';
	protected $primaryKey = 'account_id';

	protected $properties = [
		"account_id",
		"photo",
		"names",
		"username",
		"password",
		"email",
		"mobile_number",
		"account_type",
		"status",
		"registered_at",
		"permissions"
	];

	public function properties()
	{
		return $this->hasMany(PropertyModel::class);
	}

	public static function columns() {
		return [
			"fields" => [
				"account_id" => "accounts.account_id", 
				"photo" => "accounts.photo",
				"names" => "accounts.names",
				"full_name" => [
					"raw" => "CONCAT(JSON_UNQUOTE(<accounts.names>->'$.firstname'), ' ', JSON_UNQUOTE(<accounts.names>->'$.lastname'))"
				],
				"username" => "accounts.username", 
				"email" => "accounts.email", 
				"mobile_number" => "accounts.mobile_number",
				"account_type" => "accounts.account_type",
				"status" => "accounts.status", 
				"registered_at" => "accounts.registered_at",
				"permissions" => "accounts.permissions"
			]
		];
	}

}