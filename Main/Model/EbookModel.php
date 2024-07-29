<?php

namespace Main\Model;

use Main\Interfaces\IModel as IModel;

class EbookModel extends \Main\Model implements IModel
{

	function __construct() {
		$this->alias = "e";
		$this->table = "ebooks";
		$this->primary_key = "ebook_id";
		$this->init();
	}

}
