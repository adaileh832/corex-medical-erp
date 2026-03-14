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
            ['name' => 'View Doctor Statements', 'slug' => 'view-doctor-statements', 'group_name' => 'doctors'],
            ['name' => 'Manage Doctor Payments', 'slug' => 'manage-doctor-payments', 'group_name' => 'doctors'],

            ['name' => 'Manage Procedures', 'slug' => 'manage-procedures', 'group_name' => 'procedures'],
            ['name' => 'Manage Operations', 'slug' => 'manage-operations', 'group_name' => 'operations'],

            ['name' => 'Manage Invoices', 'slug' => 'manage-invoices', 'group_name' => 'invoices'],
            ['name' => 'Print Invoices', 'slug' => 'print-invoices', 'group_name' => 'invoices'],
            ['name' => 'Manage Payments', 'slug' => 'manage-payments', 'group_name' => 'payments'],

            ['name' => 'Manage Suppliers', 'slug' => 'manage-suppliers', 'group_name' => 'suppliers'],
            ['name' => 'Manage Supplier Invoices', 'slug' => 'manage-supplier-invoices', 'group_name' => 'suppliers'],
            ['name' => 'Manage Supplier Payments', 'slug' => 'manage-supplier-payments', 'group_name' => 'suppliers'],
            ['name' => 'View Supplier Statements', 'slug' => 'view-supplier-statements', 'group_name' => 'suppliers'],

            ['name' => 'Manage Inventory Items', 'slug' => 'manage-inventory-items', 'group_name' => 'inventory'],
            ['name' => 'Manage Stock Movements', 'slug' => 'manage-stock-movements', 'group_name' => 'inventory'],
            ['name' => 'View Inventory Reports', 'slug' => 'view-inventory-reports', 'group_name' => 'inventory'],

            ['name' => 'Manage Employees', 'slug' => 'manage-employees', 'group_name' => 'hr'],
            ['name' => 'Manage Attendance', 'slug' => 'manage-attendance', 'group_name' => 'hr'],
            ['name' => 'Manage Leave Requests', 'slug' => 'manage-leave-requests', 'group_name' => 'hr'],
            ['name' => 'View Attendance Reports', 'slug' => 'view-attendance-reports', 'group_name' => 'hr'],
            ['name' => 'Manage Payroll', 'slug' => 'manage-payroll', 'group_name' => 'hr'],
            ['name' => 'View Payroll', 'slug' => 'view-payroll', 'group_name' => 'hr'],

            ['name' => 'Manage Accounts', 'slug' => 'manage-accounts', 'group_name' => 'accounting'],
            ['name' => 'Manage Journal Entries', 'slug' => 'manage-journal-entries', 'group_name' => 'accounting'],
            ['name' => 'Manage Cash Vouchers', 'slug' => 'manage-cash-vouchers', 'group_name' => 'accounting'],
            ['name' => 'Manage Bank Transactions', 'slug' => 'manage-bank-transactions', 'group_name' => 'accounting'],
            ['name' => 'View Accounting Reports', 'slug' => 'view-accounting-reports', 'group_name' => 'accounting'],

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
            'manage-suppliers',
            'manage-supplier-invoices',
            'manage-supplier-payments',
            'view-supplier-statements',
            'manage-inventory-items',
            'manage-stock-movements',
            'view-inventory-reports',
            'manage-employees',
            'manage-attendance',
            'manage-leave-requests',
            'view-attendance-reports',
            'view-payroll',
            'view-accounting-reports',
        ];

        $receptionPermissionIds = Permission::query()
            ->whereIn('slug', $receptionPermissionSlugs)
            ->pluck('id')
            ->all();

        $reception->permissions()->sync($receptionPermissionIds);
    }
}