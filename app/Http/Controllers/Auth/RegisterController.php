<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;

class RegisterController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create()
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => 'required|accepted',
        ]);

        DB::beginTransaction();

        try {
            // Generate unique 6-digit client_id
            $clientId = $this->generateUniqueClientId();

            // Create tenant
            $tenant = Tenant::create([
                'client_id' => $clientId,
                'business_name' => $request->business_name,
                'status' => 'trial',
                'trial_ends_at' => now()->addDays(14),
            ]);

            // Create user (tenant owner)
            $user = User::create([
                'tenant_id' => $tenant->id,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'is_tenant_owner' => true,
            ]);

            DB::commit();

            // Fire registered event
            event(new Registered($user));

            // Log in the user
            Auth::login($user);

            return redirect()->route('verification.notice')->with('success', 
                "Registration successful! Your Client ID is: {$clientId}. Please verify your email."
            );

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors([
                'error' => 'Registration failed. Please try again.'
            ])->withInput();
        }
    }

    /**
     * Generate a unique 6-digit client ID.
     */
    private function generateUniqueClientId(): string
    {
        do {
            $clientId = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        } while (Tenant::where('client_id', $clientId)->exists());

        return $clientId;
    }
}