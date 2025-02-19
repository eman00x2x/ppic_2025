<?php

namespace EO\Model;

use Pecee\Exceptions\InvalidArgumentException;
use EO\Interfaces\IModel as IModel;
use EO\Database\DataModel;
use EO\Model\Traits\LeadTrait;

class LeadModel extends \EO\Model implements IModel
{
	protected $table = 'leads';
	protected $primaryKey = 'lead_id';

	protected $properties = [
		"lead_id",
		"account_id",
		"lead_group_id",
		"name",
		"email",
		"contact_number",
		"message",
		"source",
		"reference",
		"requirements",
		"label",
		"description",
		"created_at"
	];

	public static function columns()
	{
		return [
			"fields" => [
				"lead_id" => "leads.lead_id",
				"account_id" => "leads.account_id",
				"lead_group" => [
					"fields" => [
						"lead_group_id" => "lead_groups.lead_group_id",
						"name" => "lead_groups.name",
					]
				],
				"name" => "leads.name",
				"email" => "leads.email",
				"contact_number" => "leads.contact_number",
				"message" => "leads.message",
				"source" => "leads.source",
				"reference" => "leads.reference",
				"requirements" => "leads.requirements",
				"label" => "leads.label",
				"description" => "leads.description",
				"created_at" => "leads.created_at",
				"account" => AccountModel::columns()
			],
			"join" => [
				"accounts" => ["account_id", "account_id"],
				"lead_groups" => ["lead_group_id", "lead_group_id"]
			]
		];
	}
}
