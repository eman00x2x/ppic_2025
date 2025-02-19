<?php

namespace EO\Database\Relation;

use EO\Database\DataModel;
use EO\Database\Collections;
use EO\Interfaces\IModel;

class HasMany
{
	private Imodel $parent;
	private DataModel $query;
	private $foreignKey;
	private $localKey;

	/**
	 * Create a new has many relationship instance.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @param  \Illuminate\Database\Eloquent\Model  $parent
	 * @param  string  $foreignKey
	 * @param  string  $localKey
	 * @return void
	 */
	public function __construct(DataModel $query, Imodel $parent, $foreignKey)
	{
		$this->query = $query;
		$this->parent = $parent;

		$this->foreignKey = $foreignKey;
		$this->localKey = $this->query->getPrimaryKey();
	}

	/**
	 * Get the results of the relationship.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getResults($conditions = [])
	{
		$conditions[$this->foreignKey] = $this->parent->{$this->foreignKey};
		return $this->query->getCollections($conditions);
	}

	public function getRelated()
	{
		return $this->parent;
	}

	/**
	 * Get the foreign key for the relationship.
	 *
	 * @return string
	 */
	public function getForeignKey()
	{
		return $this->foreignKey;
	}

	/**
	 * Get the local key for the relationship.
	 *
	 * @return string
	 */
	public function getLocalKey()
	{
		return $this->localKey;
	}

	public function destroy()
	{
		$parentPrimaryKey = $this->parent::getPrimaryKey();
		return $this->query->delete([$this->foreignKey => $this->parent->{$parentPrimaryKey}]);
	}
}