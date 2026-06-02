<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Role;
use App\Models\Store;
use Illuminate\Support\Facades\Hash;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
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
            'description' => 'Full access',
        ]);

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

        $role2 = Role::create([
            'tenant_id' => $tenant2->id,
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => 'Full access',
        ]);

        User::create([
            'tenant_id' => $tenant2->id,
            'name' => 'Test User',
            'email' => 'user@test.com',
            'password' => Hash::make('password'),
            'role_id' => $role2->id,
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