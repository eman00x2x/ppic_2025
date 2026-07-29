<?php

namespace EO;

use EO\Model;
use EO\Handlers\Exceptions\ValidationException;
use EO\Handlers\Exceptions\ResourceNotFoundException;
use EO\Factories\Factory as Factory;
use EO\Validation\Validator as Validator;
use EO\Handlers\CacheHandler;
use EO\Facades\EventFacade as Event;
use EO\Facades\FileSystemFacade as FileSystem;
use EO\Facades\CacheFacade;

/**
 * Service class for handling logic related operations.
 */
class Service
{
	public Validator $validator;
	public static $collections;
	
	function __construct()
	{
		if ($_ENV['CACHE_ENABLE']) {
			CacheFacade::setCache(new CacheHandler());
		}

		/* $this->validator = Factory::Validator()->resetConstraints(); */
		$this->validator = new Validator();
	}

	/**
	 * @param callable $function The function to be called.
	 * @param mixed $param The parameter to be passed to the function.
	 * @return mixed The result of the function calls.
	 */
	protected function helper(callable $function, $param) 
	{
		return helper($function, $param);
	}
	
	public function validateInput($data) 
	{
		if($this->validator->validate($data) === false) {
			throw new ValidationException($this->validator->getErrors());
		}
		return $this->validator->getValidatedData();
	}

	public function upload($data = null, $params = ["file_type" => "image"]) 
	{
		return FileSystem::upload($data, $params);
	}

	public function log(array $data): void
	{
		$data['data'] = [
			'route' => url()->getAbsoluteUrl(),
			'data' => $data
		];

		Event::dispatch('logs.action', $data);
	}

}
