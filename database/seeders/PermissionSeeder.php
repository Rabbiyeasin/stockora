<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Dashboard
            ['name' => 'View Dashboard', 'slug' => 'dashboard.view', 'group' => 'Dashboard'],
            
            // Products
            ['name' => 'View Products', 'slug' => 'products.view', 'group' => 'Products'],
            ['name' => 'Create Product', 'slug' => 'products.create', 'group' => 'Products'],
            ['name' => 'Edit Product', 'slug' => 'products.edit', 'group' => 'Products'],
            ['name' => 'Delete Product', 'slug' => 'products.delete', 'group' => 'Products'],
            
            // Categories
            ['name' => 'View Categories', 'slug' => 'categories.view', 'group' => 'Categories'],
            ['name' => 'Create Category', 'slug' => 'categories.create', 'group' => 'Categories'],
            ['name' => 'Edit Category', 'slug' => 'categories.edit', 'group' => 'Categories'],
            ['name' => 'Delete Category', 'slug' => 'categories.delete', 'group' => 'Categories'],
            
            // Sales
            ['name' => 'View Sales', 'slug' => 'sales.view', 'group' => 'Sales'],
            ['name' => 'Create Sale', 'slug' => 'sales.create', 'group' => 'Sales'],
            ['name' => 'Edit Sale', 'slug' => 'sales.edit', 'group' => 'Sales'],
            ['name' => 'Delete Sale', 'slug' => 'sales.delete', 'group' => 'Sales'],
            
            // POS
            ['name' => 'Access POS', 'slug' => 'pos.access', 'group' => 'POS'],
            
            // Purchases
            ['name' => 'View Purchases', 'slug' => 'purchases.view', 'group' => 'Purchases'],
            ['name' => 'Create Purchase', 'slug' => 'purchases.create', 'group' => 'Purchases'],
            ['name' => 'Edit Purchase', 'slug' => 'purchases.edit', 'group' => 'Purchases'],
            ['name' => 'Delete Purchase', 'slug' => 'purchases.delete', 'group' => 'Purchases'],
            
            // Customers
            ['name' => 'View Customers', 'slug' => 'customers.view', 'group' => 'Customers'],
            ['name' => 'Create Customer', 'slug' => 'customers.create', 'group' => 'Customers'],
            ['name' => 'Edit Customer', 'slug' => 'customers.edit', 'group' => 'Customers'],
            ['name' => 'Delete Customer', 'slug' => 'customers.delete', 'group' => 'Customers'],
            
            // Suppliers
            ['name' => 'View Suppliers', 'slug' => 'suppliers.view', 'group' => 'Suppliers'],
            ['name' => 'Create Supplier', 'slug' => 'suppliers.create', 'group' => 'Suppliers'],
            ['name' => 'Edit Supplier', 'slug' => 'suppliers.edit', 'group' => 'Suppliers'],
            ['name' => 'Delete Supplier', 'slug' => 'suppliers.delete', 'group' => 'Suppliers'],
            
            // Expenses
            ['name' => 'View Expenses', 'slug' => 'expenses.view', 'group' => 'Expenses'],
            ['name' => 'Create Expense', 'slug' => 'expenses.create', 'group' => 'Expenses'],
            ['name' => 'Edit Expense', 'slug' => 'expenses.edit', 'group' => 'Expenses'],
            ['name' => 'Delete Expense', 'slug' => 'expenses.delete', 'group' => 'Expenses'],
            
            // Reports
            ['name' => 'View Reports', 'slug' => 'reports.view', 'group' => 'Reports'],
            ['name' => 'Export Reports', 'slug' => 'reports.export', 'group' => 'Reports'],
            
            // Users
            ['name' => 'View Users', 'slug' => 'users.view', 'group' => 'Users'],
            ['name' => 'Create User', 'slug' => 'users.create', 'group' => 'Users'],
            ['name' => 'Edit User', 'slug' => 'users.edit', 'group' => 'Users'],
            ['name' => 'Delete User', 'slug' => 'users.delete', 'group' => 'Users'],
            
            // Roles
            ['name' => 'View Roles', 'slug' => 'roles.view', 'group' => 'Roles'],
            ['name' => 'Create Role', 'slug' => 'roles.create', 'group' => 'Roles'],
            ['name' => 'Edit Role', 'slug' => 'roles.edit', 'group' => 'Roles'],
            ['name' => 'Delete Role', 'slug' => 'roles.delete', 'group' => 'Roles'],
            
            // Settings
            ['name' => 'View Settings', 'slug' => 'settings.view', 'group' => 'Settings'],
            ['name' => 'Edit Settings', 'slug' => 'settings.edit', 'group' => 'Settings'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }
    }
}