<?php

namespace EO\Model;

use Pecee\Exceptions\InvalidArgumentException;
use EO\Interfaces\IModel;
use EO\Model;
use EO\Model\AccountModel;

class LoginModel extends Model implements IModel 
{
	protected $table = 'logins';
	protected $primaryKey = 'login_id';

	public static $properties = [
		"login_id",
		"session_id",
		"status",
		"account_id",
		"login_details",
		"login_at"
	];

	public static function columns() {
		return [
			"fields" => [
      			"id" => "logins.account_id",
				"login_id" => "logins.login_id", 
				"session_id" => "logins.session_id", 
				"login_status" => "logins.status",
				"login_details" => "logins.login_details",
				"login_at" => "logins.login_at",
				"account" => AccountModel::columns()
			],
			"join" => [
				"accounts" => ["account_id", "account_id"]
			]
		];
	}
}
