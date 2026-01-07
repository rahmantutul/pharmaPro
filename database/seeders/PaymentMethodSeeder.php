<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentMethods = [
            ['name' => 'Cash', 'balance' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'bKash', 'balance' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Nagad', 'balance' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Rocket', 'balance' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bank Transfer', 'balance' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Credit Card', 'balance' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Credit Sale', 'balance' => 0, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('payment_methods')->insert($paymentMethods);
    }
}