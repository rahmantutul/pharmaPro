<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Analgesics', 'Antibiotics', 'Antihistamines', 'Antidiabetics',
            'Cardiovascular', 'Gastrointestinal', 'Vitamins & Supplements',
            'Dermatological', 'Ophthalmic', 'Respiratory', 'Neurological',
            'Psychiatric', 'Hormonal', 'Antifungal', 'Antiviral',
            'Anticancer', 'Muscle Relaxants', 'Anticoagulants',
            'Immunosuppressants', 'Diagnostic Agents', 'Electrolytes',
            'Antiseptics', 'Antipyretics', 'Anti-inflammatory'
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}