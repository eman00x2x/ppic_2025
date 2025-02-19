<?php

namespace EO\Facades;

class ModelFacade
{
	public static $tableModel = [];
	
	public static function get($model) {
		self::$tableModel[$model] = new $model();
		return self::$tableModel[$model];
	}
}