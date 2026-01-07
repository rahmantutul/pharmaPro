<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class StocksTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        $medicineIds = DB::table('medicines')->pluck('id');
        // First, check if user ID 1 exists
        $userExists = DB::table('admins')->where('id', 1)->exists();
        
        if (!$userExists) {
            return;
        }
        
        $stocks = [];
        $types = ['Purchase', 'Sales', 'Return', 'Demurrage', 'Others'];
        $startDate = Carbon::now()->subMonths(6);
        $endDate = Carbon::now()->addMonths(12);

        for ($i = 1; $i <= 1000; $i++) {
            $date = Carbon::createFromTimestamp(
                rand($startDate->timestamp, $endDate->timestamp)
            );
            
            $expireDate = clone $date;
            $expireDate->addMonths(rand(6, 36));
            
            $type = $types[array_rand($types)];
            
            // Determine quantity based on type
            switch ($type) {
                case 'Purchase':
                    $qty = rand(50, 500);
                    $inv_no = 'PUR-' . str_pad($i, 6, '0', STR_PAD_LEFT);
                    break;
                case 'Sales':
                    $qty = rand(-100, -10);
                    $inv_no = 'SAL-' . str_pad($i, 6, '0', STR_PAD_LEFT);
                    break;
                case 'Return':
                    $qty = rand(5, 50);
                    $inv_no = 'RET-' . str_pad($i, 6, '0', STR_PAD_LEFT);
                    break;
                case 'Demurrage':
                    $qty = rand(-20, -5);
                    $inv_no = 'DEM-' . str_pad($i, 6, '0', STR_PAD_LEFT);
                    break;
                default:
                    $qty = rand(-50, 50);
                    $inv_no = 'OTH-' . str_pad($i, 6, '0', STR_PAD_LEFT);
                    break;
            }
            
            // New logic for expire_date generation
            $expireDate = null;
            // 20% chance to be expiring soon (within next 10 days)
            if ($faker->boolean(20)) {
                $expireDate = $faker->dateTimeBetween('now', '+10 days')->format('Y-m-d');
            } else {
                $expireDate = $faker->dateTimeBetween('+1 month', '+2 years')->format('Y-m-d');
            }

            $stocks[] = [
                'medicineId' => rand(1, 1000), // Using rand as in original, or $faker->randomElement($medicineIds) if fetched
                'inv_no' => $inv_no, // Keep original inv_no generation
                'qty' => $qty, // Keep original qty generation
                'ref_id' => rand(1000, 9999),
                'date' => $date->format('Y-m-d'),
                'expire_date' => $expireDate, // Use new expireDate logic
                'type' => $type, // Keep original type selection
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Insert in batches of 100
            if ($i % 100 === 0) {
                DB::table('stocks')->insert($stocks);
                $stocks = [];
            }
        }

        // Insert remaining records
        if (!empty($stocks)) {
            DB::table('stocks')->insert($stocks);
        }
        
        // Add expired and low stock items
        $this->addExpiredStocks();
        $this->addLowStockItems();
    }

    private function addExpiredStocks(): void
    {
        $expiredStocks = [];
        
        for ($i = 1; $i <= 50; $i++) {
            $date = Carbon::now()->subMonths(rand(7, 12));
            $expireDate = Carbon::now()->subMonths(rand(1, 6));
            
            $expiredStocks[] = [
                'medicineId' => rand(1, 1000),
                'inv_no' => 'EXP-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'qty' => rand(20, 100),
                'ref_id' => rand(2000, 2999),
                'date' => $date->format('Y-m-d'),
                'expire_date' => $expireDate->format('Y-m-d'),
                'type' => 'Purchase',
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        DB::table('stocks')->insert($expiredStocks);
    }

    private function addLowStockItems(): void
    {
        $lowStocks = [];
        
        for ($i = 1; $i <= 30; $i++) {
            $date = Carbon::now()->subDays(rand(1, 30));
            $expireDate = Carbon::now()->addMonths(rand(3, 12));
            
            $lowStocks[] = [
                'medicineId' => rand(1, 1000),
                'inv_no' => 'LOW-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'qty' => rand(1, 5),
                'ref_id' => rand(3000, 3999),
                'date' => $date->format('Y-m-d'),
                'expire_date' => $expireDate->format('Y-m-d'),
                'type' => 'Purchase',
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        DB::table('stocks')->insert($lowStocks);
    }
}