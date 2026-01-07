<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixStockTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update 'Purchase' to 'purchase'
        DB::table('stocks')
            ->where('type', 'Purchase')
            ->update(['type' => 'purchase']);

        // Update 'Sales' to 'sales' (if any uppercase exist)
        DB::table('stocks')
            ->where('type', 'Sales')
            ->update(['type' => 'sales']);

        $this->command->info('Stock types normalized to lowercase.');
    }
}
