<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show login page
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle login
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        /*
        |------------------------------------------------------
        | BLOCKED USER CHECK
        |------------------------------------------------------
        */
        if ($user->is_blocked) {
            Auth::logout();

            request()->session()->invalidate();
            request()->session()->regenerateToken();

            abort(403, 'Your account is blocked.');
        }

        /*
        |------------------------------------------------------
        | ROLE REDIRECTS (FIXED)
        |------------------------------------------------------
        */

        if ($user->hasRole('Admin') || $user->role === 'Admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('Accountant') || $user->role === 'Accountant') {
            return redirect()->route('accountant.dashboard');
        }

        if ($user->hasRole('Registrar') || $user->role === 'Registrar') {
            return redirect()->route('registrar.dashboard');
        }

        /*
        |------------------------------------------------------
        | DEFAULT FALLBACK
        |------------------------------------------------------
        */
        return redirect()->route('dashboard');
    }

    /**
     * Logout
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}