<?php

namespace EO\Database;

use Medoo\Medoo;
use EO\Handlers\Exceptions\DBQueryException as DBQueryException;
use EO\Facades\LoggerFacade as Logger;

class DBModel
{
	private ?Medoo $connection = null;

	protected $from;
	protected $join;
	protected $columns = "*";
	protected $groupBy = [];
	protected $orderBy = [];
	protected $conditions = [];
	protected $limit = [];

	public function __construct()
	{
		$db_config = $this->getDatabaseConfig();
		
		$this->connection = new Medoo([
			// [required]
			'type' => 'mysql',
			'host' => $db_config['host'],
			'database' => $db_config['name'],
			'username' => $db_config['user'],
			'password' => $db_config['pass'],
		
			// [optional]
			'charset' => 'utf8mb4',
			'collation' => 'utf8mb4_general_ci',
			/* 'port' => 3306, */
		
			// [optional] The from prefix. All from names will be prefixed as PREFIX_from.
			'prefix' => $db_config['prefix'],
		
			// [optional] To enable logging. It is disabled by default for better performance.
			'logging' => false,
		
			// [optional]
			// Error mode
			// Error handling strategies when the error has occurred.
			// PDO::ERRMODE_SILENT (default) | PDO::ERRMODE_WARNING | PDO::ERRMODE_EXCEPTION
			// Read more from https://www.php.net/manual/en/pdo.error-handling.php.
			'error' => \PDO::ERRMODE_EXCEPTION
		]);

		return $this;
	}

	/**
	 * Executes a raw SQL query and returns all the rows as an array.
	 *
	 * @param string $query The SQL query to execute.
	 * @return array An array containing all the rows returned by the query.
	 */
	public function query($query)
	{
		$data = $this->connection->query($query)->fetchAll(\PDO::FETCH_ASSOC);
		$this->hasQueryError()->resetClause();
		return $data;
	}

	/**
	 * Executes a raw SQL query and returns all the rows as an array.
	 *
	 * @param string $query The SQL query to execute.
	 * @return array An array containing all the rows returned by the query.
	 */
	public function execute($query)
	{
		$data = $this->connection->query($query)->fetch(\PDO::FETCH_ASSOC);
		$this->hasQueryError()->resetClause();
		return $data;
	}

	/**
	 * Executes a SELECT query based on the set parameters and returns the result.
	 *
	 * @return mixed The result of the SELECT query. If no columns are specified, it returns an associative array.
	 *               If a single column is specified, it returns a simple array.
	 *               If multiple columns are specified, it returns an array of objects.
	 */
	public function select()
	{
		if($this->join == null) { 
			$result = $this->connection->select($this->from, $this->columns, $this->conditions);
		}else {
			$result = $this->connection->select($this->from, $this->join, $this->columns, $this->conditions);
		}
		
		$this->hasQueryError()->resetClause();
		return $result;
	}

	public function count(string $from, mixed $columns, array $join = null, array $conditions = [])
	{
		if($join == null) {
			$result = $this->connection->select($from, $columns, $conditions);
		}else {
			$result = $this->connection->select($from, $join, $columns, $conditions);
		}

		$this->hasQueryError()->resetClause();
		return count($result);
	}

	/**
	 * Inserts data into the specified table.
	 *
	 * @param array $values An associative array conditions the keys are column names and the values are the data to be inserted.
	 * @return int Returns the ID of the inserted row.
	 *
	 * @throws PDOException If there is an error executing the SQL query.
	 *
	 * @note This method assumes that the table name has been set using the setFrom() method.
	 *       If the table name is not set, this method will throw an exception.
	 *
	 * @note This method also assumes that the primary key of the table is an auto-incrementing integer.
	 *       If the primary key is not auto-incrementing or if you want to specify a specific ID, you should modify the code accordingly.
	 */
	public function insert($values)
	{
		$result = $this->connection->insert($this->from, $values);

		if ($result === null) {
			throw new DBQueryException("INSERT failed for table '{$this->from}' — query returned null");
		}

		$id = $this->insertId();

		$this->hasQueryError()->resetClause();
		return $id;
	}

	/**
	 * Updates data in the specified table based on the given conditions.
	 *
	 * @param array $data An associative array conditions the keys are column names and the values are the data to be updated.
	 * @param array $conditions An associative array representing the conditions for updating the data.
	 *
	 * @return void This method does not return any value.
	 *
	 * @throws PDOException If there is an error executing the SQL query.
	 *
	 * @note This method assumes that the table name has been set using the setFrom() method.
	 *       If the table name is not set, this method will throw an exception.
	 */
	public function update($data, $conditions)
	{
		$this->connection->update($this->from, $data, $conditions);
		$this->hasQueryError()->resetClause();
	}
	
	/**
	 * Deletes data from the specified table based on the given conditions.
	 *
	 * @return int Returns the number of rows deleted.
	 */
	public function delete()
	{
		$result = $this->connection->delete($this->from, $this->conditions);
		$this->hasQueryError()->resetClause();
		return $result->rowCount();
	}

	/**
	 * Replaces data in the specified table based on the given conditions.
	 *
	 * @param string $from The name of the table to replace data in.
	
	 * @return mixed Returns the ID of the replaced row or false on failure.
	 */
	public function replace()
	{
		$result = $this->connection->replace($this->from, $this->columns, $this->conditions);
		$this->hasQueryError()->resetClause();
		return $result;
	}

	/**
	 * Retrieves a single row from the database based on the set parameters.
	 *
	 * @return mixed The result of the SELECT query. If no columns are specified, it returns an associative array.
	 *               If a single column is specified, it returns a simple value.
	 *               If multiple columns are specified, it returns an object.
	 *               Returns null if no matching row is found.
	 */
	public function get()
	{
		if($this->join == null) {
			// If no join is specified, perform a simple get operation
			$result = $this->connection->get($this->from, $this->columns, $this->conditions);
		} else {
			// If a join is specified, perform a get operation with join
			$result = $this->connection->get($this->from, $this->join, $this->columns, $this->conditions);
		}
		
		$this->hasQueryError()->resetClause();
		return $result;
	}

	/**
	 * Checks if a record exists in the specified table based on the given conditions.
	 *
	 * @param array $conditions An associative array representing the conditions for checking the existence of a record.
	 * @return bool Returns true if a record exists that matches the given conditions, otherwise returns false.
	 */
	public function has($conditions)
	{
		if($this->join == null) {
			// If no join is specified, perform a simple has operation
			$result = $this->connection->has($this->from, $conditions);
		}else {
			// If a join is specified, perform a has operation with join
			// Note: The $join parameter is not used in this context, but it is included for completeness.
			$result = $this->connection->has($this->from, $this->join, $conditions);
		}

		$this->hasQueryError()->resetClause();
		return $result;
	}

	/**
	 * Retrieves a random row from the database based on the set parameters.
	 *
	 * @return mixed The result of the SELECT query. If no columns are specified, it returns an associative array.
	 *               If a single column is specified, it returns a simple value.
	 *               If multiple columns are specified, it returns an object.
	 *               Returns null if no matching row is found.
	 */
	public function rand()
	{
		if($this->join == null) {
			$result = $this->connection->rand($this->from, $this->columns, $this->conditions);
		}else {
			$result = $this->connection->rand($this->from, $this->join, $this->column, $this->conditions);
		}

		$this->hasQueryError()->resetClause();
		return $result;
	}

	/**
	 * Fetches the column names and their details from the specified table.
	 *
	 * @param string $from The name of the table from which to fetch the column names.
	 * @return array An array containing the column names and their details.
	 *
	 * @throws PDOException If there is an error executing the SQL query.
	 *
	 */
	public function fetchColumns($from)
	{
		// Use the query method of the Medoo library to execute the SQL query
		// The SQL query is to describe the table and fetch all the column details
		$result = $this->connection->query(" DESCRIBE ".$_ENV['DB_PREFIX'].$from)->fetchAll();

		$this->hasQueryError()->resetClause();
		return $result;
	}

	/**
	 * Retrieves the ID of the last inserted row.
	 *
	 * @return ?int The ID of the last inserted row.
	 *
	 * @throws PDOException If there is an error executing the SQL query.
	 *
	 * @note This method is useful when you need to retrieve the ID of the row that was just inserted.
	 *       It is important to note that this method assumes that the primary key of the table is an auto-incrementing integer.
	 */
	public function insertId()
	{
		return $this->connection->id();
	}

	/**
	 * Sets the table name for the SELECT, INSERT, UPDATE, DELETE, or REPLACE query.
	 *
	 * @param string $table The name of the table.
	 * @return $this Returns the current instance of the Database class for method chaining.
	 */
	public function setFrom($table)
	{
		$this->from = $table;
		return $this;
	}

	/**
	 * Sets the join conditions for the SELECT query.
	 *
	 * @param array $args An array containing the join conditions.
	 * @return $this Returns the current instance of the Database class for method chaining.
	 */
	public function setJoin(array $args)
	{
		foreach($args as $key => $value) {
			$this->join[$key] = $value;
		}
		return $this;
	}

	/**
	 * Sets the columns to be selected, inserted, updated, or replaced in the query.
	 *
	 * @param array $names An array containing the column names.
	 * @return $this Returns the current instance of the Database class for method chaining.
	 */
	public function setColumns(array $names)
	{
		$this->columns = $names;
		return $this;
	}

	/**
	 * Sets the conditions conditions for the SELECT, UPDATE, or DELETE query.
	 *
	 * @param array $filter An associative array representing the conditions conditions.
	 * @return $this Returns the current instance of the Database class for method chaining.
	 */
	public function setWhere(array $filter)
	{
		$this->conditions = $filter;
		return $this;
	}

	/**
	 * Sets the GROUP BY clause for the SELECT query.
	 *
	 * @param string $group The column name to group the results by.
	 * @return $this Returns the current instance of the Database class for method chaining.
	 */
	public function setGroupBy(array $group)
	{
		$this->groupBy = $group;
		return $this;
	}

	/**
	 * Sets the ORDER BY clause for the SELECT query.
	 *
	 * @param string $order The column name to order the results by.
	 * @return $this Returns the current instance of the Database class for method chaining.
	 */
	public function setOrderBy(array $order)
	{
		$this->orderBy = $order;
		return $this;
	}

	/**
	 * Sets the LIMIT clause for the SELECT query.
	 *
	 * @param array $limit The maximum number of rows to return.
	 * @return $this Returns the current instance of the Database class for method chaining.
	 */
	public function setLimit($limit = 20, $adjacent = 0)
	{
		$this->limit = [ "LIMIT" => [ $adjacent, $limit ] ];
		return $this;
	}

	/**
	 * Returns the table name for the SELECT, INSERT, UPDATE, DELETE, or REPLACE query.
	 *
	 * @return string The name of the table.
	 */
	public function getFrom()
	{
		return $this->from;
	}

	/**
	 * Returns the join conditions for the SELECT query.
	 *
	 * @return array An array containing the join conditions.
	 */
	public function getJoin()
	{
		return $this->join;
	}

	/**
	 * Returns the columns to be selected, inserted, updated, or replaced in the query.
	 *
	 * @return array An array containing the column names.
	 */
	public function getColumns()
	{
		return $this->columns;
	}

	/**
	 * Returns the conditions conditions for the SELECT, UPDATE, or DELETE query.
	 *
	 * @return array An associative array representing the conditions conditions.
	 */
	public function getWhere()
	{
		return $this->conditions;
	}

	/**
	 * Returns the GROUP BY clause for the SELECT query.
	 *
	 * @return array An array containing the column names to group the results by.
	 */
	public function getGroupBy()
	{
		return $this->groupBy;
	}

	/**
	 * Returns the ORDER BY clause for the SELECT query.
	 *
	 * @return array An associative array representing the column names and the order (ASC or DESC).
	 */
	public function getOrderBy()
	{
		return $this->orderBy;
	}

	/**
	 * Returns the LIMIT clause for the SELECT query.
	 *
	 * @return array An array containing the maximum number of rows to return.
	 */
	public function getLimit()
	{
		return $this->limit;
	}

	public function resetClause()
	{
		$this->from = null;
		$this->join = null;
		$this->columns = "*";
		$this->groupBy = null;
		$this->orderBy = null;
		$this->conditions = null;
		return $this;
	}

	function getPrimaryKey($table)
	{
		$result = $this->connection->query("SHOW KEYS FROM ".self::DB_PREFIX."{$table} WHERE Key_name = 'PRIMARY'")->fetch(\PDO::FETCH_ASSOC);
		return $result;
	}

	function getTableFields($table)
	{
		$result = $this->fetchColumns($table);
		if ($result) {
			return array_column($result, 'Field');
		}
		return [];
	}

	function checkTableIfExists($table)
	{
		$result = $this->connection->query("SELECT * FROM information_schema.tables WHERE table_schema = '".$_ENV['DB_NAME']."' AND table_name = '".$table."' LIMIT 1")->fetch(\PDO::FETCH_ASSOC);
		return $result;
	}

	public function hasQueryError()
	{
		if(isset($this->connection->error)) {
			throw new DBQueryException($this->connection->error);
		}
		return $this;
	}

	public function getDatabaseConfig()
	{
		return [
			"host" => $_ENV['DB_HOST'],
			"user" => $_ENV['DB_USER'],
			"pass" => $_ENV['DB_PASS'],
			"name" => $_ENV['DB_NAME'],
			"prefix" => $_ENV['DB_PREFIX']
		];
	}
	
}

