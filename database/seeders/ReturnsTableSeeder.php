<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\Medicine;
use App\Models\Customer;
use App\Models\Supplier;

class ReturnsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $medicineIds = Medicine::pluck('id')->toArray();
        $customerIds = Customer::pluck('id')->toArray();
        $supplierIds = Supplier::pluck('id')->toArray();

        if (empty($medicineIds)) {
            return;
        }

        // Sales Returns
        for ($i = 0; $i < 20; $i++) {
            $qty = $faker->numberBetween(1, 10);
            $price = $faker->randomFloat(2, 5, 100);
            DB::table('sales_returns')->insert([
                'inv_no' => 'SR-' . $faker->unique()->numberBetween(10000, 99999),
                'medicineId' => $faker->randomElement($medicineIds),
                'customerId' => !empty($customerIds) ? $faker->randomElement($customerIds) : null,
                'return_date' => $faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
                'qty' => $qty,
                'price' => $price,
                'total' => $qty * $price,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Purchase Returns
        for ($i = 0; $i < 10; $i++) {
            if (empty($supplierIds)) continue;
            
            $qty = $faker->numberBetween(5, 50);
            $price = $faker->randomFloat(2, 10, 200);
            DB::table('purchase_returns')->insert([
                'inv_no' => 'PR-' . $faker->unique()->numberBetween(10000, 99999),
                'medicineId' => $faker->randomElement($medicineIds),
                'supplierId' => $faker->randomElement($supplierIds),
                'return_date' => $faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
                'qty' => $qty,
                'price' => $price,
                'total' => $qty * $price,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
