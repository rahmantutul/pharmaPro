<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitsTableSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            'Box',
            'Strip',
            'Piece',
            'Bottle',
            'Packet',
            'Tube',
            'Vial',
            'Ampoule',
            'Can',
            'Jar',
            'Sachet',
            'Spray',
            'Kit',
            'Bag',
            'Roll',
        ];

        foreach ($units as $unit) {
            DB::table('units')->insert([
                'name' => $unit,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
