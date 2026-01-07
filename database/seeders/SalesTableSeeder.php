<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Medicine;
use App\Models\PaymentMethod;

class SalesTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $customerIds = Customer::pluck('id')->toArray();
        $medicineIds = Medicine::pluck('id')->toArray();
        $paymentIds = DB::table('payment_methods')->pluck('id')->toArray(); // Use DB query as model might not exist or be seeded

        if (empty($medicineIds) || empty($paymentIds)) {
            return;
        }

        for ($i = 0; $i < 100; $i++) {
            $invoiceDate = $faker->dateTimeBetween('-6 months', 'now');
            $customerId = $faker->boolean(70) && !empty($customerIds) ? $faker->randomElement($customerIds) : null;
            
            // Create Sale Header
            // 40% chance to be in the current month (Jan 2026), otherwise random last year
            if ($faker->boolean(40)) {
                $invoiceDate = $faker->dateTimeBetween('now', '+2 days'); // Very recent
            } else {
                $invoiceDate = $faker->dateTimeBetween('-1 year', 'now');
            }
            
            $saleId = DB::table('sales')->insertGetId([
                'invoice_no' => 'INV-' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'invoice_date' => $invoiceDate,
                'customerId' => $faker->randomElement($customerIds),
                'is_walking_customer' => $customerId ? 0 : 1,
                'paymentId' => $faker->randomElement($paymentIds),
                'grand_total' => 0, // update later
                'invoice_discount' => 0,
                'discount_type' => 0,
                'payable_total' => 0, // update later
                'paid_amount' => 0, // update later
                'due_amount' => 0,
                'status' => 'paid',
                'created_by' => 1,
                'created_at' => $invoiceDate,
                'updated_at' => $invoiceDate,
            ]);

            $grandTotal = 0;
            
            // Create Sale Details
            $numItems = $faker->numberBetween(1, 5);
            for ($j = 0; $j < $numItems; $j++) {
                $medId = $faker->randomElement($medicineIds);
                $qty = $faker->numberBetween(1, 10);
                // Fetch random sell price simulation
                $sellPrice = $faker->randomFloat(2, 5, 100);
                $subtotal = $qty * $sellPrice;
                $discount = 0;
                $total = $subtotal - $discount;
                $grandTotal += $total;

                DB::table('sale_details')->insert([
                    'medicineId' => $medId,
                    'salesId' => $saleId,
                    'expiry_date' => $faker->dateTimeBetween('now', '+2 years')->format('Y-m-d'),
                    'sell_price' => $sellPrice,
                    'qty' => $qty,
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'total' => $total,
                    'created_at' => $invoiceDate,
                    'updated_at' => $invoiceDate,
                ]);
            }

            // Determine Payment Status
            $paymentStatus = $faker->randomElement(['paid', 'partial', 'due']);
            $paidAmount = 0;
            $dueAmount = 0;

            if ($paymentStatus === 'paid') {
                $paidAmount = $grandTotal;
                $dueAmount = 0;
            } elseif ($paymentStatus === 'partial') {
                $paidAmount = $faker->randomFloat(2, 10, $grandTotal - 1);
                $dueAmount = $grandTotal - $paidAmount;
            } else { // due
                $paidAmount = 0;
                $dueAmount = $grandTotal;
            }

            // Update Sale Header Totals
            DB::table('sales')->where('id', $saleId)->update([
                'grand_total' => $grandTotal,
                'payable_total' => $grandTotal,
                'paid_amount' => $paidAmount, 
                'due_amount' => $dueAmount,
                'status' => $paymentStatus,
            ]);
        }
    }
}
