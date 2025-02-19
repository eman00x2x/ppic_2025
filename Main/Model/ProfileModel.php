<?php

namespace EO\Model;

use EO\Interfaces\IModel as IModel;
use EO\Database\DataModel;
use EO\Model\Traits\ProfileTrait;

class ProfileModel extends \EO\Model implements IModel
{
	use ProfileTrait;

	public static $profileColumns;
	public static DataModel $model;

	function __construct() {
		parent::__construct();
		self::$model = $this->initialized( "accounts_profile", "profile_id" );
		self::$model->setProperties(self::$properties);
	}

	public static function setColumns() {
		self::$profileColumns = self::getColumns();
	}

	public static function model(): DataModel {
		return self::$model;
	}

	public static function columns() {
		self::setColumns();
		return self::$profileColumns;
	}

}
