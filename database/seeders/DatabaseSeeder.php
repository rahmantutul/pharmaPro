<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(AdminTableSeeder::class);
        $this->call(AdminRolePermissionSeeder::class);
        $this->call(GeneralSettingsTableSeeder::class);
        
        // Basic Settings & Metadata
        $this->call(PaymentMethodSeeder::class);
        $this->call(UnitsTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(TypesTableSeeder::class);
        $this->call(LeavesTableSeeder::class);
        
        // People
        $this->call(SuppliersTableSeeder::class);
        $this->call(VendorsTableSeeder::class);
        $this->call(CustomersTableSeeder::class);
        
        // // Products & Stock
        $this->call(MedicinesTableSeeder::class);
        $this->call(StocksTableSeeder::class);
        
        // // Operations
        $this->call(PurchasesTableSeeder::class);
        $this->call(SalesTableSeeder::class);
        $this->call(ReturnsTableSeeder::class);
        $this->call(DemurragesTableSeeder::class);
        $this->call(ExpensesTableSeeder::class);
        $this->call(TransactionsTableSeeder::class);
    }

}
