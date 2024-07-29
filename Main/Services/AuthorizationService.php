<?php

namespace Main\Services;

use Pecee\Http\Request as Request;

class AuthorizationService
{

    private Request $request;

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
    private const PERMISSIONS = [
        'manage_users',
        'edit_content',
        'delete_content',
        'view_reports',
        'manage_orders',
        'manage_products',
        'view_content',
        'publish_content',
        'provide_support',
        'access_premium_content'
    ];

    // Mapping of roles to permissions
    private $rolePermissions = [
        'Administrator' => ['manage_users', 'edit_content', 'delete_content', 'view_reports'],
        'Super Administrator' => ['manage_users', 'edit_content', 'delete_content', 'view_reports', 'manage_orders', 'manage_products'],
        'Editor' => ['edit_content', 'publish_content'],
        'Author' => ['edit_content'],
        'Contributor' => ['edit_content'],
        'Support Staff' => ['provide_support'],
        'Registered User' => ['view_content', 'access_premium_content'],
        'Organization' => ['manage_users', 'edit_content', 'delete_content', 'view_reports']
    ];

    // Array to store users and their roles
    private $userRoles = [];

    public $user;
    
	function __construct(Request $request) {
		
        $this->request = $request;

        if($this->request->authenticated) {
            $this->user = $this->request->authenticated['account'];
            $this->addUserRole($this->user['account_type']);
        }

	}

    // Add a role to a user
    public function addUserRole($role)
    {
        if (!in_array($role, self::ROLES)) {
            throw new InvalidArgumentException("Role $role does not exist.");
        }

        if (!isset($this->userRoles[ $this->user['username'] ])) {
            $this->userRoles[ $this->user['username'] ] = [];
        }

        if (!in_array($role, $this->userRoles[$this->user['username']])) {
            $this->userRoles[$this->user['username']][] = $role;
        }
    }

    // Remove a role from a user
    public function removeUserRole($role)
    {
        if (isset($this->userRoles[ $this->user['username'] ])) {
            $this->userRoles[ $this->user['username'] ] = array_diff($this->userRoles[ $this->user['username'] ], [$role]);
        }
    }

    // Check if a user has a specific role
    public function userHasRole($role)
    {
        return isset($this->userRoles[ $this->user['username'] ]) && in_array($role, $this->userRoles[ $this->user['username'] ]);
    }

    // Get all roles of a user
    public function getUserRoles()
    {
        return isset($this->userRoles[ $this->user['username'] ]) ? $this->userRoles[ $this->user['username'] ] : [];
    }

    // Check if a user has any role from a list of roles
    public function userHasAnyRole($roles)
    {
        if (!isset($this->userRoles[ $this->user['username'] ])) {
            return false;
        }

        foreach ($roles as $role) {
            if (in_array($role, $this->userRoles[ $this->user['username'] ])) {
                return true;
            }
        }

        return false;
    }

    // Check if a user has all roles from a list of roles
    public function userHasAllRoles($roles)
    {
        if (!isset($this->userRoles[ $this->user['username'] ])) {
            return false;
        }

        foreach ($roles as $role) {
            if (!in_array($role, $this->userRoles[ $this->user['username'] ])) {
                return false;
            }
        }

        return true;
    }

    // Check if a user has a specific permission
    public function userHasPermission($permission)
    {
        if (!in_array($permission, self::PERMISSIONS)) {
            throw new InvalidArgumentException("Permission $permission does not exist.");
        }

        $roles = $this->getUserRoles( $this->user['username'] );
        foreach ($roles as $role) {
            if (in_array($permission, $this->rolePermissions[$role])) {
                return true;
            }
        }

        return false;
    }

    // Get all permissions of a user
    public function getUserPermissions()
    {
        $permissions = [];
        $roles = $this->getUserRoles( $this->user['username'] );
        foreach ($roles as $role) {
            $permissions = array_merge($permissions, $this->rolePermissions[$role]);
        }
        return array_unique($permissions);
    }
    
}