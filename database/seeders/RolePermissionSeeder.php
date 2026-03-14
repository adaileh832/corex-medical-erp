<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = [
            'admin',
            'doctor',
            'reception',
            'accountant',
            'hr',
            'inventory',
        ];

        $permissions = [
            'view dashboard',

            'view patients',
            'create patients',
            'edit patients',
            'delete patients',

            'view doctors',
            'create doctors',
            'edit doctors',
            'delete doctors',

            'view invoices',
            'create invoices',
            'edit invoices',
            'delete invoices',

            'view inventory',
            'create inventory',
            'edit inventory',
            'delete inventory',

            'view employees',
            'create employees',
            'edit employees',
            'delete employees',
        ];

        foreach ($roles as $roleName) {
            $role = DB::table('roles')->where('name', $roleName)->first();

            $roleData = [
                'name' => $roleName,
            ];

            if (Schema::hasColumn('roles', 'guard_name')) {
                $roleData['guard_name'] = 'web';
            }

            if (Schema::hasColumn('roles', 'slug')) {
                $roleData['slug'] = Str::slug($roleName);
            }

            if (Schema::hasColumn('roles', 'updated_at')) {
                $roleData['updated_at'] = now();
            }

            if (! $role) {
                if (Schema::hasColumn('roles', 'created_at')) {
                    $roleData['created_at'] = now();
                }

                DB::table('roles')->insert($roleData);
            } else {
                DB::table('roles')->where('id', $role->id)->update($roleData);
            }
        }

        foreach ($permissions as $permissionName) {
            $permission = DB::table('permissions')->where('name', $permissionName)->first();

            $permissionData = [
                'name' => $permissionName,
            ];

            if (Schema::hasColumn('permissions', 'guard_name')) {
                $permissionData['guard_name'] = 'web';
            }

            if (Schema::hasColumn('permissions', 'slug')) {
                $permissionData['slug'] = Str::slug($permissionName);
            }

            if (Schema::hasColumn('permissions', 'updated_at')) {
                $permissionData['updated_at'] = now();
            }

            if (! $permission) {
                if (Schema::hasColumn('permissions', 'created_at')) {
                    $permissionData['created_at'] = now();
                }

                DB::table('permissions')->insert($permissionData);
            } else {
                DB::table('permissions')->where('id', $permission->id)->update($permissionData);
            }
        }

        $adminRole = DB::table('roles')->where('name', 'admin')->first();

        if ($adminRole && Schema::hasTable('role_has_permissions')) {
            $permissionIds = DB::table('permissions')->pluck('id');

            foreach ($permissionIds as $permissionId) {
                $exists = DB::table('role_has_permissions')
                    ->where('permission_id', $permissionId)
                    ->where('role_id', $adminRole->id)
                    ->exists();

                if (! $exists) {
                    DB::table('role_has_permissions')->insert([
                        'permission_id' => $permissionId,
                        'role_id' => $adminRole->id,
                    ]);
                }
            }
        }
    }
}