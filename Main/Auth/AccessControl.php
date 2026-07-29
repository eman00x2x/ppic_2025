<?php

namespace EO\Auth;

class AccessControl
{
	// List of predefined roles
	private const ROLES = [
		'Administrator',
		'Super Administrator',
		'Editor',
		'Author',
		'Contributor',
		'Support Staff',
		'Registered User',
		'Organization'
	];

	// List of predefined permissions
	public const PERMISSIONS = [
		"accounts" => ["my_account", "manage_accounts", "add_accounts", "edit_accounts", "view_accounts", "delete_accounts", "download_accounts"],
		"properties" => ["add_properties", "edit_properties", "view_properties", "set_category", "set_status", "delete", "delete_property_image", "download_properties"],
		"leads" => ["add_leads", "edit_leads", "view_leads", "set_source", "delete", "download_leads"],
		"articles" => ["add_articles", "edit_articles", "view_articles", "set_category", "set_status", "delete", "download_articles"],
		"settings" => ["manage_settings", "update_system_settings", "update_web_settings", "update_data_privacy", "update_terms", "update_refund_policy", "update_community_guidelines"],
		"traffics" => ["access_traffics", "delete_traffics"],
		"database" => ["access_administration"]
	];

	public const DEFAULT_PERMISSIONS = [
		"accounts" => ["my_account"],
		"properties" => ["add_properties", "edit_properties", "view_properties", "set_category", "set_status", "delete_property_image", "download_properties"],
		"leads" => ["add_leads", "edit_leads", "view_leads", "set_source", "delete", "download_leads"],
		"traffics" => ["access_traffics"]
	];

	public $userRole;
	public $userPermissions = [];

	protected $abilities = [];

	/**
	 * Sets the user permissions.
	 *
	 * @param array $userPermissions The user permissions to set.
	 * @return void
	 */
	public function setUserPermissions($userPermissions) 
	{
		if(is_null($userPermissions) || $userPermissions == "") {
			return [];
		}

		$this->userPermissions = $userPermissions;
	}

	/**
	 * Gets the user permissions.
	 *
	 * @return array The user permissions.
	 */
	public function getUserPermissions() 
	{
		return $this->userPermissions;
	}

	/**
	 * Adds a new permission to the user's permissions array.
	 *
	 * @param array $new_permission The new permission to be added.
	 * @return void
	 */
	public function addPermission(array $new_permission) 
	{
		$this->userPermissions[$new_permission];
	}

	/**
	 * Checks if the user has the specified permission in the given application.
	 *
	 * @param string $permission The permission to check for.
	 * @param string $module The application to check the permission in.
	 * @return bool True if the user has the permission, false otherwise.
	 */
	public function userHasPermission(string $permission, string $module) 
	{
		if(!isset($this->userPermissions[$module])) {
			return false;
		}

		if(!in_array($permission, $this->userPermissions[$module])) {
			return false;
		}
		return true;
	}

	public function setUserRole(string $role): void
	{
		$this->userRole = $role;
	}

	public function getUserRole(): string
	{
		return $this->userRole;
	}

	public function userHasRole(string $role): bool
	{
		return $this->userRole === $role;
	}

	/**
	 * Returns an array of all available permissions.
	 *
	 * @return array An array of all available permissions.
	 */
	public function definePermissions() 
	{
		return self::PERMISSIONS;
	}

	public function defineDefaultPermissions() 
	{
		return self::DEFAULT_PERMISSIONS;
	}

	/**
	 * Defines an ability and associates it with a callback function.
	 *
	 * @param string $ability The name of the ability to define.
	 * @param callable $callback The callback function to associate with the ability.
	 * @return void
	 */
	public function define(string $ability,  callable $callback) 
	{
		$this->abilities[$ability] = $callback;
	}

	/**
	 * Checks if the given ability is allowed and executes the corresponding callback function if it exists.
	 *
	 * @param string $ability The ability to check.
	 * @param mixed ...$args The arguments to pass to the callback function.
	 * @return bool Returns true if the ability is allowed, false otherwise.
	 */
	public function allows($ability, ...$args) 
	{
		if(isset($this->abilities[$ability])) {
			return call_user_func_array($this->abilities[$ability], $args);
		}

		return false;
	}

	/**
	 * Checks if the given ability is allowed.
	 *
	 * @param string $ability The ability to check.
	 * @param mixed ...$args The arguments to pass to the callback function.
	 * @return bool Returns true if the ability is allowed, false otherwise.
	 */
	public function can($ability, ...$args) 
	{
		return $this->allows($ability, ...$args);
	}

}