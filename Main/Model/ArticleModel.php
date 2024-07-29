<?php

namespace Main\Model;

use Main\Interfaces\IModel as IModel;

class ArticleModel extends \Main\Model implements IModel
{

	function __construct() {
		$this->alias = "ar";
		$this->table = "articles";
		$this->primary_key = "article_id";
		$this->init();
	}

}
