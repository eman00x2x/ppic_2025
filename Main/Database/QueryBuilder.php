<?php

namespace EO\Database;

use EO\Handlers\Exceptions\DBQueryException as DBQueryException;
use Medoo\Medoo;
use Medoo\Medoo\Raw;
use EO\Support\Helpers\Inflect;
use EO\Database\DBModel;
use EO\Database\Pagination;
use EO\Database\Collections;
use EO\Factories\Factory;

class QueryBuilder
{
	private DBModel $DBO;
	private Pagination $pagina;

	private string $table;
	private string $primaryKey;
	private int $limit;
	private mixed $totalRows = 0;
	private array $uri = [];
	protected array $model;
	protected $currentModel;
	
	public function __construct()
	{
		$this->DBO = Factory::DBO();
		$this->pagina = Factory::Pagination();
		/* $this->DBO = new DBModel();
		$this->pagina = new Pagination(); */
	}

	public function table(string $name): self
	{
		$this->table = $name;
		return $this;
	}

	public function raw(string $string)
	{
		return Medoo::raw($string);
	}

	public function query(string $query)
	{
		return $this->DBO->query($query);
	}

	public function execute(string $query)
	{
		return $this->DBO->execute($query);
	}

	public function select(mixed $columns): self
	{
		if(is_string($columns)) {
			$columns = array_map("trim", explode(',', $columns));
		}

		$this->DBO->setColumns($columns);
		return $this;
	}

	public function join($table, $table_key_id, $other_key_id): self
	{
		$join = ['[>]' . $table => [$table_key_id => $other_key_id]];
		$this->DBO->setJoin($join);
		return $this;
	}

	public function rightJoin($table, $table_key_id, $other_key_id = null): self
	{
		$join = ['[<]' . $table => [$table_key_id => $other_key_id]];
		$this->DBO->setJoin($join);
		return $this;
	}

	public function fullJoin($table, $table_key_id, $other_key_id = null): self
	{
		$join = ['[<>]' . $table => [$table_key_id => $other_key_id]];
		$this->DBO->setJoin($join);
		return $this;
	}

	public function innerJoin($table, $table_key_id, $other_key_id = null): self
	{
		$join = ['[><]' . $table => [$table_key_id => $other_key_id]];
		$this->DBO->setJoin($join);
		return $this;
	}

	public function where(array $conditions): self
	{
		if(!is_array($conditions)) {
			return $this;
		}

		$conditions = $this->formatConditions($conditions);
		$this->DBO->setWhere($conditions);
		return $this;
	}

	public function orderBy($order_by): self
	{
		$this->DBO->setOrderBy(["ORDER" => $this->formatConditions($order_by)]);
		return $this;
	}

	public function groupBy(array $group_by): self
	{
		if(!is_array($group_by)) { return $this; }

		$this->DBO->setGroupBy(["GROUP" => $group_by]);
		return $this;
	}

	public function limit(int $limit = 20): self
	{
		$this->pagina->setPerPage($limit);
		return $this;
	}

	public function get(int $current_page = 1): mixed
	{
		$this->pagina->setCurrentPage($current_page);
		$this->DBO->setLimit($this->pagina->getPerPage(), $this->pagina->getAdjacent());

		$columns = $this->DBO->getColumns();
		$joins = $this->DBO->getJoin();

		$conditions = array_merge(
			$this->DBO->getWhere() ?? [],
			$this->DBO->getGroupBy() ?? [],
			$this->DBO->getOrderBy() ?? []
		);

		$result = $this->DBO
			->setFrom($this->table)
			->setWhere(array_merge($conditions, $this->DBO->getLimit()))
			->select();

		$this->totalRows = $this->DBO->count($this->table, $columns, $joins, $conditions);
		$this->pagina->setTotalRows(total_rows: $this->totalRows);

		if ($result) {
			$collections = array_map([$this, 'jsonToArray'], $result);
			return $this->pagina->setItems(Collections::make($collections));
		}

		return $this->pagina->setItems(Collections::make());
	}

	public function findById($id): mixed
	{
		$result = 
		$this->DBO
			->setFrom($this->table)
			->setWhere([$this->table.".".$this->primaryKey => $id])
			->get()
		;

		if ($result) {
			$this->totalRows = 1;
			$collections = $this->jsonToArray($result);
			return $this->pagina->setItems(Collections::make($collections));
		}

		return $this->pagina->setItems(Collections::make());
	}

	public function insert($data): int
	{
		$id = $this->DBO->setFrom($this->table)->insert($data);
		return (int) $id;
	}

	public function update(array $data, array $conditions = [])
	{
		$this->DBO
			->setFrom($this->table)
			->update($data, $conditions);
		return $this;
	}

	public function delete(array $conditions)
	{
		$this->DBO
			->setFrom($this->table)
				->setWhere($conditions)
				->delete();
		return $this;
	}

	public function getNumRows(): int
	{
		return $this->totalRows ?? 0;
	}

	public function setNumRows($row): self
	{
		$this->totalRows = $row;
		return $this;
	}

	public function setPrimaryKey(string $primary_key)
	{
		$this->primaryKey = $primary_key;
		return $this;
	}

	public function getTotalNumPage(): int
	{
		return $this->totalPages;
	}

	public function getItemStartingNumber(): int
	{
		return $this->pagina->getPagination()['starting_number'];
	}

	public function getPagination()
	{
		return $this->pagina;
	}

	public function getPrimaryKey()
	{
		return $this->primaryKey;
	}

	public function getTableFields()
	{
		return $this->DBO->getTableFields($this->table);
	}

	public function setUri($key, $value)
	{
		$this->uri[$key] = $value;
	}

	public function getUri($key)
	{
		return $this->uri[$key];
	}

	private function jsonToArray($data) 
	{
		foreach($data as $key => $val) {
			if(is_array($val)) {  
				$data[$key] = $this->jsonToarray($val);
			}else {
				if(!is_null($data[$key]) && !is_numeric($data[$key])) {
					json_decode($data[$key]);
					if(json_last_error() === 0) {
						$data[$key] = json_decode($data[$key], true);
					}
				}
			}
		}

		return new $this->currentModel($data);
	}

	public function formatConditions($conditions)
	{
		foreach ($conditions as $key => $val) {
			if (stripos($key, "AND") !== false || stripos($key, "OR") !== false) {
				if (is_array($val)) {
					foreach ($val as $field => $value) {
						if(str_contains($field, $this->table . ".")) {
							$conditions[$key][$field] = $value;
						}else {
							$conditions[$key][$this->table . "." . $field] = $value;
							unset($conditions[$key][$field]);
						}
					}
				}
			} else if(strpos($key, "MATCH") !== false) {
				$conditions["MATCH"] = $this->validateMatchCondition($conditions[$key]);
			} else {
				if(!$conditions instanceof \Medoo\Raw) {
					if(str_contains($key, $this->table . ".")) {
						$conditions[$key] = $val;
					}else {
						$conditions[$this->table . "." . $key] = $val;
						unset($conditions[$key]);
					}
				}
				
			}
		}
		
		return $conditions;
	}

	public function validateMatchCondition(array $condition): array 
	{
		$allowed_modes = [
			'natural',
			'natural+query',
			'boolean',
			'query'
		];

		$validated_condition = [];

		if (!isset($condition['keyword'])) {
			throw new DBQueryException('Malformed query: keyword is required');
		}

		foreach ($condition as $key => $value) {
			switch ($key) {
				case 'columns':
					if (!is_array($value)) {
						$value = [$value];
					}
					$validated_condition['columns'] = $value;
					break;
				case 'keyword':
					$validated_condition['keyword'] = $value;
					break;
				case 'mode':
					if (!in_array($value, $allowed_modes)) {
						$value = 'natural';
					}
					$validated_condition['mode'] = $value;
					break;
				default:
					throw new DBQueryException("Malformed query: $key is not a valid match condition");
			}
		}

		return $validated_condition;
	}

	public function getDatabaseConfig()
	{
		return $this->DBO->getDatabaseConfig();
	}


	public function initializeModel($model)
	{
		if(isset($this->model[$model])) {
			$this->setCurrentModelObject($this->model[$model]);
			return $this->currentModel;
		}
		
		$this->model[$model] = new $model();
		$this->setCurrentModelObject($this->model[$model]);
		return $this->currentModel;
	}

	private function setCurrentModelObject($model)
	{
		$this->currentModel = $model;
		$model_name = get_class($this->currentModel);
		$namespace_parts = explode('\\', $model_name);
		$classNameWithoutNamespace = array_pop($namespace_parts);
		$table_name = Inflect::pluralize(str_replace('Model', '', $model_name));
		$primary_key = Inflect::singularize($table_name) . '_id';

		if (property_exists($model, 'table')) {
			$table_name = $this->getModelProtectedProperty($model, $this->currentModel, "table");
			$this->table($table_name);
		}

		if (property_exists($model, 'primaryKey')) {
			$primary_key = $this->getModelProtectedProperty($model, $this->currentModel, "primaryKey");
			$this->setPrimaryKey($primary_key);
		}
	}

	public function getModelDefaultColumns()
	{
		return $this->getModelProtectedProperty(get_class($this->currentModel), $this->currentModel, "properties");
	}

	public function setProperties(array $data) {
		foreach ($data as $name) {
			$this->currentModel->attributes[$name] = null;
		}
		return $this;
	}

	private function getModelProtectedProperty($model_name, $object, $property)
	{
		$rp = new \ReflectionProperty($model_name, $property);
		$rp->setAccessible(true);
		return $rp->getValue($object);
	}

	public function getModelObject()
	{
		return $this->model;
	}

	public function checkTableIfExists($table)
	{
		return $this->DBO->checkTableIfExists($table);
	}

}