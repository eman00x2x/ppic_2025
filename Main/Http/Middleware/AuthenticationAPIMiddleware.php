<?php

namespace EO\Http\Middleware;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request as Request;
use EO\Auth\Auth as Auth;
use EO\Auth\TokenGuardian as TokenGuardian;
use EO\Auth\AccessControl as AccessControl;

/**
 * Class APIAccessMiddleware
 */
class AuthenticationAPIMiddleware implements IMiddleware {

	/**
	 * @param Request $request
	 */
	public function handle(Request $request): void  {

		$user = Auth::guardian("api")->user();

		if(Auth::check()) {

			$request->authenticated = (array) $user; 
			
			Auth::setAccessControl( new AccessControl() );
			Auth::setUserPermissions( $request->authenticated['permissions'] );
		}
	}

}