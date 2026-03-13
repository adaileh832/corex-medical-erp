<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {

        $permissions = [

            // USERS
            ['name'=>'Manage Users','slug'=>'manage-users','group_name'=>'users'],

            // SETTINGS
            ['name'=>'Manage Settings','slug'=>'manage-settings','group_name'=>'settings'],

            // PATIENTS
            ['name'=>'Manage Patients','slug'=>'manage-patients','group_name'=>'patients'],

            // DOCTORS
            ['name'=>'Manage Doctors','slug'=>'manage-doctors','group_name'=>'doctors'],

            // PROCEDURES
            ['name'=>'Manage Procedures','slug'=>'manage-procedures','group_name'=>'procedures'],

            // OPERATIONS
            ['name'=>'Manage Operations','slug'=>'manage-operations','group_name'=>'operations'],

            // DOCTOR STATEMENTS
            ['name'=>'View Doctor Statements','slug'=>'view-doctor-statements','group_name'=>'doctors'],

            // DOCTOR PAYMENTS
            ['name'=>'Manage Doctor Payments','slug'=>'manage-doctor-payments','group_name'=>'doctors'],

            // INVOICES
            ['name'=>'Manage Invoices','slug'=>'manage-invoices','group_name'=>'invoices'],

            ['name'=>'Print Invoices','slug'=>'print-invoices','group_name'=>'invoices'],

            // PAYMENTS
            ['name'=>'Manage Payments','slug'=>'manage-payments','group_name'=>'payments'],

            // SUPPLIERS
            ['name'=>'Manage Suppliers','slug'=>'manage-suppliers','group_name'=>'suppliers'],

            ['name'=>'Manage Supplier Invoices','slug'=>'manage-supplier-invoices','group_name'=>'suppliers'],

            ['name'=>'Manage Supplier Payments','slug'=>'manage-supplier-payments','group_name'=>'suppliers'],

            ['name'=>'View Supplier Statements','slug'=>'view-supplier-statements','group_name'=>'suppliers'],

            // ACCOUNTING
            ['name'=>'View Accounting','slug'=>'view-accounting','group_name'=>'accounting'],

            ['name'=>'Manage Accounting','slug'=>'manage-accounting','group_name'=>'accounting'],

            // REPORTS
            ['name'=>'View Reports','slug'=>'view-reports','group_name'=>'reports'],

        ];

        foreach($permissions as $permission){

            Permission::updateOrCreate(
                ['slug'=>$permission['slug']],
                $permission
            );

        }

        /*
        =========================
        MANAGER ROLE
        =========================
        */

        $manager = Role::updateOrCreate(

            ['slug'=>'manager'],

            [
                'name'=>'Manager',
                'description'=>'Full system access'
            ]

        );

        $manager->permissions()->sync(

            Permission::pluck('id')->toArray()

        );

        /*
        =========================
        RECEPTION ROLE
        =========================
        */

        $reception = Role::updateOrCreate(

            ['slug'=>'reception'],

            [
                'name'=>'Reception',
                'description'=>'Daily operations'
            ]

        );

        $receptionPermissions = [

            'manage-patients',
            'manage-invoices',
            'print-invoices',
            'manage-payments',
            'manage-operations',
            'view-doctor-statements',

        ];

        $permissionIds = Permission::whereIn('slug',$receptionPermissions)
        ->pluck('id')
        ->toArray();

        $reception->permissions()->sync($permissionIds);

    }
}