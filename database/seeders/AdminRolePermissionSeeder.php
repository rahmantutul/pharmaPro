<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Admin;
class AdminRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */ public function run()
    {
        // Clear cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions
        $permissions = [
            'user-management',
            'role-permission',
            'software-settings',
            'customers',
            'suppliers',
            'vendors',
            'payment-method',
            
            // Purchase Module
            'purchase-module',
            'purchase-order-create',
            'purchase-list',
            'purchase-direct-invoice',
            'purchase-invoice-list',
            
            // Expense Module
            'expense-module',
            'expense-category',
            'expense-list',
            
            // Demurrage
            'demurrage-module',
            'demurrage-list',
            'demurrage-create',
            
            // Medicine Module
            'medicine-module',
            'medicine-category',
            'medicine-unit',
            'medicine-leaf',
            'medicine-type',
            'medicine-list',
            
            // Sales Module
            'sales-module',
            'sales-order-create',
            'sales-list',
            
            // Return Module
            'return-module',
            'sales-return',
            'purchase-return',
            
            // Stock Module
            'stock-module',
            'in-stock-medicines',
            'low-stock-medicines',
            'stock-out-medicines',
            'upcoming-expired',
            'expired-medicines',
            
            // Reports Module
            'reports-module',
            'sales-report',
            'purchase-report',
            'customer-due-report',
            'supplier-due-report',
            
            'clear-cache',
        ];

        // Create and assign permissions for the admin guard
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'admin', // Specify the guard
            ]);
        }

        // Define roles
        $roles = [
            'Admin',
            'Sales',
            // Add more roles as needed
        ];

        // Create roles and assign existing permissions
        foreach ($roles as $roleName) {
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'admin', // Specify the guard
            ]);

            // Assign all permissions to the Admin role
            if ($roleName == 'Admin') {
                $role->syncPermissions(Permission::where('guard_name', 'admin')->get());
            }
        }

        // Assign roles to admin users
        $admin = Admin::find(1); // Get the admin (change the ID as needed)
        if ($admin) {
            $admin->assignRole('Admin'); // Assign the Admin role to the admin
        }

        // You can assign other roles to other admins as needed
    }
}
