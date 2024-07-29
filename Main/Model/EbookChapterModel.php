<?php

namespace Main\Model;

use Main\Interfaces\IModel as IModel;

class EbookChapterModel extends \Main\Model implements IModel
{

	function __construct() {
		$this->alias = "ec";
		$this->table = "ebook_chapters";
		$this->primary_key = "ebook_chapter_id";
		$this->init();
	}

}
