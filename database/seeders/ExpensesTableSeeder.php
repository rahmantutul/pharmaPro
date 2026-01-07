<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ExpensesTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $categories = [
            'Rent', 'Utilities', 'Salaries', 'Maintenance', 'Office Supplies', 'Marketing', 'Tea & Snacks', 'Transportation'
        ];

        foreach ($categories as $category) {
            DB::table('expense_categories')->insert([
                'name' => $category,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $categoryIds = DB::table('expense_categories')->pluck('id')->toArray();
        $adminId = DB::table('admins')->first()->id ?? 1;

        for ($i = 0; $i < 100; $i++) {
            // 40% chance to be in the current month
            if ($faker->boolean(40)) {
                 $date = $faker->dateTimeBetween('now', '+2 days');
            } else {
                 $date = $faker->dateTimeBetween('-1 year', 'now');
            }

            DB::table('expenses')->insert([
                'categoryId' => $faker->randomElement($categoryIds),
                'amount' => $faker->randomFloat(2, 50, 5000),
                'date' => $date,
                'expense_for' => $faker->sentence(3),
                'note' => $faker->sentence,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
