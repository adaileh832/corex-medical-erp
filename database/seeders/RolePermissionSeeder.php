<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'Manage Users', 'slug' => 'manage-users', 'group_name' => 'users'],
            ['name' => 'Manage Roles', 'slug' => 'manage-roles', 'group_name' => 'roles'],
            ['name' => 'Manage Settings', 'slug' => 'manage-settings', 'group_name' => 'settings'],
            ['name' => 'Manage Patients', 'slug' => 'manage-patients', 'group_name' => 'patients'],
            ['name' => 'Manage Doctors', 'slug' => 'manage-doctors', 'group_name' => 'doctors'],
            ['name' => 'Manage Procedures', 'slug' => 'manage-procedures', 'group_name' => 'procedures'],
            ['name' => 'Manage Operations', 'slug' => 'manage-operations', 'group_name' => 'operations'],
            ['name' => 'View Doctor Statements', 'slug' => 'view-doctor-statements', 'group_name' => 'doctors'],
            ['name' => 'Manage Doctor Payments', 'slug' => 'manage-doctor-payments', 'group_name' => 'doctors'],
            ['name' => 'Manage Invoices', 'slug' => 'manage-invoices', 'group_name' => 'invoices'],
            ['name' => 'Print Invoices', 'slug' => 'print-invoices', 'group_name' => 'invoices'],
            ['name' => 'Manage Payments', 'slug' => 'manage-payments', 'group_name' => 'payments'],
            ['name' => 'Manage Suppliers', 'slug' => 'manage-suppliers', 'group_name' => 'suppliers'],
            ['name' => 'View Accounting', 'slug' => 'view-accounting', 'group_name' => 'accounting'],
            ['name' => 'Manage Accounting', 'slug' => 'manage-accounting', 'group_name' => 'accounting'],
            ['name' => 'View Reports', 'slug' => 'view-reports', 'group_name' => 'reports'],
        ];

        foreach ($permissions as $permission) {
            Permission::query()->updateOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }

        $manager = Role::query()->updateOrCreate(
            ['slug' => 'manager'],
            [
                'name' => 'Manager',
                'description' => 'Full system access',
            ]
        );

        $reception = Role::query()->updateOrCreate(
            ['slug' => 'reception'],
            [
                'name' => 'Reception',
                'description' => 'Daily operational access',
            ]
        );

        $manager->permissions()->sync(Permission::query()->pluck('id')->all());

        $receptionPermissionSlugs = [
            'manage-patients',
            'manage-invoices',
            'print-invoices',
            'manage-payments',
            'manage-operations',
            'view-doctor-statements',
        ];

        $receptionPermissionIds = Permission::query()
            ->whereIn('slug', $receptionPermissionSlugs)
            ->pluck('id')
            ->all();

        $reception->permissions()->sync($receptionPermissionIds);
    }
}