<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;

class TenantRegisterController extends Controller
{
    public function create()
    {
        return Inertia::render('Auth/TenantRegister', [
            'countries' => $this->getCountries(),
            'businessTypes' => $this->getBusinessTypes(),
            'paymentMethods' => $this->getPaymentMethods(),
            'billingCycles' => $this->getBillingCycles(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Business Information
            'business_name' => 'required|string|max:255',
            'business_type' => 'nullable|string|max:50',
            'country' => 'required|string|max:100',
            'region' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'postal_code' => 'nullable|string|max:20',
            
            // Contact Information
            'email' => 'required|string|email|max:255|unique:tenants,email',
            'phone' => 'required|string|max:20',
            'emergency_contact' => 'nullable|string|max:20',
            
            // Payment Preferences
            'preferred_payment_method' => 'nullable|string|in:bkash,nagad,rocket,bank_transfer,card',
            
            // Owner Information
            'owner_name' => 'required|string|max:255',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        DB::beginTransaction();

        try {
            // Create tenant
            $tenant = Tenant::create([
                'business_name' => $validated['business_name'],
                'business_type' => $validated['business_type'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'emergency_contact' => $validated['emergency_contact'],
                'country' => $validated['country'],
                'region' => $validated['region'],
                'city' => $validated['city'],
                'address' => $validated['address'],
                'postal_code' => $validated['postal_code'],
                'preferred_payment_method' => $validated['preferred_payment_method'],
                'status' => 'trial',
                'trial_starts_at' => now(),
                'trial_ends_at' => now()->addDays(14), // 14-day trial
            ]);

            // Create owner user
            $user = User::create([
                'tenant_id' => $tenant->id,
                'name' => $validated['owner_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'status' => 'active',
                'email_verified_at' => now(), // Auto-verify for simplicity
            ]);

            // Create default roles for this tenant
            $this->createDefaultRoles($tenant);

            // Assign admin role to owner
            $adminRole = $tenant->roles()->where('slug', 'admin')->first();
            if ($adminRole) {
                $user->roles()->attach($adminRole->id, [
                    'assigned_at' => now(),
                ]);
            }

            // Increment tenant user count
            $tenant->increment('current_users_count');

            DB::commit();

            // Log in the user
            auth()->login($user);

            // Redirect to tenant dashboard
            return redirect()->route('tenant.dashboard', ['client_id' => $tenant->client_id])->with('success', "Welcome to Stockora! Your Client ID is: {$tenant->client_id}. Trial ends on {$tenant->trial_ends_at->format('M d, Y')}.");

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Registration failed: ' . $e->getMessage()]);
        }
    }

    protected function createDefaultRoles(Tenant $tenant)
    {
        $roles = [
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Full access to all features',
                'is_system_role' => true,
                'level' => 100,
                'permissions' => ['*'], // All permissions
            ],
            [
                'name' => 'Manager',
                'slug' => 'manager',
                'description' => 'Manage products, sales, and reports',
                'is_system_role' => true,
                'level' => 80,
                'permissions' => [
                    'products.view', 'products.create', 'products.update',
                    'inventory.view', 'inventory.adjust',
                    'sales.view', 'sales.create',
                    'purchases.view', 'purchases.create',
                    'customers.view', 'customers.create', 'customers.update',
                    'suppliers.view', 'suppliers.create', 'suppliers.update',
                    'reports.view',
                ],
            ],
            [
                'name' => 'Cashier',
                'slug' => 'cashier',
                'description' => 'POS and sales operations',
                'is_system_role' => true,
                'level' => 50,
                'permissions' => [
                    'products.view',
                    'sales.view', 'sales.create',
                    'customers.view', 'customers.create',
                    'pos.access',
                ],
            ],
        ];

        foreach ($roles as $roleData) {
            $permissionSlugs = $roleData['permissions'];
            unset($roleData['permissions']);

            $role = $tenant->roles()->create($roleData);

            // Attach permissions
            $permissions = Permission::whereIn('slug', $permissionSlugs)->get();
            $role->permissions()->attach($permissions->pluck('id'));
        }
    }

    protected function getCountries()
    {
        return [
            ['value' => 'Bangladesh', 'label' => 'Bangladesh'],
            ['value' => 'India', 'label' => 'India'],
            ['value' => 'Pakistan', 'label' => 'Pakistan'],
            ['value' => 'Nepal', 'label' => 'Nepal'],
            ['value' => 'Sri Lanka', 'label' => 'Sri Lanka'],
            ['value' => 'United States', 'label' => 'United States'],
            ['value' => 'United Kingdom', 'label' => 'United Kingdom'],
            ['value' => 'Canada', 'label' => 'Canada'],
            ['value' => 'Australia', 'label' => 'Australia'],
            ['value' => 'Other', 'label' => 'Other'],
        ];
    }

    protected function getBusinessTypes()
    {
        return [
            ['value' => 'retail', 'label' => 'Retail Store'],
            ['value' => 'wholesale', 'label' => 'Wholesale Business'],
            ['value' => 'restaurant', 'label' => 'Restaurant'],
            ['value' => 'pharmacy', 'label' => 'Pharmacy'],
            ['value' => 'electronics', 'label' => 'Electronics Shop'],
            ['value' => 'clothing', 'label' => 'Clothing Store'],
            ['value' => 'grocery', 'label' => 'Grocery Store'],
            ['value' => 'hardware', 'label' => 'Hardware Store'],
            ['value' => 'other', 'label' => 'Other'],
        ];
    }

    protected function getPaymentMethods()
    {
        return [
            ['value' => 'bkash', 'label' => 'bKash'],
            ['value' => 'nagad', 'label' => 'Nagad'],
            ['value' => 'rocket', 'label' => 'Rocket'],
            ['value' => 'bank_transfer', 'label' => 'Bank Transfer'],
            ['value' => 'card', 'label' => 'Credit/Debit Card'],
        ];
    }

    protected function getBillingCycles()
    {
        return [
            ['value' => 1, 'label' => '1st of every month'],
            ['value' => 7, 'label' => '7th of every month'],
            ['value' => 14, 'label' => '14th of every month'],
            ['value' => 21, 'label' => '21st of every month'],
        ];
    }
}