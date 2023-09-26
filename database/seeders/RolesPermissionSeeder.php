<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->roleNames() as $roleName) {
            $permissionName = $roleName.'_permission';
            $permission = Permission::updateOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);
            Role::updateOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);
            $role = Role::findByName($roleName);
            $role->givePermissionTo($permission);
        }
    }

    private function roleNames()
    {
        return [
            'SuperAdmin',
            'Admin',
            'Installer',
            'Farmer',
        ];
    }
}
