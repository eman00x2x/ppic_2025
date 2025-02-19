<?php

namespace EO\Http\Middleware;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request as Request;

/**
 * Class MaintenanceMiddleware
 */
class MaintenanceMiddleware implements IMiddleware 
{
	/**
	 * @param Request $request
	 */
	public function handle(Request $request): void  
	{
		if(MAINTENANCE) {
			throw new MaintenanceException('We will be right back with awesome features.');
		}
	}
}