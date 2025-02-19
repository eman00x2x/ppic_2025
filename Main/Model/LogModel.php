<?php

namespace EO\Model;

use Pecee\Exceptions\InvalidArgumentException;
use EO\Interfaces\IModel as IModel;
use EO\Database\DataModel;
use EO\Model\Traits\LogsTrait;

class LogModel extends \EO\Model implements IModel
{
	protected $table = 'logs';
	protected $primaryKey = 'log_id';

	protected $properties = [
		"log_id",
		"channel",
		"level",
		"message",
		"time"
	];
}
