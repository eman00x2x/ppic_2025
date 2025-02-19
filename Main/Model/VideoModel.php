<?php

namespace EO\Model;

use Pecee\Exceptions\InvalidArgumentException;
use EO\Interfaces\IModel as IModel;

class VideoModel extends \EO\Model implements IModel 
{
	protected $table = 'videos';
	protected $primaryKey = 'video_id';

	protected $properties = [
		"video_id",
		"unique_id",
		"category",
		"thumbnail",
		"url",
		"embed",
		"created_at"
	];
}