<?php

namespace EO\Facades;

use EO\Database\QueryBuilder;

class QueryBuilderFacade
{
	public static QueryBuilder $queryBuilder;

	public static function setQueryBuilder(QueryBuilder $queryBuilder) {
		self::$queryBuilder = $queryBuilder;
	}

	/** QUERY BUILDERS */

	public static function raw(string $string) 
	{
		self::hasInstance();
		return self::$queryBuilder->raw($string);
	}

	public static function execute($query) 
	{
		self::hasInstance();
		return self::$queryBuilder->execute($query);
	}

	public static function query($query) 
	{
		self::hasInstance();
		return self::$queryBuilder->query($query);
	}

	/** SELECT STATMENT */

	public static function table(string $name): QueryBuilder 
	{
		self::hasInstance();
		return self::$queryBuilder->table($name);
	}

	public static function select(mixed $columns): QueryBuilder 
	{
		self::hasInstance();
		return self::$queryBuilder->select($columns);
	}

	public static function join(string $table, string $table_key_id, string $other_key_id = null): QueryBuilder 
	{
		self::hasInstance();
		return self::$queryBuilder->join($table, $table_key_id, $other_key_id);
	}

	public static function rightJoin(string $table, string $table_key_id, string $other_key_id = null): QueryBuilder 
	{
		self::hasInstance();
		return self::$queryBuilder->rightJoin($table, $table_key_id, $other_key_id);
	}

	public static function fullJoin(string $table, string $table_key_id,string  $other_key_id = null): QueryBuilder 
	{
		self::hasInstance();
		return self::$queryBuilder->fullJoin($table, $table_key_id, $other_key_id);
	}

	public static function innerJoin(string $table, string $table_key_id, string $other_key_id = null): QueryBuilder 
	{
		self::hasInstance();
		return self::$queryBuilder->innerJoin($table, $table_key_id, $other_key_id);
	}

	public static function where(array $conditions): QueryBuilder 
	{
		self::hasInstance();
		return self::$queryBuilder->where($conditions);
	}

	public static function groupBy(array $group_by): QueryBuilder 
	{
		self::hasInstance();
		return self::$queryBuilder->groupBy($group_by);
	}

	public static function orderBy(array $order_by): QueryBuilder 
	{
		self::hasInstance();
		return self::$queryBuilder->orderBy($order_by);
	}

	public static function limit(int $limit = 20): QueryBuilder 
	{
		self::hasInstance();
		return self::$queryBuilder->limit($limit);
	}

	public static function get(int $page = 1): QueryBuilder 
	{
		self::hasInstance();
		return self::$queryBuilder->get($page);
	}

	/** END SELECT STATMENT */

	public static function insert(array $data): Void 
	{
		self::hasInstance();
		self::$queryBuilder->insert($data);
	}

	public static function update(array $data, array $conditions): Void 
	{
		self::hasInstance();
		self::$queryBuilder->update($data, $conditions);
	}

	public static function delete(array $conditions): Void 
	{
		self::hasInstance();
		self::$queryBuilder->delete($conditions);
	}

	/** END QUERY BUILDERS */

	/** QUERY BUILDER UTILITIES */

	public static function setResults(array $results): Void 
	{
		self::hasInstance();
		self::$queryBuilder->setResults($results);
	}

	public static function getResults(): array 
	{
		self::hasInstance();
		return self::$queryBuilder->getResults();
	}

	public static function getNumRows(): int 
	{
		self::hasInstance();
		return self::$queryBuilder->getNumRows();
	}

	public static function setNumRows(int $row): QueryBuilder 
	{
		self::hasInstance();
		return self::$queryBuilder->setNumRows($row);
	}

	public static function getTotalNumPage(): int 
	{
		self::hasInstance();
		return self::$queryBuilder->getTotalNumPage();
	}

	public static function getItemStartingNumber(): int 
	{
		self::hasInstance();
		return self::$queryBuilder->getItemStartingNumber();
	}

	public static function getPagination() 
	{
		self::hasInstance();
		return self::$queryBuilder->getPagination();
	}

	public static function getPrimaryKey() 
	{
		self::hasInstance();
		return self::$queryBuilder->getPrimaryKey();
	}

	public static function getTableFields() 
	{
		self::hasInstance();
		return self::$queryBuilder->getTableFields();
	}

	public static function getDatabaseConfig() 
	{
		self::hasInstance();
		return self::$queryBuilder->getDatabaseConfig();
	}

	/** END QUERY BUILDER UTILITIES */

	public static function hasInstance()
    {
		if (self::$queryBuilder === null) {
            throw new \RuntimeException('QueryBuilder instance has not been set.');
        }
    }
}