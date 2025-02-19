<?php

namespace EO\Auth;

use EO\Auth\TokenGuardian;
use EO\Auth\SessionGuardian;
use EO\Auth\AccessControl;

class Auth
{
	protected static $guard;
	protected static $accessControl;
	protected static $tokenGuardian;

	private static $selectedGuard;

	/**
	 * Sets the guardian for the authentication.
	 *
	 * @param String $selected_guard The guardian to set.
	 * @return Auth A new instance of the Auth class.
	 */
	public static function guardian(String $selected_guard): Auth {
		self::$selectedGuard = $selected_guard;
		return new Auth;
	}

	/**
	 * Sets the guard for the authentication.
	 *
	 * @param SessionGuardian $guard The guard to set.
	 * @return void
	 */
	public static function setGuard(SessionGuardian $guard) {
		self::$guard = $guard;
	}

	/**
	 * Attempts to authenticate a user using the given credentials.
	 *
	 * @param array $credentials The user's credentials.
	 * @return bool Returns true if the authentication is successful, false otherwise.
	 */
	public static function attempt(array $credentials) {

		if (self::$guard === null) {
			throw new \Exception('No guard has been set.');
		}
		
		return self::$guard->attempt($credentials);
	}

	/**
	 * Retrieves the authenticated user from the session guard.
	 *
	 * @return mixed The authenticated user or null if no user is authenticated.
	 */
	public static function user() {
		
		if(self::$selectedGuard !== null && self::$selectedGuard == "api") {
			return self::$tokenGuardian->user();
		}

		return self::$guard->user();
	}

	/**
	 * Checks if the user is authenticated.
	 *
	 * @return bool Returns true if the user is authenticated, false otherwise.
	 */
	public static function check() {

		if(self::$selectedGuard !== null && self::$selectedGuard == "api") {
			return self::$tokenGuardian->check();
		}

		return self::$guard->check();
	}

	public static function isAdmin() {

		if(self::$selectedGuard !== null && self::$selectedGuard == "api") {
			return self::$tokenGuardian->isAdmin();
		}

		return self::$guard->isAdmin();

	}

	public static function getDomain() {
		return self::$guard->getDomain();
	}

	/**
	 * Logs out the user by calling the logout method on the session guard.
	 *
	 * @return void
	 */
	public static function logout() {
		self::$guard->logout();
	}

	public static function forceLogout() {
		self::$guard->forceLogout();
	}


	/******************* ACCESS CONTROL */

	/**
	 * Sets the access control for the authentication system.
	 *
	 * @param AccessControl $access_control The access control instance to set.
	 * @return void
	 */
	public static function setAccessControl(AccessControl $access_control) {
		self::$accessControl = $access_control;
	}

	/**
	 * Retrieves the defined permissions from the access control system.
	 *
	 * @return array The defined permissions.
	 */
	public static function definePermissions() {
		return self::$accessControl->definePermissions();
	}

	public static function defineDefaultPermissions()
	{
		return self::$accessControl->defineDefaultPermissions();
	}

	/**
	 * Sets the user permissions for the access control system.
	 *
	 * @param array $user_permissions An array of user permissions to set.
	 * @return void
	 */
	public static function setUserPermissions($user_permissions) {
		self::$accessControl->setUserPermissions($user_permissions);
	}

	/**
	 * Retrieves the user permissions from the access control system.
	 *
	 * @return array The user permissions.
	 */
	public static function getUserPermissions() {
		return self::$accessControl->getUserPermissions();
	}

	/**
	 * Adds a new permission to the access control system.
	 *
	 * @param array $new_permission The new permission to be added.
	 * @return void
	 */
	public static function addPermission(array $new_permission) {
		self::$accessControl->addPermission($new_permission);
	}

	/**
	 * Checks if the user has the specified permission in the given application.
	 *
	 * @param string $permission The permission to check for.
	 * @param string $app The application to check the permission in.
	 * @return bool Returns true if the user has the permission, false otherwise.
	 */
	public static function userHasPermission(string $permission, string $app) {
		return self::$accessControl->userHasPermission($permission, $app);
	}

	/**
	 * Sets the user role in the access control system.
	 *
	 * @param string $role The role to set for the user.
	 * @return void
	 */
	public static function setUserRole(string $role): void
	{
		self::$accessControl->setUserRole($role);
	}

	/**
	 * Retrieves the user role from the access control system.
	 *
	 * @return string The user role.
	 */
	public static function getUserRole(): string
	{
		return self::$accessControl->getUserRole();
	}

	/**
	 * Checks if the user has the specified role.
	 *
	 * @param string $role The role to check for.
	 * @return bool Returns true if the user has the role, false otherwise.
	 */
	public static function userHasRole(string $role): bool
	{
		return self::$accessControl->userHasRole($role);
	}

	/**
	 * Defines an ability and associates it with a callback function.
	 *
	 * @param string $ability The name of the ability to define.
	 * @param callable $callback The callback function to associate with the ability.
	 * @return void
	 */
	public static function define(string $ability,  callable $callback) {
		self::$accessControl->define($ability, $callback);
	}

	/**
	 * Checks if the given ability is allowed and executes the corresponding callback function if it exists.
	 *
	 * @param string $ability The ability to check.
	 * @param mixed ...$arguments The arguments to pass to the callback function.
	 * @return bool Returns true if the ability is allowed, false otherwise.
	 */
	public static function allows(string $ability, ...$arguments) {
		return self::$accessControl->allows($ability, ...$arguments);
	}

	/**
	 * Checks if the user has the ability to perform a certain action.
	 *
	 * @param string $ability The ability to check.
	 * @param mixed ...$arguments Additional arguments to pass to the ability check.
	 * @return bool Returns true if the user has the ability, false otherwise.
	 */
	public static function can(string $ability, ...$arguments) {
		return self::$accessControl->can($ability, ...$arguments);
	}

	/******************* TOKEN GUARDIAN */

	public static function setTokenGuardian(TokenGuardian $token_guardian) {
		self::$tokenGuardian = $token_guardian;
	}

	public function validate(array $credentials = []) {
		return self::$tokenGuardian->validate($credentials);
	}

}