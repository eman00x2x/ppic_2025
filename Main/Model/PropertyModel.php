<?php

namespace EO\Model;

use Pecee\Exceptions\InvalidArgumentException;
use EO\Model\PropertyImageModel;
use EO\Interfaces\IModel;

class PropertyModel extends \EO\Model implements IModel
{
	protected $table = 'properties';
	protected $primaryKey = 'property_id';

	protected $properties = [
		"property_id",
		"account_id",
		"is_mls",
		"is_website",
		"featured",
		"listing_type",
		"property_type",
		"foreclosure",
		"service_type",
		"name",
		"title",
		"tags",
		"long_desc",
		"category",
		"address",
		"price",
		"reservation",
		"payment_details",
		"floor_area",
		"lot_area",
		"unit_area",
		"bedroom",
		"bathroom",
		"parking",
		"thumb_img",
		"videos",
		"amenities",
		"other_details",
		"documents",
		"created_at",
		"modified_at",
		"status",
		"duration",
		"post_score",
		"documents",
		"display"
	];

	public function images()
	{
		return $this->hasMany(PropertyImageModel::class);
	}

	public static function columns() 
	{
		return [
			"fields" => [
				"property_id" => "properties.property_id", 
				"name" => "properties.name", 
				"title" => "properties.title",
				"address" => "properties.address", 
				"short_address" => [
					"raw" => "CONCAT(JSON_UNQUOTE(<properties.address>->'$.barangay'), ' ', JSON_UNQUOTE(<properties.address>->'$.municipality'), ' ', JSON_UNQUOTE(<properties.address>->'$.province'))"
				],
				"listing_type" => "properties.listing_type",
				"property_type " => "properties.property_type ", 
				"foreclosure " => "properties.foreclosure ", 
				"category" => "properties.category",
				"bedroom" => "properties.bedroom", 
				"bathroom" => "properties.bathroom", 
				"floor_area" => "properties.floor_area", 
				"lot_area" => "properties.lot_area",
				"parking" => "properties.parking",
				"reservation" => "properties.reservation",
				"price" => "properties.price",
				"status" => "properties.status",
				"availability" => [
					"raw" => "CASE WHEN " . $_ENV['DB_PREFIX'] . "properties.status = 1 THEN 'Available'
						WHEN " . $_ENV['DB_PREFIX'] . "properties.status = 2 THEN 'Sold'
						WHEN " . $_ENV['DB_PREFIX'] . "properties.status = 3 THEN 'Removed' END"
				],
				"total_images" => [
					"raw" => "(SELECT COUNT(*) FROM " . $_ENV['DB_PREFIX'] . "property_images WHERE property_id = " . $_ENV['DB_PREFIX'] . "properties.property_id)",
				],
				"created_at" => "properties.created_at",
				"modified_at" => "properties.modified_at",
				"thumb_img" => "properties.thumb_img",
				"account" => AccountModel::columns()
			],
			"join" => [
				"accounts" => ["account_id", "account_id"]
			]
		];
	}

	public static function columnsFull() 
	{
		return [
			"fields" => [
				"property_id" => "properties.property_id",
				"account_id" => "properties.account_id",
				"featured" => "properties.featured",
				"listing_type" => "properties.listing_type",
				"property_type " => "properties.property_type ",
				"foreclosure" => "properties.foreclosure",
				"service_type" => "properties.service_type",
				"name" => "properties.name",
				"title" => "properties.title",
				"tags" => "properties.tags",
				"long_desc" => "properties.long_desc",
				"category" => "properties.category",
				"address" => "properties.address",
				"short_address" => [
					"raw" => "CONCAT(JSON_UNQUOTE(<properties.address>->'$.barangay'), ' ', JSON_UNQUOTE(<properties.address>->'$.municipality'), ' ', JSON_UNQUOTE(<properties.address>->'$.province'))"
				],
				"complete_address" => [
					"raw" => "CONCAT(JSON_UNQUOTE(<properties.address>->'$.village'), ' ', JSON_UNQUOTE(<properties.address>->'$.barangay'), ' ', JSON_UNQUOTE(<properties.address>->'$.municipality'), ' ', JSON_UNQUOTE(<properties.address>->'$.province'))"
				],
				"price" => "properties.price",
				"reservation" => "properties.reservation",
				"payment_details" => "properties.payment_details",
				"floor_area" => "properties.floor_area",
				"lot_area" => "properties.lot_area",
				"bedroom" => "properties.bedroom",
				"bathroom" => "properties.bathroom",
				"parking" => "properties.parking",
				"thumb_img" => "properties.thumb_img",
				"videos" => "properties.videos",
				"amenities" => "properties.amenities",
				"other_details" => "properties.other_details",
				"documents" => "properties.documents",
				"status" => "properties.status",
				"availability" => [
					"raw" => "CASE WHEN " . $_ENV['DB_PREFIX'] . "properties.status = 1 THEN 'Available'
						WHEN " . $_ENV['DB_PREFIX'] . "properties.status = 2 THEN 'Sold'
						WHEN " . $_ENV['DB_PREFIX'] . "properties.status = 3 THEN 'Removed' END"
				],
				"duration" => "properties.duration",
				"post_score" => "properties.post_score",
				"display" => "properties.display",
				"created_at" => "properties.created_at",
				"modified_at" => "properties.modified_at",
				"total_images" => [
					"raw" => "(SELECT COUNT(*) FROM " . $_ENV['DB_PREFIX'] . "property_images WHERE property_id = " . $_ENV['DB_PREFIX'] . "properties.property_id)",
				],
				/* "images" => [
					"raw" => "
					(SELECT CONCAT('[', images, ']') AS images FROM 
					( 
						SELECT GROUP_CONCAT('{', my_json, '}' SEPARATOR ',') AS images FROM
						(
							SELECT CONCAT(
								'\"image_id\": ', '\"', image_id, '\"',  
								',\"filename\": ', '\"', filename, '\"',
								',\"width\": ', width,
								',\"height\": ', height,
								',\"url\": ', '\"', url, '\"'
							) AS my_json FROM " . $_ENV['DB_PREFIX'] . "property_images WHERE property_id = " . $_ENV['DB_PREFIX'] . "properties.property_id LIMIT 1000
						) AS more_json
					) AS yet_more_json)",
				], */
				"account" => AccountModel::columns()
			],
			"join" => [
				"accounts" => ["account_id", "account_id"]
			]
		];
	}
}
