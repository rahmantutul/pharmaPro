<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\Customer;
use App\Models\Supplier;

class TransactionsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $customerIds = Customer::pluck('id')->toArray();
        $supplierIds = Supplier::pluck('id')->toArray();

        // 1. Transactions for Sales (Paid)
        // We can create some random transaction logs mimicking sales payments
        for ($i = 0; $i < 50; $i++) {
            DB::table('transactions')->insert([
                'medicineId' => null,
                'customerId' => !empty($customerIds) ? $faker->randomElement($customerIds) : null,
                'supplierId' => null,
                'is_walking_customer' => $faker->boolean(30) ? 1 : 0,
                'amount' => $faker->randomFloat(2, 50, 1000),
                'date' => $faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
                'refId' => null, // Could link to sales ID if needed, but schema says integer nullable
                'type' => 'customer_due', // OR sale_payment? Schema comment says: demurrage,purchase_return,sales_return,customer_due,supplier_due
                // Wait, normal sales might not be in 'transactions' table if that table is only for special events?
                // The comment says: 'demurrage,purchase_return,sales_return,customer_due,supplier_due'.
                // Standard sales usually go to 'sales' table. 'transactions' might be for due payments or adjustments.
                // I will stick to the types listed in the comment.
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2. Transactions for Customer Due Payments
        for ($i = 0; $i < 20; $i++) {
            if (empty($customerIds)) break;
            DB::table('transactions')->insert([
                'customerId' => $faker->randomElement($customerIds),
                'amount' => $faker->randomFloat(2, 100, 500),
                'date' => $faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
                'type' => 'customer_due',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. Transactions for Supplier Payments
        for ($i = 0; $i < 20; $i++) {
            if (empty($supplierIds)) break;
            DB::table('transactions')->insert([
                'supplierId' => $faker->randomElement($supplierIds),
                'amount' => $faker->randomFloat(2, 1000, 5000),
                'date' => $faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
                'type' => 'supplier_due',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
