<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypesTableSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'Tablets', 'Capsules', 'Syrups', 'Injections',
            'Ointments', 'Creams', 'Drops', 'Inhalers',
            'Sprays', 'Patches', 'Suppositories', 'Lotions',
            'Gels', 'Powders', 'Suspensions', 'Emulsions',
            'Implants', 'Lozenges', 'Chewable Tablets',
            'Effervescent Tablets', 'Sublingual Tablets',
            'Sachets', 'Vials', 'Ampoules', 'Infusions'
        ];

        foreach ($types as $type) {
            DB::table('types')->insert([
                'name' => $type,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}