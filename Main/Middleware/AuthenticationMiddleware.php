<?php

namespace Main\Middleware;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request as Request;
use Main\Services\SessionService as Session;

/**
 * Class AuthenticationMiddleware
 */
class AuthenticationMiddleware implements IMiddleware {

    /**
     * @param Request $request
     */
    public function handle(Request $request): void  {
        
        $session = new Session();

        if(session_status() === PHP_SESSION_NONE) {
            $session->sessionHandler->start();
        }

        if(url()->contains("Admin")) {
            $request->template = ROOT . "/Resources/Templates/admin/template.php";
        }

        $request->authenticated = $session->monitor(); 

        if($request->authenticated === false) {
 
            if(url()->contains("Admin")) {
               $request->template = ROOT . "/Resources/Templates/admin/login.template.php";
            }

            $request->setRewriteUrl(url("LoginController@getLoginForm"));
        }

    }

}