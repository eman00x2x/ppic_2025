<?php

namespace Main\Model;

class OrganizationModel extends \Main\Model {

    function __construct() {
		$this->alias = "o";
		$this->table = "organizations";
		$this->primary_key = "organization_id";
		$this->init();
	}

}