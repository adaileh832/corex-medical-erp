<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class SetupController extends Controller
{
    public function createManager(): View
    {
        $managerExists = User::query()
            ->whereHas('role', function ($query) {
                $query->where('slug', 'manager');
            })
            ->exists();

        if ($managerExists) {
            abort(403, __('app.unauthorized'));
        }

        return view('setup.manager');
    }

    public function storeManager(Request $request): RedirectResponse
    {
        $managerExists = User::query()
            ->whereHas('role', function ($query) {
                $query->where('slug', 'manager');
            })
            ->exists();

        if ($managerExists) {
            abort(403, __('app.unauthorized'));
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $managerRole = Role::query()->firstOrCreate(
            ['slug' => 'manager'],
            [
                'name' => 'Manager',
                'description' => 'Full system access',
            ]
        );

        $allPermissionIds = Permission::query()->pluck('id')->all();

        if (! empty($allPermissionIds)) {
            $managerRole->permissions()->syncWithoutDetaching($allPermissionIds);
        }

        User::create([
            'role_id' => $managerRole->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_active' => true,
        ]);

        return redirect()
            ->route('login')
            ->with('success', app()->getLocale() === 'ar'
                ? 'تم إنشاء مدير النظام بنجاح. يمكنك تسجيل الدخول الآن.'
                : 'System manager created successfully. You can log in now.'
            );
    }
}