<?php

namespace EO\Http\Middleware;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request as Request;
use EO\Auth\Auth as Auth;
use Josantonius\Session\Session;

class TwoFactorAuthenticationMiddleware implements IMiddleware 
{
    /**
	 * @param Request $request
	 */
	public function handle(Request $request): void  
	{
		$session = new Session;

		if(!$session->has("two_factor_auth")) redirect(url("twoFactorAuthentication"));

		if($session->get("two_factor_auth")['authorized']) return;
		
		redirect(url("twoFactorAuthentication"));
    }

}