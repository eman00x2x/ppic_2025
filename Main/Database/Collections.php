<?php

namespace EO\Database;

class Collections implements \IteratorAggregate
{
	public $items;
	
	public function __construct($items)
	{
		$this->items = $items;
	}

	/**
	 * {@inheritDoc}
	 *
	 * This implementation allows the collection to be iterated by using a foreach
	 * loop. The keys and values of the collection will be the keys and values
	 * of the items array.
	 */
	public function getIterator(): \Traversable
	{
		return (function () {
			foreach($this->items as $key => $val) {
				yield $key => $val;
			}
		})();
	}

	/**
	 * Returns all items in the collection.
	 *
	 * @return array
	 */
	public function all()
	{
		return $this->items;
	}

	public function getItems() {
		return $this->all();
	}

	/**
	 * Apply a callback to all items in the collection.
	 *
	 * The callback function will recieve two arguments, the item and the key of
	 * the item. The callback should return a modified version of the item.
	 *
	 * The modified items will be returned as a new collection.
	 *
	 * @param callable $callback The callback function to apply to each item.
	 *
	 * @return Collections A new collection with the modified items.
	 */
	public function map(callable $callback)
	{
		// Use array_map to apply the callback to each item
		$keys = array_keys($this->items);
		$items = array_map($callback, $this->items, $keys);

		return new static(array_combine($keys, $items));
	}

	/**
	 * Filters the items in the collection.
	 *
	 * If a callback function is provided, each item will be passed to the
	 * callback. If the callback returns false, the item will be excluded
	 * from the new collection.
	 *
	 * If no callback is provided, the items will be filtered by their
	 * boolean value.
	 *
	 * @param callable $callback The callback function to apply to each item.
	 *
	 * @return Collections A new collection with the filtered items.
	 */
	public function filter(callable $callback = null)
	{
		if ($callback) {
			// Use array_filter to apply the callback to filter the items
			return new static(array_filter($this->items, $callback, ARRAY_FILTER_USE_BOTH));
		}

		// If no callback is provided, filter truthy values
		return new static(array_filter($this->items));
	}

	/**
	 * Iterate over the items in the collection.
	 *
	 * The callback function will recieve two arguments, the item and the key of
	 * the item. If the callback function returns false, the iteration will be
	 * halted.
	 *
	 * @param callable $callback The callback function to apply to each item.
	 *
	 * @return Collections The same collection.
	 */
	public function each(callable $callback)
	{
		foreach ($this->items as $key => $item) {
			if ($callback($item, $key) === false) {
				break;
			}
		}

		return $this;
	}

	/**
	 * Reduce the collection to a single value.
	 *
	 * Iterates over the items in the collection and applies the callback function to each item
	 * along with an accumulator value. The result of each iteration is used as the accumulator
	 * in the next iteration. The final result is the accumulated value.
	 *
	 * @param callable $callback The callback function to apply to each item.
	 *                        It should accept two arguments: the accumulator and the current item.
	 * @param mixed $initial The initial value of the accumulator. If not provided, the first item
	 *                        in the collection will be used as the initial value.
	 * @return mixed The final accumulated value after reducing the collection.
	 */
	public function reduce(callable $callback, $initial = null)
	{
		return array_reduce($this->items, $callback, $initial);
	}

	/**
	 * Pluck an array of column values from the collection.
	 *
	 * @param string|array $value The value(s) to pluck.
	 * @param string|null $key The key to use for indexing the resulting array.
	 *
	 * @return static A new collection with the plucked values.
	 */
	public function pluck($value, $key = null)
	{
		$results = [];

		foreach ($this->items as $item) {
			$itemValue = $this->data_get($item, $value);
			$itemKey = $key ? $this->data_get($item, $key) : null;

			if ($key) {
				$results[$itemKey] = $itemValue;
			} else {
				$results[] = $itemValue;
			}
		}

		return new static($results);
	}

	/**
	 * Map a collection and flatten the result by a single level using given callback.
	 *
	 * @param callable $callback The callback function to apply to each item.
	 *                        It should accept two arguments: the item and its index.
	 *                        The callback should return an associative array.
	 *
	 * @return static A new collection with the mapped and flattened values.
	 */
	public function mapWithKeys(callable $callback)
	{
		$result = [];

		foreach ($this->items as $key => $value) {
			$assoc_array = $callback($value, $key);

			foreach ($assoc_array as $mapKey => $mapValue) {
				$result[$mapKey] = $mapValue;
			}
		}

		return new static($result);
	}

	/**
	 * Get the first item from the collection that matches the callback condition.
	 *
	 * If no callback is provided, return the first item in the collection. If the collection is empty,
	 * the default value will be returned instead.
	 *
	 * @param callable|null $callback The callback function to filter the items.
	 * @param mixed $default The default value to return if the collection is empty.
	 *
	 * @return mixed The first item that matches the callback condition, or the default value.
	 */
	public function first(callable $callback = null, $default = null)
	{
		if (is_null($callback)) {
			return count($this->items) > 0 ? reset($this->items) : $this->value($default);
		}

		foreach ($this->items as $key => $value) {
			if (call_user_func($callback, $value, $key)) {
				return $value;
			}
		}

		return $this->value($default);
	}


    /**
     * Determine if the collection is empty or not.
     *
     * @return bool True if the collection is empty, false otherwise.
     */
	public function isEmpty()
	{
		return empty($this->items);
	}

	/**
	 * Check if the collection is not empty.
	 *
	 * @return bool True if the collection is not empty, false otherwise.
	 */
	public function isNotEmpty()
	{
		return !$this->isEmpty();
	}

    /**
     * Get the number of items in the collection.
     *
     * @return int The number of items in the collection.
     */
	public function count()
	{
		return count($this->items);
	}

	/**
	 * Convert the collection to its PHP representation.
	 *
	 * @return array The PHP representation of the collection.
	 */
	public function toArray()
	{
		$items = [];
		foreach($this->items as $key => $object) {
			if(is_object($object)) {
				foreach($object as $keys => $value) {
					$items[$key] = $value;
				}
			}else {
				$items = $object;
			}
				
		}

		return $items;
	}

    /**
     * Convert the collection to its JSON representation.
     *
     * @param int $options The options to pass to json_encode().
     *
     * @return string The JSON representation of the collection.
     */
	public function toJson($options = 0)
	{
		return json_encode($this->toArray(), $options);
	}

    /**
     * Check if a given key exists in the collection.
     *
     * @param string $key The key to look for.
     * @param string|null $operator The operator to use to compare the key to the value.
     * @param mixed $value The value to compare the key to.
     *
     * @return bool True if the key exists, false otherwise.
     */
	public function contains($key, $operator = null, $value = null)
	{
		[$key, $operator, $value] = $this->operatorForWhere($key, $operator, $value);

		return $this->filter(function ($item) use ($key, $operator, $value) {
			return $this->recursiveSearch($item->toArray(), $key, $operator, $value);
		})->isNotEmpty();
	}

	/**
	 * Recursively search an array for a key and compare its value.
	 *
	 * @param array $item The array to search.
	 * @param string $search_key The key to search for.
	 * @param string|null $operator The operator to use to compare the key to the value.
	 * @param string|null $comparing_value The value to compare the key to.
	 *
	 * @return bool True if the key was found and the value matches, false otherwise.
	 */
	protected function recursiveSearch(array $item, string $search_key, ?string $operator = null, ?string $comparing_value = null)
	{
		if(is_array($item) && count($item) > 0) { 
			foreach ($item as $key => $value) {
				if ($key === $search_key) {
					return $this->compare($value, $operator, $comparing_value);
				}

				if (is_array($value) && count($value) > 0) {
					return $this->recursiveSearch($value, $search_key, $operator, $comparing_value);
				}
			}
		}

		return false;
	}


    /**
     * Normalize the comparison operator and value.
     *
     * @param  string  $key
     * @param  string|null  $operator
     * @param  mixed  $value
     *
     * @return array
     */
	protected function operatorForWhere($key, $operator = null, $value = null)
	{
		if (func_num_args() === 2) {
			$value = $operator;
			$operator = '=';
		}

		return [$key, $operator, $value];
	}

    /**
     * Get an item from an array or object using "dot" notation.
     *
     * @param  mixed   $target
     * @param  string|array  $key
     * @param  mixed   $default
     * @return mixed
     */
	function data_get($target, $key, $default = null)
	{
		// If key is null, return the target itself
		if (is_null($key)) {
			return $target;
		}

		if(is_string($key)) {
			// If the key contains a wildcard, handle it
			if (str_contains($key, '*')) {
				return $this->data_get_with_wildcard($target, $key, $default);
			}
		}

		// Split the key using dot notation
		$keys = is_array($key) ? $key : explode('.', $key);

		foreach ($keys as $i => $segment) {
			if (is_array($target)) {
				if (!array_key_exists($segment, $target)) {
					return $this->value($default);
				}
				$target = $target[$segment];
			} elseif (is_object($target)) {
				if (!isset($target->attributes[$segment])) {
					return $this->value($default);
				}
				$target = $target->{$segment};
			} else {
				return $this->value($default);
			}
		}

		return $target;
	}

    /**
     * Get an item from an array or object using "dot" notation, handling wildcards.
     *
     * @param  mixed   $target
     * @param  string|array  $key
     * @param  mixed   $default
     * @return mixed
     */
	function data_get_with_wildcard($target, $key, $default)
	{
		$segments = explode('.', $key);
		$results = [];

		foreach ($segments as $i => $segment) {
			if ($segment === '*') {
				// Handle wildcard (*)
				foreach ($target as $item) {
					$results[] = $this->data_get($item, array_slice($segments, $i + 1), $default);
				}
				return $results;
			} else {
				// Traverse the target as normal
				if (is_array($target) && array_key_exists($segment, $target)) {
					$target = $target[$segment];
				} elseif (is_object($target) && isset($target->{$segment})) {
					$target = $target->{$segment};
				} else {
					return $this->value($default);
				}
			}
		}

		return $target;
	}

    /**
     * If the given value is a closure, call it and return the result.
     * Otherwise, return the value as is.
     *
     * @param  mixed  $value
     * @return mixed
     */
	function value($value)
	{
		return ($value instanceof Closure) ? $value() : $value;
	}

    /**
     * Compare two values based on the given operator.
     *
     * @param mixed $a The first value to compare.
     * @param string $operator The comparison operator.
     * @param mixed $b The second value to compare.
     *
     * @return bool The result of the comparison.
     *
     * @throws InvalidArgumentException If an invalid comparison operator is provided.
     */
	protected function compare($a, $operator, $b)
	{
		switch ($operator) {
			case '=':
			case '==':
				return $a == $b;
			case '!=':
			case '<>':
				return $a != $b;
			case '<':
				return $a < $b;
			case '>':
				return $a > $b;
			case '<=':
				return $a <= $b;
			case '>=':
				return $a >= $b;
			case '===':
				return $a === $b;
			case '!==':
				return $a !== $b;
			default:
				throw new InvalidArgumentException("Invalid comparison operator: $operator");
		}
	}

	/**
	 * Sort the items in the collection.
	 *
	 * @return static A new collection with the items sorted.
	 */
	public function sort()
	{
		$items = $this->items;

		sort($items);

		return new static($items);
	}

	/**
	 * Sort the collection by the specified key in the given direction.
	 *
	 * @param string $key The key to sort the collection by.
	 * @param string $direction The direction of the sorting, defaults to 'asc'.
	 *
	 * @return static A new collection with the items sorted.
	 */
	public function sortBy($key, $direction = 'asc')
	{
		$items = $this->items;

		uasort($items, function ($a, $b) use ($key, $direction) {

			$a = $a->attributes;
			$b = $b->attributes;

			if (!isset($a[$key]) || !isset($b[$key])) {
				return 0;
			}

			if ($direction === 'desc') {
				return $b[$key] <=> $a[$key];
			}

			return $a[$key] <=> $b[$key];
		});

		return new static($items);
	}

	/**
	 * Sort the collection by the specified key in descending order.
	 *
	 * @param string $key The key to sort the collection by.
	 *
	 * @return static A new collection with the items sorted.
	 */
	public function sortByDesc($key)
	{
		return $this->sortBy($key, 'desc');
	}

	/**
	 * Transform each item in the collection using the provided callback.
	 *
	 * The callback function will be applied to each item in the collection.
	 * The modified items will be returned as a new collection.
	 *
	 * @param callable $callback The callback function to apply to each item.
	 *
	 * @return static A new collection with the transformed items.
	 */
	public function transform(callable $callback)
	{
		$items = array_map($callback, $this->items);

		return new static($items);
	}

	/**
	 * Remove duplicate items from the collection.
	 *
	 * The items will be checked for uniqueness using the provided key. If no key is
	 * provided, the items themselves will be checked for uniqueness.
	 *
	 * @param string|null $key The key to check for uniqueness.
	 * @param bool $strict Perform a strict comparison.
	 *
	 * @return static A new collection with the unique items.
	 */
	public function unique($key = null, $strict = false)
	{
		$unique = [];

		foreach ($this->items as $item) {
			$value = $key ? $item->{$key} : $item;

			// Check for uniqueness
			if (in_array($value, $unique, $strict)) {
				continue;
			}

			$unique[] = $value;
		}

		return new static($unique);
	}

	/**
	 * Unwrap the items in the collection.
	 *
	 * If the items in the collection are themselves collections, they will be
	 * unwrapped and the items will be merged together.
	 *
	 * @return static A new collection with the unwrapped items.
	 */
	public function unwrap()
	{
		$unwrap = [];

		foreach ($this->items as $item) {
			if ($item instanceof Collection) {
				$unwrap = array_merge($unwrap, $item->all());
			} else {
				$unwrap[] = $item;
			}
		}

		return new static($unwrap);
	}

	/**
	 * Get all the values from the collection, resetting the keys.
	 *
	 * This method returns a new collection containing all the values
	 * from the current collection, with the keys reset to consecutive
	 * integers starting from 0.
	 *
	 * @return static A new collection with the values from the current collection.
	 */
	public function values()
	{
		return new static(array_values($this->items));
	}

	/**
	 * Make a new collection instance.
	 *
	 * If the provided value is already a collection, it will be returned as is.
	 * If the value is not an array, it will be converted to one.
	 *
	 * @param  mixed  $value The value to make a collection from.
	 * @return static A new collection instance.
	 */
	public static function make($value = [])
	{
		if ($value instanceof Collection) {
			return $value;
		}

		if (!is_array($value)) {
			$value = [$value];
		}

		return new static($value);
	}

	/**
	 * Sum the values of the collection.
	 *
	 * If the collection contains other arrays or objects, you can pass a key
	 * to sum the values of the given key. Otherwise, the sum of the values
	 * of the collection is returned.
	 *
	 * @param  string|null  $key The key to sum.
	 *
	 * @return int The sum of the values of the collection.
	 */
	public function sum($key = null)
	{
		$sum = 0;

		foreach ($this->items as $item) {
			if ($key !== null) {
				$sum += ($key instanceof IModel ? $item->{$key} : $this->items) ?? 0;
			} elseif (is_array($item)) {
				$sum += array_sum($item);
			} else {
				$sum += $key;
			}
		}

		return $sum;
	}

	/**
	 * Calculate the average of the values of the collection.
	 *
	 * If the collection contains other arrays or objects, you can pass a key
	 * to calculate the average of the values of the given key. Otherwise, the
	 * average of the values of the collection is returned.
	 *
	 * @param  string|null  $key The key to average.
	 *
	 * @return float The average of the values of the collection.
	 */
	public function avg($key = null)
	{
		$sum = $this->sum($key);
		$count = $this->count();

		if ($count === 0) {
			return null;
		}

		return $sum / $count;
	}

	/**
	 * Calculate the variance of the values in the collection.
	 *
	 * The variance is a measure of how spread out the values are in the collection.
	 * It is calculated by finding the average squared difference of each value from the mean.
	 *
	 * @param bool $sample If true, calculate the sample variance. If false, calculate the population variance.
	 *
	 * @return float|null The variance of the values in the collection, or null if the collection is empty.
	 */
	public function variance($sample = false)
	{
		$mean = $this->avg();
		$n = $this->count();

		if ($n === 0) {
			return null;
		}

		$sum = $this->sum(function ($value) use ($mean) {
			return pow($value - $mean, 2);
		});

		if ($sample) {
			return $sum / ($n - 1);
		} else {
			return $sum / $n;
		}
	}

	/**
	 * Get the minimum value in the collection.
	 *
	 * If a key is provided, the minimum value of that key will be returned.
	 *
	 * @param string|array|null $key The key to get the minimum value of, or null to get the minimum value of the entire item.
	 *
	 * @return mixed The minimum value of the collection, or null if the collection is empty.
	 */
	public function min($key = null)
	{
		return $this->reduce(function ($carry, $item) use ($key) {
			if ($key !== null) {
				$item = $item[$key] ?? null;
			}

			return $carry === null || $item < $carry ? $item : $carry;
		}, null);
	}

	/**
	 * Get the maximum value in the collection.
	 *
	 * If a key is provided, the maximum value of that key will be returned.
	 *
	 * @param string|array|null $key The key to get the maximum value of, or null to get the maximum value of the entire item.
	 *
	 * @return mixed The maximum value of the collection, or null if the collection is empty.
	 */
	public function max($key = null)
	{
		return $this->reduce(function ($carry, $item) use ($key) {
			if ($key !== null) {
				$item = $item[$key] ?? null;
			}

			return $carry === null || $item > $carry ? $item : $carry;
		}, null);
	}

	/**
	 * Get the item at the specified index in the collection.
	 *
	 * @param int $n The index of the item to retrieve.
	 *
	 * @return mixed|null The item at the specified index, or null if the index is out of bounds.
	 */
	public function nth($n)
	{
		return $this->items[$n] ?? null;
	}

}