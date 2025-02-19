<?php

namespace EO\Facades;

use EO\Database\DataModel;

class DataModelFacade
{
	public static ?DataModel $dataModel;

	public static function setDataModel(DataModel $dataModel) 
	{
		self::$dataModel = $dataModel;
	}

	public static function init()
	{
		return new static();
	}

	/** DATA MODELS */
	public static function getCollections(array $request = []) 
	{
		return self::$dataModel->getCollections($request);
	}

	public static function getBy(string $name, string $value, array $conditions = []) 
	{
		return self::$dataModel->getBy($name, $value, $conditions);
	}

	public static function getById(int $id) 
	{
		return self::$dataModel->getById($id);
	}

	public static function getId($id) 
	{
		return self::$dataModel->getId($id);
	}

	public static function create($data): Void 
	{
		self::$dataModel->create($data);
	}

	public static function modify($data, $id = null): Void 
	{
		self::$dataModel->modify($data, $id);
	}

	public static function filter($request = false) 
	{
		return self::$dataModel->filter($request);
	}

	public static function sort($request, $sorting) 
	{
		return self::$dataModel->sort($request, $sorting);
	}

	public static function encrypt(string $data, $type = "bcrypt"): string 
	{
		return self::$dataModel->encrypt($data, $type);
	}

	public static function load(array $collections) 
	{
		return self::$dataModel->load($collections);
	}

	/** END DATA MODELS */

	/** QUERY BUILDERS */

	public static function raw(string $string) 
	{
		return self::$dataModel->raw($string);
	}

	public static function execute($query) 
	{
		return self::$dataModel->execute($query);
	}

	/** SELECT STATMENT */

	public static function table(string $name) 
	{
		return self::$dataModel->table($name);
	}

	public static function select(mixed $columns) 
	{
		return self::$dataModel->select($columns);
	}

	public static function join(string $table, string $table_key_id, string $other_key_id = null) 
	{
		return self::$dataModel->join($table, $table_key_id, $other_key_id);
	}

	public static function rightJoin(string $table, string $table_key_id, string $other_key_id = null) 
	{
		return self::$dataModel->rightJoin($table, $table_key_id, $other_key_id);
	}

	public static function fullJoin(string $table, string $table_key_id,string  $other_key_id = null) 
	{
		return self::$dataModel->fullJoin($table, $table_key_id, $other_key_id);
	}

	public static function innerJoin(string $table, string $table_key_id, string $other_key_id = null) 
	{
		return self::$dataModel->innerJoin($table, $table_key_id, $other_key_id);
	}

	public static function where(array $conditions) 
	{
		return self::$dataModel->where($conditions);
	}

	public static function groupBy(array $group_by) 
	{
		return self::$dataModel->groupBy($group_by);
	}

	public static function orderBy(array $order_by) 
	{
		return self::$dataModel->orderBy($order_by);
	}

	public static function limit(int $limit = 20) 
	{
		return self::$dataModel->limit($limit);
	}

	public static function get(int $page = 1): array 
	{
		return self::$dataModel->get($page);
	}

	/** END SELECT STATMENT */

	public static function insert(array $data): int 
	{
		return self::$dataModel->insert($data);
	}

	public static function update(array $data, array $conditions): Void 
	{
		self::$dataModel->update($data, $conditions);
	}

	public static function delete(array $conditions): Void 
	{
		self::$dataModel->delete($conditions);
	}

	/** END QUERY BUILDERS */

	/** QUERY BUILDER UTILITIES */

	public static function setResults(array $results) 
	{
		return self::$dataModel->setResults($results);
	}

	public static function getResults(): array 
	{
		return self::$dataModel->getResults();
	}

	public static function getNumRows(): int 
	{
		return self::$dataModel->getNumRows();
	}

	public static function setNumRows(int $row) 
	{
		return self::$dataModel->setNumRows($row);
	}

	public static function getTotalNumPage(): int 
	{
		return self::$dataModel->getTotalNumPage();
	}

	public static function getItemStartingNumber(): int 
	{
		return self::$dataModel->getItemStartingNumber();
	}

	public static function getPagination(): array 
	{
		return self::$dataModel->getPagination();
	}

	public static function getPrimaryKey(): string 
	{
		return self::$dataModel->getPrimaryKey();
	}

	public static function getTableFields(): array 
	{
		return self::$dataModel->getTableFields();
	}

	public static function initializeModel(string $model) 
	{
		return self::$dataModel->initializeModel($model);
	}

	public static function setCurrentModelObject(string $model) 
	{
		return self::$dataModel->setCurrentModelObject($model);
	}

	public static function getModelObject() 
	{
		return self::$dataModel->getModelObject();
	}

	public function checkTableIfExists($table) 
	{
		return self::$dataModel->checkTableIfExists($table);
	}

	/** END QUERY BUILDER UTILITIES */
}