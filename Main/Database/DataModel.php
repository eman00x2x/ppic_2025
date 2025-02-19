<?php

namespace EO\Database;

use Pecee\Http\Exceptions\MalformedUrlException;
use EO\Handlers\Exceptions\ResourceNotFoundException;
use EO\Database\QueryBuilder;

class DataModel extends QueryBuilder
{
	function getCollections(array $request = []): mixed
	{
		$result =
		$this->filter(request: $request)
			->sort(request: $request)
			->limit(limit: ($request['rows'] ?? 20))
			->get(current_page: ($request['page'] ?? 1))
		;

		return $result;
	}

	function getBy(string $name, string $value, array $conditions = []): mixed
	{
		$conditions[$name] = $value;
		$result = $this->filter(request: $conditions)->get(current_page: ($conditions['page'] ?? 1));

		return $result;
	}

	function getById(int $id): mixed
	{
		$result = $this->findById($id);
		return $result;
	}

	function getId($id): mixed
	{
		return $this->findById($id);
	}

	function create(array $data): int
	{
		$processed_data = $this->checkDataIntegrity(data: $data); 
		// Insert the new record into the database.
		return $this->insert($processed_data);
	}

	function modify(array $data, mixed $id = null, array $conditions = []): void
	{
		// Check data integrity before saving.
		$processed_data = $this->checkDataIntegrity(data: $data);

		if ($id !== null) {
			$conditions[$this->getPrimaryKey()] = $id;
		}

		// Update the model model in the database.
		$this->update($processed_data, $conditions);
	}
	
	private function checkDataIntegrity(array $data): array
	{
		foreach ($data as $fieldName => $fieldValue) {
			if (in_array($fieldName, $this->getModelDefaultColumns())) {
				$processed_data[$fieldName] = is_array($fieldValue) ? json_encode($fieldValue) : $fieldValue;
			}
		}

		if(!isset($processed_data)) {
			throw new \Exception("Model properties is not set in Trait!");
		}

		return $processed_data;
	}
	
	/**
	 * Filters the model data based on the provided request parameters.
	 *
	 * @param array|false $request The request parameters. If false, no filtering is performed.
	 * @return $this Returns the current model of the model for method chaining.
	 */
	public function filter($request = false): self
	{
		if ($request === false || !is_array($request)) {
			return $this;
		}

		$filters = [];

		foreach ($request as $param_name => $param_value) {
			if (!in_array($param_name, ['page', 'rows', 'sort'])) {
				$filters += $this->formatConditions([$param_name => $param_value]);
			}

			/* if($param_name != "session_id") {
				$this->setUri($param_name, $param_value);
			} */
		}
		
		foreach($filters as $key => $value) {
			$this->validateFields($key, $value);
		}

		$this->where($filters);
		
		return $this;
	}

	private function validateFields(string $key, mixed $value): void
	{
		if (str_contains($key, 'AND') || str_contains($key, 'OR')) {
			foreach ($value as $field => $filter_value) {
				if (str_contains($field, '.')) {
					[$table, $field_name] = explode('.', $field);
				} else {
					$field_name = $field;
				}

				$this->isValidField($field_name);
			}
		} else if (str_contains($key, 'MATCH')) {
			foreach ($value['columns'] as $field) {
				if (str_contains($field, '.')) {
					[$table, $field_name] = explode('.', $field);
				} else {
					$field_name = $field;
				}

				$this->isValidField($field_name);
			}
		} else {
			if (str_contains($key, '.')) {
				[$table, $field_name] = explode('.', $key);
			} else {
				$field_name = $key;
			}

			$this->isValidField($field_name);
		}
	}

	/**
	 * Sorts the list based on the request parameters.
	 *
	 * @param array $request The request parameters.
	 * @param array $initial_sorting The initial sorting clause.
	 * @return $this The current model of the model for method chaining.
	 * @throws MalformedUrlException If the request contains an invalid sort direction.
	 */
	function sort($request): self {
		if (isset($request['sort']) && $request['sort'] !== "" && strpos($request['sort'], "|") !== false) {
			$sorting = [];
			[$field, $direction] = explode("|", $request['sort']);

			if (in_array(strtoupper($direction), ["ASC", "DESC"])) {
				if ($this->isValidField($field)) {
					$sorting[$field] = strtoupper($direction);
				} else {
					throw new MalformedUrlException("404 Invalid sort field: $field");
				}
			} else {
				throw new MalformedUrlException("404 Invalid sort direction: $direction");
			}
		}

		$this->orderBy($sorting ?? [$this->getPrimaryKey() => "DESC"]);
		return $this;
	}
	
	/**
	 * Encrypts the given data using the specified encryption type.
	 *
	 * @param string $data The data to be encrypted.
	 * @param string $encryption_type The type of encryption to use. Default is "bcrypt".
	 *
	 * @return string The encrypted data.
	 * @throws \Exception If an unsupported encryption type is provided.
	 */
	function encrypt(string $data, string $encryption_type = "bcrypt"): string {
		$encryptions = [
			"bcrypt" => function ($data) {
				return password_hash($data, PASSWORD_BCRYPT, ['cost' => 11]);
			},
			"sha1" => "sha1",
			"md5" => "md5"
		];

		if (!isset($encryptions[$encryption_type])) {
			throw new \Exception("Unsupported encryption type: {$encryption_type}");
		}

		$method = $encryptions[$encryption_type];

		return is_callable($method) ? $method($data) : $method($data);
	}
	
	/**
	 * Loads the data model with the specified columns and joins.
	 *
	 * @param array $collections The collections to load.
	 * @return $this The data model model.
	 */
	public function load(array $collections): self
	{
		$columns = $this->buildFields($collections['fields']);

		$this->select($columns);

		if (isset($collections['join'])) {
			foreach ($collections['join'] as $table_name => $joinKeys) {
				[$table_key_id, $other_key_id] = $joinKeys;

				$this->join($table_name, $table_key_id, $other_key_id);
			}
		}

		return $this;
	}

	/**
	 * Builds a list of columns from the given fields specification.
	 *
	 * @param array $fields_specification The fields specification.
	 * @return array The list of columns.
	 */
	private function buildFields(array $fields_specification): array {
		$columns = [];

		foreach ($fields_specification as $column_name => $column_specification) {
			if (is_string($column_specification)) {
				$columns[] = "{$column_specification} ({$column_name})";
			} elseif (is_array($column_specification)) {
				if (isset($column_specification['raw'])) {
					$columns[$column_name] = $this->raw($column_specification['raw']);
				} else {
					$columns[$column_name] = $this->buildFields($column_specification['fields']);
				}
			}
		}

		return $columns;
	}
	
	/**
	 * Checks if the given field_name name is valid.
	 *
	 * @param string $field_name The name of the field to be validated.
	 *
	 * @return bool True if the field is valid, false otherwise.
	 */
	private function isValidField($field_name) {
		if ($field_name === 'search') {
			return false;
		}

		$filtered_field_name = str_replace([
			"[~]", 	// LIKE
			"[>]", 	// GREATER THAN
			"[<]", 	// LESS THAN
			"[<>]",	// BETWEEN
			"[><]",	// NOT BETWEEN
			"[!]", 	// NOT
			"[!=]",	// NOT EQUAL
			"[>=]",	// GREATER THAN OR EQUAL
			"[<=]" // LESS THAN OR EQUAL
		], '', $field_name);

		return in_array($filtered_field_name, $this->getModelDefaultColumns()) ? true : $this->handleInvalidField($filtered_field_name);
	}

	private function handleInvalidField($field_name) {
		throw new MalformedUrlException("404 Field '$field_name' is invalid, request not found!");
	}
}