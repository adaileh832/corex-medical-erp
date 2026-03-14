<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $admin = User::firstOrCreate(
            [
                'email' => 'admin@corex.local',
            ],
            [
                'name' => 'CoreX Admin',
                'password' => Hash::make('Admin@12345'),
            ]
        );

        $role = DB::table('roles')->where('name', 'admin')->first();

        if (! $role) {
            $data = [
                'name' => 'admin',
            ];

            if (Schema::hasColumn('roles', 'guard_name')) {
                $data['guard_name'] = 'web';
            }

            if (Schema::hasColumn('roles', 'slug')) {
                $data['slug'] = 'admin';
            }

            if (Schema::hasColumn('roles', 'created_at')) {
                $data['created_at'] = now();
            }

            if (Schema::hasColumn('roles', 'updated_at')) {
                $data['updated_at'] = now();
            }

            DB::table('roles')->insert($data);
            $role = DB::table('roles')->where('name', 'admin')->first();
        }

        if ($role && Schema::hasTable('model_has_roles')) {
            $exists = DB::table('model_has_roles')
                ->where('role_id', $role->id)
                ->where('model_type', User::class)
                ->where('model_id', $admin->id)
                ->exists();

            if (! $exists) {
                DB::table('model_has_roles')->insert([
                    'role_id' => $role->id,
                    'model_type' => User::class,
                    'model_id' => $admin->id,
                ]);
            }
        }
    }
}