<?php

namespace Library;

use Medoo\Medoo;

class Table {

	/**
	 * Database Object instance for database operations.
	 * @var object
	 */
	public $DBO;

	/**
	 * Pagination instance for managing pagination.
	 * @var object
	 */
	protected $pagina;

	/**
	 * Alias for the table, used in SQL queries.
	 * @var string
	 */
	protected String $alias;

	/**
	 * Name of the table to perform operations on.
	 * @var string
	 */
	protected String $table;

	/**
	 * Primary key of the table.
	 * @var string
	 */
	protected String $primary_key = "";

	/**
	 * Fields of the table to be used in SQL queries.
	 * @var array
	 */
	public Array $fields = [];

	/**
	 * Associative array representing a row's column names and their values.
	 * @var array
	 */
	public Array $column = [];

	/**
	 * Number of rows affected or returned by the last SQL operation.
	 * @var int
	 */
	public Int $rows = 0;

	/**
	 * Results of the last query executed.
	 * @var array
	 */
	public $results = [];

	/**
	 * Total number of pages available for pagination.
	 * @var int
	 */
	public Int $total_pages = 0;

	/**
	 * Configuration for pagination, including limits, current page, and URI.
	 * @var array
	 */
	public $page = [
		"adjacents" => null,
		"limit" => 20,
		"current" => null,
		"uri" => [],
		"url" => null,
		"starting_number" => null
	];

	/**
	 * Pagination HTML or other content.
	 * @var mixed
	 */
	public $pagination;
	public $pagina_links;

	function init() {

		$this->DBO = \Library\Factory::getDBO();
		$this->pagina = \Library\Factory::getPagination();
		
		if(empty($this->fields)) {
			$this->getFields();
		}
	
	}

	function medooRaw(String $string) {
		return Medoo::raw($string);
	}

	function select(mixed $columns) {
		$this->DBO->setColumns($columns);
		return $this;
	}

	function join($table, $table_key_id, $other_key_id = null) {

		$primary_key = $this->primary_key;
		if(!is_null($other_key_id)) {
			$primary_key = $other_key_id;
		}

		$join["[>]$table"] = [$primary_key => $table_key_id];

		$this->DBO->setJoin($join);
		return $this;
	}

	function rightJoin($table, $table_key_id, $other_key_id = null) {
		
		$primary_key = $this->primary_key;
		if(!is_null($other_key_id)) {
			$primary_key = $other_key_id;
		}

		$join["[>]$table"] = [$primary_key => $table_key_id];

		$this->DBO->setJoin($join);
		return $this;
	}

	function fullJoin($table, $table_key_id, $other_key_id = null) {
		
		$primary_key = $this->primary_key;
		if(!is_null($other_key_id)) {
			$primary_key = $other_key_id;
		}

		$join["[>]$table"] = [$primary_key => $table_key_id];

		$this->DBO->setJoin($join);
		return $this;
	}

	function innerJoin($table, $table_key_id, $other_key_id = null) {
		
		$primary_key = $this->primary_key;
		if(!is_null($other_key_id)) {
			$primary_key = $other_key_id;
		}

		$join["[>]$table"] = [$primary_key => $table_key_id];

		$this->DBO->setJoin($join);
		return $this;
	}

	function where(array $where) {

		if(!is_array($where)) {
			return $this;
		}

		foreach($where as $key => $val) {

			if(stripos($key, "AND") !== false || stripos($key, "OR") !== false) {
				if(is_array($val)) {
					foreach($val as $field => $value) {
						$where[$key][$this->table.".".$field] = $value;
						unset($where[$key][$field]);	
					}
				}
			}else {
				$where[$this->table.".".$key] = $val;
				unset($where[$key]);
			}

		}

		$this->DBO->setWhere($where);
		return $this;
	}

	function orderBy($order_by) {
		if(!is_array($order_by)) { return $this; }

		foreach($order_by as $name => $value) {
			$order_by[$this->table.".".$name] = $value;
			unset($order_by[$name]);
		}

		$this->DBO->setOrderBy(["ORDER" => $order_by]);
		return $this;
	}

	function groupBy($group_by) {
		if(!is_array($group_by)) { return $this; }

		foreach($group_by as $name => $value) {
			$group_by[$this->table.".".$name] = $value;
			unset($group_by[$name]);
		}

		$this->DBO->setGroupBy(["GROUP" => $group_by]);
		return $this;
	}

	function limit($limit) {
		if(!is_array($limit)) { return $this; }
		$this->limit = ["LIMIT" => $limit];
		return $this;
	}

	function getBy($column, $value) {

		$where = array_merge(
			[$this->table.".".$column => $value],
			($this->DBO->getWhere() !== null ? $this->DBO->getWhere() : []),
			($this->DBO->getGroupBy() !== null ? $this->DBO->getGroupBy() : []),
			($this->DBO->getOrderBy() !== null ? $this->DBO->getOrderBy() : [])
		);

		$result = 
		$this->DBO
			->setFrom($this->table)
				->setWhere($where)
					->get();

		if($result) {

			$result = $this->jsonToArray($result);
			
			$this->column = $result;
			$this->results = $result;
			return $result;
		}

		return false;

	}

	function getId($id) {
		return $this->getBy($this->primary_key, $id);
	}

	function getList($page = 1, $limit = 20, $url = null) {

		$this->setPagina($page, $limit);
		$this->page['url'] = $url;

		$this->DBO->setLimit([
			"LIMIT" => [
				$this->page['adjacents'], $this->page['limit']
			]
		]);

		$where = array_merge(
			($this->DBO->getWhere() !== null ? $this->DBO->getWhere() : []),
			($this->DBO->getGroupBy() !== null ? $this->DBO->getGroupBy() : []),
			($this->DBO->getOrderBy() !== null ? $this->DBO->getOrderBy() : [])
		);

		$result = 
			$this->DBO
				->setFrom($this->table)
					->setWhere(
							array_merge($where, ($this->DBO->getLimit() !== null ? $this->DBO->getLimit() : []))
						)
						->select();

        if($result) {

			$this->rows = $this->DBO->connection->count($this->table, $where);
			$this->total_pages = ceil($this->rows / $this->page['limit']);
			$this->pagination = $this->pagina->build($this, $this->page['url'], $this->page['uri']);

			$this->createPaginaLinks();
			
			for($i=0; $i<count($result); $i++) {
				/* foreach($this->fields as $key => $field) {
					$result[$i][$field] = $this->jsonToArray($result[$i][$field]);
				} */
				$result[$i] = $this->jsonToArray($result[$i]);
			}

			$this->results = $result;
            return $result;
        }

		return false;

	}

	function insert() {
		$id = $this->DBO->setFrom($this->table)->insert($this->column);
        return $id;
	}

	function update() {
		$this->DBO
			->setFrom($this->table)
				->update($this->column, $this->DBO->getWhere()
			);
	}

	function delete() {

		$this->DBO
			->setFrom($this->table)
				->delete();

	}

	function getFields() {
		$result =  $this->DBO->fetchColumns($this->table);
		if($result) {
			for($i=0; $i<count($result); $i++) {
				$this->fields[] = $result[$i]['Field'];
			}
		}
		return $this->fields;
	}

	function processArray($data) {
		foreach ($data as $key => $value) {
			if (is_array($value)) {
				$data[$key] = $this->processArray($value);
			}else {
				if(!is_null($data[$key]) && !is_numeric($data[$key])) {
					json_decode($data[$key]);
					if(json_last_error() === 0) {
						$data[$key] = json_decode($data[$key], true);
					}
				}
			}
		}

		return $data;
	}

	function jsonToArray($data) {
		
		foreach($data as $key => $val) {
			if(is_array($val)) {  
				$data[$key] = $this->processArray($val);
			}else {
				if(!is_null($data[$key]) && !is_numeric($data[$key])) {
					json_decode($data[$key]);
					if(json_last_error() === 0) {
						$data[$key] = json_decode($data[$key], true);
					}
				}
			}
		}
		
		return $data;

	}

	function setPagina($page, $limit = 20) {

		$this->page['limit'] = $limit;
		$this->page['current'] = (in_array($page, range(0, 10000)) ? $page : 10000000);
		$this->page['adjacents'] = ($this->page['current'] - 1) * $this->page['limit'];

		if($this->page['current']) {
			$this->page['starting_number'] = $this->page['adjacents']; 
		} else {
			$this->page['starting_number'] = 0;			
		}

	}

	function createPaginaLinks() {
		if($this->total_pages > 0) {
            for($i = 0; $i <= $this->total_pages; $i++) {
                $this->pagina_links[$i] = url($this->page['url'], null, array_merge($this->page['uri'], ["page" => $this->page['current']]));
            }
        }
	}

}

