<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('dashboard.index', [
            'usersCount' => User::count(),
            'rolesCount' => Role::count(),
            'permissionsCount' => Permission::count(),
        ]);
    }
}