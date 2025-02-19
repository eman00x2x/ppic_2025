<?php

namespace EO\Model;

use Pecee\Exceptions\InvalidArgumentException;
use EO\Interfaces\IModel as IModel;
use EO\Model\AccountModel;

class TrafficModel extends \EO\Model implements IModel
{
	protected $table = 'traffics';
	protected $primaryKey = 'traffic_id';

	protected $properties = [
		"traffic_id",
		"account_id",
		"session_id",
		"traffic",
		"user_agent",
		"created_at"
	];

	public static function columns() {
		return [
			"fields" => [
				"traffic_id" => "traffics.traffic_id",
				"url" => [
					"raw" => "JSON_UNQUOTE(<traffics.traffic>->'$.url')"
				],
				"name" => [
					"raw" => "JSON_UNQUOTE(<traffics.traffic>->'$.name')"
				],
				"ip" => [
					"raw" => "JSON_UNQUOTE(<traffics.user_agent>->'$.geo.ip')"	
				],
				"geo" => [
					"raw" => "CONCAT(JSON_UNQUOTE(<traffics.user_agent>->'$.geo.city'), ' ', JSON_UNQUOTE(<traffics.user_agent>->'$.geo.region'), ' ', JSON_UNQUOTE(<traffics.user_agent>->'$.geo.country'))"	
				],
				"browser" => [
					"raw" => "JSON_UNQUOTE(<traffics.user_agent>->'$.browser')"	
				],
				"created_at" => "traffics.created_at",
				"created_date" => [
					"raw" => "FROM_UNIXTIME(created_at)",
				],
				"account" => AccountModel::columns()
			],
			"join" => [
				"accounts" => ["account_id", "account_id"]
			]
		];
	}
}
