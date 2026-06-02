<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/Login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('These credentials do not match our records.'),
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        // Check if user is active
        if (!$user->is_active) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => __('Your account has been deactivated.'),
            ]);
        }

        // Check tenant status
        if ($user->tenant->status === 'suspended') {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => __('Your account has been suspended. Please contact support.'),
            ]);
        }

        if ($user->tenant->status === 'cancelled') {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => __('Your subscription has been cancelled.'),
            ]);
        }

        // FIX: Redirect to correct dashboard URL with client_id
        return redirect()->route('app.dashboard', ['client_id' => $user->tenant->client_id]);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}