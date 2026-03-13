<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        $managerExists = User::query()
            ->whereHas('role', function ($query) {
                $query->where('slug', 'manager');
            })
            ->exists();

        return view('auth.login', [
            'managerExists' => $managerExists,
        ]);
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return back()
                ->withErrors([
                    'email' => __('app.invalid_credentials'),
                ])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        if (! $request->user()->is_active) {
            Auth::logout();

            return back()
                ->withErrors([
                    'email' => __('app.user_inactive'),
                ])
                ->onlyInput('email');
        }

        return redirect()->route('dashboard')->with('success', __('app.login_success'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', __('app.logout_success'));
    }
}