<?php

namespace EO\Model;

use Pecee\Exceptions\InvalidArgumentException;
use EO\Interfaces\IModel as IModel;
use EO\Database\DataModel;
use EO\Model\Traits\LeadTrait;

class LeadGroupModel extends \EO\Model implements IModel
{
	protected $table = 'lead_groups';
	protected $primaryKey = 'lead_group_id';

	protected $properties = [
		"lead_group_id",
		"account_id",
		"name",
		"created_at"
	];
}
