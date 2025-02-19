<?php

namespace EO\Model;

use Pecee\Exceptions\InvalidArgumentException;
use EO\Interfaces\IModel as IModel;
use EO\Database\DataModel;
use EO\Model\Traits\PropertyImageTrait;

class PropertyImageModel extends \EO\Model implements IModel
{
	protected $table = 'property_images';
	protected $primaryKey = 'image_id';

	protected $properties = [
		"image_id",
		"property_id",
		"filename",
		"width",
		"height",
		"url",
		"created_at"
	];
}
