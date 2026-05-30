<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Wildcard (super admin)
            ['name' => 'All Permissions', 'slug' => '*', 'module' => 'system'],
            
            // Products
            ['name' => 'View Products', 'slug' => 'products.view', 'module' => 'products'],
            ['name' => 'Create Products', 'slug' => 'products.create', 'module' => 'products'],
            ['name' => 'Update Products', 'slug' => 'products.update', 'module' => 'products'],
            ['name' => 'Delete Products', 'slug' => 'products.delete', 'module' => 'products'],
            ['name' => 'Export Products', 'slug' => 'products.export', 'module' => 'products'],
            
            // Inventory
            ['name' => 'View Inventory', 'slug' => 'inventory.view', 'module' => 'inventory'],
            ['name' => 'Adjust Inventory', 'slug' => 'inventory.adjust', 'module' => 'inventory'],
            ['name' => 'Transfer Inventory', 'slug' => 'inventory.transfer', 'module' => 'inventory'],
            
            // Sales
            ['name' => 'View Sales', 'slug' => 'sales.view', 'module' => 'sales'],
            ['name' => 'Create Sales', 'slug' => 'sales.create', 'module' => 'sales'],
            ['name' => 'Update Sales', 'slug' => 'sales.update', 'module' => 'sales'],
            ['name' => 'Delete Sales', 'slug' => 'sales.delete', 'module' => 'sales'],
            ['name' => 'Refund Sales', 'slug' => 'sales.refund', 'module' => 'sales'],
            
            // POS
            ['name' => 'Access POS', 'slug' => 'pos.access', 'module' => 'pos'],
            
            // Purchases
            ['name' => 'View Purchases', 'slug' => 'purchases.view', 'module' => 'purchases'],
            ['name' => 'Create Purchases', 'slug' => 'purchases.create', 'module' => 'purchases'],
            ['name' => 'Update Purchases', 'slug' => 'purchases.update', 'module' => 'purchases'],
            ['name' => 'Delete Purchases', 'slug' => 'purchases.delete', 'module' => 'purchases'],
            
            // Customers
            ['name' => 'View Customers', 'slug' => 'customers.view', 'module' => 'customers'],
            ['name' => 'Create Customers', 'slug' => 'customers.create', 'module' => 'customers'],
            ['name' => 'Update Customers', 'slug' => 'customers.update', 'module' => 'customers'],
            ['name' => 'Delete Customers', 'slug' => 'customers.delete', 'module' => 'customers'],
            
            // Suppliers
            ['name' => 'View Suppliers', 'slug' => 'suppliers.view', 'module' => 'suppliers'],
            ['name' => 'Create Suppliers', 'slug' => 'suppliers.create', 'module' => 'suppliers'],
            ['name' => 'Update Suppliers', 'slug' => 'suppliers.update', 'module' => 'suppliers'],
            ['name' => 'Delete Suppliers', 'slug' => 'suppliers.delete', 'module' => 'suppliers'],
            
            // Expenses
            ['name' => 'View Expenses', 'slug' => 'expenses.view', 'module' => 'expenses'],
            ['name' => 'Create Expenses', 'slug' => 'expenses.create', 'module' => 'expenses'],
            ['name' => 'Approve Expenses', 'slug' => 'expenses.approve', 'module' => 'expenses'],
            
            // Reports
            ['name' => 'View Reports', 'slug' => 'reports.view', 'module' => 'reports'],
            ['name' => 'Export Reports', 'slug' => 'reports.export', 'module' => 'reports'],
            
            // Users
            ['name' => 'Manage Users', 'slug' => 'users.manage', 'module' => 'users'],
            ['name' => 'Manage Roles', 'slug' => 'roles.manage', 'module' => 'users'],
            
            // Settings
            ['name' => 'Manage Settings', 'slug' => 'settings.manage', 'module' => 'settings'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }

        $this->command->info('Permissions seeded successfully!');
    }
}