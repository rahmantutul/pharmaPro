<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\Medicine;
use App\Models\Supplier;

class PurchasesTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $supplierIds = Supplier::pluck('id')->toArray();
        $medicineIds = Medicine::pluck('id')->toArray();

        if (empty($supplierIds) || empty($medicineIds)) {
            return;
        }

        for ($i = 0; $i < 50; $i++) {
            $grandTotal = 0;
            
            // 40% chance to be in the current month
            if ($faker->boolean(40)) {
                $orderDate = $faker->dateTimeBetween('now', '+2 days');
            } else {
                $orderDate = $faker->dateTimeBetween('-1 year', 'now');
            }
            
            // Create Purchase Order
            $orderId = DB::table('purchase_orders')->insertGetId([
                'order_date' => $orderDate,
                'order_time' => $faker->time('H:i:s'),
                'supplierId' => $faker->randomElement($supplierIds),
                'grand_total' => 0, // Will update later
                'is_invoiced' => $faker->boolean(80) ? 1 : 0,
                'order_by' => 'Super Admin',
                'created_at' => $orderDate,
                'updated_at' => $orderDate,
            ]);

            // Create Order Details
            $numItems = $faker->numberBetween(1, 5);
            $orderDetails = []; // Store details for invoice creation
            
            for ($j = 0; $j < $numItems; $j++) {
                $medId = $faker->randomElement($medicineIds);
                $qty = $faker->numberBetween(10, 100);
                $price = $faker->randomFloat(2, 10, 200);
                $total = $qty * $price;
                $grandTotal += $total;

                $detailData = [
                    'order_id' => $orderId,
                    'medicineId' => $medId,
                    'price' => $price,
                    'qty' => $qty,
                    'total' => $total, // This column name matches migration
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ];

                DB::table('purchase_order_details')->insert($detailData);
                $orderDetails[] = $detailData;
            }

            // Update Grand Total
            DB::table('purchase_orders')->where('id', $orderId)->update(['grand_total' => $grandTotal]);

            // If invoiced, create invoice entry
            if ($faker->boolean(80)) {
                $paidAmount = $faker->randomFloat(2, 0, $grandTotal);
                $dueAmount = $grandTotal - $paidAmount;
                $supplierId = $faker->randomElement($supplierIds);

                $invoiceId = DB::table('purchase_invoices')->insertGetId([
                    'invoice_no' => 'INV-' . str_pad($orderId, 6, '0', STR_PAD_LEFT),
                    'invoice_date' => $orderDate->format('Y-m-d'),
                    'supplierId' => $supplierId, 
                    'discount_type' => 1,
                    'total_discount' => 0,
                    'total_amount' => $grandTotal,
                    'paid_amount' => $paidAmount,
                    'due_amount' => $dueAmount,
                    'direct_invoice' => 0, // It comes from PO
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ]);

                // Create Invoice Details
                foreach ($orderDetails as $detail) {
                    DB::table('purchase_invoice_details')->insert([
                        'invoice_id' => $invoiceId,
                        'medicineId' => $detail['medicineId'],
                        'supplier_id' => $supplierId,
                        'expire_date' => $faker->dateTimeBetween('+1 year', '+3 years')->format('Y-m-d'),
                        'qty' => $detail['qty'],
                        'price' => $detail['price'],
                        'total' => $detail['total'],
                        'created_at' => $orderDate,
                        'updated_at' => $orderDate,
                    ]);
                }
            }
        }
    }
}
