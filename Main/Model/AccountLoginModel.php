<?php

namespace Main\Model;

use Main\Interfaces\IModel as IModel;

class AccountLoginModel extends \Main\Model implements IModel 
{

	function __construct() {
		$this->alias = "al";
		$this->table = "accounts_login";
		$this->primary_key = "account_login_id";
		$this->init();
	}

}
