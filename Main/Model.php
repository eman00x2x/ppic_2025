<?php

namespace EO;

use EO\Facades\DataModelFacade as DataModel;
use EO\Interfaces\IModel;
use EO\Database\Collections;
use EO\Database\Relation\HasMany;

use EO\Database\DBModel;

class Model
{
	protected static array $DataModel;
	private $hasMany;
	public $attributes;

	public function __construct($attributes = [])
	{
		$this->attributes = $attributes;
	}

	public function __get($key)
	{
		return $this->attributes[$key] ?? null;
	}

	public function __set($key, $value)
	{
		$this->attributes[$key] = $value;
	}

	public static function __callStatic($method, $parameters)
	{
		return self::model()->$method(...$parameters);
	}

	private static function model(): DataModel
	{
		$data_model_instance = DataModel::init();
		$data_model_instance->initializeModel(static::class);
		return $data_model_instance;
	}

	public function toArray() {
		return $this->attributes;
	}

	/**
	 * Define a one-to-many relationship.
	 *
	 * @param string $related The related model class name.
	 * @param string|null $foreign_key The foreign key in the related model. Defaults to the primary key of the related model.
	 * @param string|null $localKey The local key in the current model. Defaults to the primary key of the current model.
	 * @return EO\Database\Collections The collection of related model instances.
	 */
	public function hasMany($related, $foreign_key = null)
    {
       /*  $instance = new $related();
		$localKey = $localKey ?: $instance::getPrimaryKey();
        $foreign_key = $foreign_key ?: self::getPrimaryKey();
        
		$collections = $instance::getBy($foreign_key, $this->$foreign_key);
		
		return $collections; */

		$model = self::getDataModelInstance();
		$model->setModelObject($related);

		$this->hasMany = $model;

		$foreign_key = $foreign_key ?: self::getPrimaryKey();
		return new HasMany($model, $this, $foreign_key);
    }

}