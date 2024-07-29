<?php

namespace Main\Model;

use Main\Interfaces\IModel as IModel;

class VideoModel extends \Main\Model implements IModel
{

	function __construct() {
		$this->alias = "v";
		$this->table = "videos";
		$this->primary_key = "video_id";
		$this->init();
	}

}
