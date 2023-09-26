<?php

namespace App\Contracts;

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

interface PermissionsRepositoryInterface
{
    /**
     * @param  Permission  $permission
     * @param  string|Role  $role
     * @return bool
     */
    public function assignPermissionToRole(Permission $permission, string|Role $role): bool;

    /**
     * @param  User  $user
     * @param  string|Role  $role
     * @return bool
     */
    public function assignUserToRole(User $user, string|Role $role): bool;

    /**
     * @param  Permission  $permission
     * @param  string|Role  $role
     * @return bool
     */
    public function revokePermissionToRole(Permission $permission, string|Role $role): bool;

    /**
     * @param  User  $user
     * @param  string|Role  $role
     * @return bool
     */
    public function revokeUserToRole(User $user, string|Role $role): bool;
}
