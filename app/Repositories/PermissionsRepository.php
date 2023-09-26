<?php

namespace App\Repositories;

use App\Contracts\PermissionsRepositoryInterface;
use App\Models\User;
use Psr\Log\LoggerInterface;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsRepository implements PermissionsRepositoryInterface
{
    /**
     * @param  LoggerInterface  $logger
     */
    public function __construct(protected LoggerInterface $logger)
    {
    }

    /**
     * @param  string|Permission  $permission
     * @param  string|Role  $role
     * @return bool
     */
    public function assignPermissionToRole(string|Permission $permission, string|Role $role): bool
    {
        try {
            $role = is_string($role) ? Role::findByName($role) : $role;
            $permission = is_string($permission) ? Permission::findByName($permission) : $permission;
            $role->givePermissionTo($permission);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());

            return false;
        }

        return true;
    }

    /**
     * @param  int|User  $user
     * @param  string|Role  $role
     * @return bool
     */
    public function assignUserToRole(int|User $user, string|Role $role): bool
    {
        try {
            $role = is_string($role) ? Role::findByName($role) : $role;
            $user = is_int($user) ? User::find($user) : $user;
            if ($user->hasRole($role)) {
                return true;
            }
            $user->roles()->detach();
            $user->assignRole($role);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());

            return false;
        }

        return true;
    }

    /**
     * @param  string|Permission  $permission
     * @param  string|Role  $role
     * @return bool
     */
    public function revokePermissionToRole(string|Permission $permission, string|Role $role): bool
    {
        try {
            $role = is_string($role) ? Role::findByName($role) : $role;
            $permission = is_string($permission) ? Permission::findByName($permission) : $permission;
            $role->revokePermissionTo($permission);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());

            return false;
        }

        return true;
    }

    /**
     * @param  int|User  $user
     * @param  string|Role  $role
     * @return bool
     */
    public function revokeUserToRole(int|User $user, string|Role $role): bool
    {
        try {
            $role = is_string($role) ? Role::findByName($role) : $role;
            $user = is_int($user) ? User::find($user) : $user;
            $user->removeRole($role);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());

            return false;
        }

        return true;
    }
}
