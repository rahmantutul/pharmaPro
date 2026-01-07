<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\Medicine;

class DemurragesTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $medicineIds = Medicine::pluck('id')->toArray();

        if (empty($medicineIds)) {
            return;
        }

        for ($i = 0; $i < 30; $i++) {
            $qty = $faker->numberBetween(1, 20);
            $price = $faker->randomFloat(2, 5, 100);

            DB::table('demurrages')->insert([
                'medicineId' => $faker->randomElement($medicineIds),
                'demurrage_date' => $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
                'price' => $price,
                'qty' => $qty,
                'total' => $qty * $price,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
