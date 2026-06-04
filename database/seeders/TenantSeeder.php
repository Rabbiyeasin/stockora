<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Store;
use Illuminate\Support\Facades\Hash;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        $adminPermissions = Permission::all();

        // Tenant 1
        $tenant1 = Tenant::create([
            'client_id' => '100001',
            'name' => 'Demo Store',
            'email' => 'demo@store.com',
            'phone' => '01711111111',
            'status' => 'active',
            'trial_ends_at' => now()->addDays(14),
        ]);

        $role1 = Role::create([
            'tenant_id' => $tenant1->id,
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => 'Full access to all features',
        ]);

        $role1->permissions()->sync($adminPermissions->pluck('id'));

        User::create([
            'tenant_id' => $tenant1->id,
            'name' => 'Demo Admin',
            'email' => 'admin@demo.com',
            'password' => Hash::make('password'),
            'role_id' => $role1->id,
            'is_active' => true,
        ]);

        Store::create([
            'tenant_id' => $tenant1->id,
            'name' => 'Main Store',
            'code' => 'MAIN',
            'is_main' => true,
        ]);

        // Tenant 2
        $tenant2 = Tenant::create([
            'client_id' => '100002',
            'name' => 'Test Business',
            'email' => 'test@business.com',
            'phone' => '01722222222',
            'status' => 'trial',
            'trial_ends_at' => now()->addDays(7),
        ]);

        $role2Admin = Role::create([
            'tenant_id' => $tenant2->id,
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => 'Full access',
        ]);

        $role2Admin->permissions()->sync($adminPermissions->pluck('id'));

        $role2Staff = Role::create([
            'tenant_id' => $tenant2->id,
            'name' => 'Staff',
            'slug' => 'staff',
            'description' => 'Limited access',
        ]);

        $staffPermissions = Permission::whereIn('slug', [
            'dashboard.view',
            'products.view',
            'sales.view',
            'sales.create',
            'pos.access',
            'customers.view',
            'customers.create',
        ])->pluck('id');

        $role2Staff->permissions()->sync($staffPermissions);

        User::create([
            'tenant_id' => $tenant2->id,
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role_id' => $role2Admin->id,
            'is_active' => true,
        ]);

        User::create([
            'tenant_id' => $tenant2->id,
            'name' => 'Test Staff',
            'email' => 'staff@test.com',
            'password' => Hash::make('password'),
            'role_id' => $role2Staff->id,
            'is_active' => true,
        ]);

        Store::create([
            'tenant_id' => $tenant2->id,
            'name' => 'Main Branch',
            'code' => 'MB01',
            'is_main' => true,
        ]);
    }
}