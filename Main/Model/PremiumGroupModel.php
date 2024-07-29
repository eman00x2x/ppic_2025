<?php

namespace Main\Model;

use Main\Interfaces\IModel as IModel;

class PremiumGroupModel extends \Main\Model implements IModel
{

	function __construct() {
		$this->alias = "eg";
		$this->table = "premium_groups";
		$this->primary_key = "premium_group_id";
		$this->init();
	}

}
