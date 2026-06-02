<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Role;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class RegisterController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'business_name' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:tenants,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        DB::beginTransaction();

        try {
            // Generate unique 6-digit client_id
            $clientId = $this->generateClientId();

            // Create tenant
            $tenant = Tenant::create([
                'client_id' => $clientId,
                'name' => $request->business_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'status' => 'trial',
                'trial_ends_at' => now()->addDays(14),
            ]);

            // Create admin role for this tenant
            $adminRole = Role::create([
                'tenant_id' => $tenant->id,
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Full access to all features',
            ]);

            // Create user
            $user = User::create([
                'tenant_id' => $tenant->id,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role_id' => $adminRole->id,
                'is_active' => true,
            ]);

            DB::commit();

            event(new Registered($user));

            Auth::login($user);

            // FIX: Correct redirect with client_id
            return redirect()->route('app.dashboard', ['client_id' => $clientId])
                ->with('success', 'Account created successfully! Your trial period is 14 days.');

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function generateClientId(): string
    {
        do {
            $clientId = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        } while (Tenant::where('client_id', $clientId)->exists());

        return $clientId;
    }
}