<?php

namespace Main\Model;

use Main\Interfaces\IModel as IModel;

class ProfileModel extends \Main\Model implements IModel
{

	function __construct() {
		$this->alias = "al";
		$this->table = "accounts_profile";
		$this->primary_key = "profile_id";
		$this->init();
	}

}
