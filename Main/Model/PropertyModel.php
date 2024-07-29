<?php

namespace Main\Model;

use Main\Interfaces\IModel as IModel;

class PropertyModel extends \Main\Model implements IModel
{

	function __construct() {
		$this->alias = "pr";
		$this->table = "properties";
		$this->primary_key = "property_id";
		$this->init();
	}

}
