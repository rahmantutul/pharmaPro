<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeavesTableSeeder extends Seeder
{
    public function run(): void
    {
        $medicines = [
            ['name' => 'Paracetamol 500mg', 'qty' => '1000 tablets'],
            ['name' => 'Amoxicillin 500mg', 'qty' => '500 capsules'],
            ['name' => 'Ibuprofen 400mg', 'qty' => '800 tablets'],
            ['name' => 'Cetirizine 10mg', 'qty' => '600 tablets'],
            ['name' => 'Metformin 500mg', 'qty' => '750 tablets'],
            ['name' => 'Azithromycin 250mg', 'qty' => '300 tablets'],
            ['name' => 'Omeprazole 20mg', 'qty' => '450 capsules'],
            ['name' => 'Atorvastatin 10mg', 'qty' => '500 tablets'],
            ['name' => 'Losartan 50mg', 'qty' => '600 tablets'],
            ['name' => 'Levothyroxine 50mcg', 'qty' => '400 tablets'],
            ['name' => 'Amlodipine 5mg', 'qty' => '550 tablets'],
            ['name' => 'Metronidazole 400mg', 'qty' => '700 tablets'],
            ['name' => 'Diclofenac 50mg', 'qty' => '900 tablets'],
            ['name' => 'Pantoprazole 40mg', 'qty' => '500 tablets'],
            ['name' => 'Clopidogrel 75mg', 'qty' => '400 tablets'],
            ['name' => 'Montelukast 10mg', 'qty' => '350 tablets'],
            ['name' => 'Ciprofloxacin 500mg', 'qty' => '450 tablets'],
            ['name' => 'Ranitidine 150mg', 'qty' => '600 tablets'],
            ['name' => 'Aspirin 75mg', 'qty' => '1000 tablets'],
            ['name' => 'Vitamin C 500mg', 'qty' => '800 tablets'],
            ['name' => 'Vitamin D3 1000IU', 'qty' => '500 capsules'],
            ['name' => 'Calcium 500mg', 'qty' => '700 tablets'],
            ['name' => 'Metoclopramide 10mg', 'qty' => '400 tablets'],
            ['name' => 'Salbutamol Inhaler', 'qty' => '100 pieces'],
            ['name' => 'Insulin Glargine', 'qty' => '50 vials'],
        ];

        foreach ($medicines as $medicine) {
            DB::table('leaves')->insert([
                'name' => $medicine['name'],
                'qty' => $medicine['qty'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}