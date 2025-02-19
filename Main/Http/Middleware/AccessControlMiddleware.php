<?php

namespace EO\Http\Middleware;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request as Request;
use EO\Auth\Auth as Auth;
use EO\Auth\AccessControl as AccessControl;

class AccessControlMiddleware implements IMiddleware 
{
    /**
	 * @param Request $request
	 */
	public function handle(Request $request): void  
	{
		$user = Auth::user();
		if(Auth::check()) {
			$request->authenticated = (array) $user; 
			$this->setUserRole( $request->authenticated['account']['account_type'] );
			$this->initiatePermissions();
		}
    }

    private function initiatePermissions()
	{
		foreach (Auth::getUserPermissions() as $module => $permissions) {
			foreach ($permissions as $permission_name) {
				Auth::define($permission_name, function (array $user, ?array $appData = []) use ($module, $permission_name) {
					$user_permissions = $user['permissions'][$module] ?? [];
					return in_array($permission_name, $user_permissions, true) || ($appData['account']['account_id'] === $user['account_id']);
				});
			}
		}
	}

	private function setUserRole(string $role): void
	{
		Auth::setUserRole($role);
	}
}