<?php

namespace EO\Http\Middleware;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request as Request;
use EO\Auth\Auth;
use EO\Auth\AccessControl as AccessControl;

/**
 * Class AuthenticationMiddleware
 */
class AuthenticationMiddleware implements IMiddleware 
{
	/**
	 * @param Request $request
	 */
	public function handle(Request $request): void  
	{
		$user = Auth::user();
		if(Auth::check()) {
			$request->authenticated = (array) $user; 
			
			Auth::setAccessControl( new AccessControl() );
			Auth::setUserPermissions( $request->authenticated['account']['permissions'] );
		}else {
			redirect(url("login"));
		}

		if(input()->get("logout") || url()->contains("logout")) {
			Auth::logout();
			unset($request->authenticated);
			redirect(url("login"));
		}
	}
}