<?php

namespace Main;

/**
 * View class for handling view related operations.
 */
class Service
{

	function getModel($model, $options = []) {
		$class = "\\Main\Model\\".$model."Model";
		return new $class;
	}

    /**
	 * This function is a helper function to call other functions dynamically.
	 * @param callable $function The name of the function to be called. It should be a valid callable function.
	 * @param mixed $param The parameter to be passed to the function. It can be of any type.
	 * @return mixed The result of the called function. The return type depends on the function being called.
	 * @throws Exception If the function does not exist.
	 * @throws InvalidArgumentException If the first parameter is not a callable function.
	 */
	function helper(callable $function, $param = []) {
		if(is_callable($function)) {
			return $function($param);
		} else {
			throw new InvalidArgumentException("$function must be a callable function.");
		}
	}
    
}
