<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Leaf;
use App\Models\Medicine;
use App\Models\Supplier;
use App\Models\Type;
use App\Models\Vendor;
use Faker\Factory as Faker;

use Illuminate\Support\Facades\Storage;
class MedicinesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $leafId = Leaf::pluck('id')->toArray();
        $categoryId = Category::pluck('id')->toArray();
        $vendorId = Vendor::pluck('id')->toArray();
        $supplierId = Supplier::pluck('id')->toArray();
        $typeId = Type::pluck('id')->toArray();

        // Realistic medicine strengths
        $strengths = [
            // Tablets/Capsules
            '50 mg', '75 mg', '100 mg', '125 mg', '150 mg', '200 mg', '250 mg', '300 mg', '400 mg', '500 mg', '600 mg', '750 mg', '800 mg', '1000 mg',
            '10 mg/325 mg', '15 mg/325 mg', '20 mg/325 mg', // Combination drugs
            '5 mg/10 mg', '10 mg/20 mg', '20 mg/40 mg', // Combination hypertension meds
            
            // Injections
            '10 mg/mL', '20 mg/mL', '40 mg/mL', '50 mg/mL', '100 mg/mL',
            '250 mg/5 mL', '500 mg/5 mL', '1 g/10 mL', '2 g/10 mL',
            
            // Syrups/Suspensions
            '125 mg/5 mL', '250 mg/5 mL', '500 mg/5 mL',
            '50 mg/mL suspension',
            
            // Creams/Ointments
            '0.1% cream', '1% cream', '2% cream', '5% cream',
            '0.5% ointment', '1% ointment',
            
            // Eye/Ear drops
            '0.3% eye drops', '0.5% eye drops', '1% eye drops',
            '0.3% ear drops',
            
            // Insulin
            '100 units/mL',
            
            // Inhalers
            '100 mcg/inhalation', '200 mcg/inhalation', '250 mcg/inhalation',
            
            // Patches
            '5 mg/24 hours', '10 mg/24 hours', '20 mg/24 hours',
            
            // Special formats
            '10 mg film-coated tablets',
            '25 mg extended-release',
            '50 mg sustained-release capsules',
            '75 mg enteric-coated',
            '100 mg dispersible tablets',
            
            // Combination strengths
            '20 mg + 12.5 mg',
            '40 mg + 12.5 mg',
            '80 mg + 12.5 mg',
            '5 mg + 160 mg + 12.5 mg',
        ];

        // Realistic medicine names with their typical strengths
        $commonMedicines = [
            ['name' => 'Paracetamol', 'generic' => 'Acetaminophen', 'strengths' => ['500 mg', '650 mg']],
            ['name' => 'Ibuprofen', 'generic' => 'Ibuprofen', 'strengths' => ['200 mg', '400 mg', '600 mg']],
            ['name' => 'Amoxicillin', 'generic' => 'Amoxicillin Trihydrate', 'strengths' => ['250 mg', '500 mg', '125 mg/5 mL']],
            ['name' => 'Metformin', 'generic' => 'Metformin Hydrochloride', 'strengths' => ['500 mg', '850 mg', '1000 mg']],
            ['name' => 'Atorvastatin', 'generic' => 'Atorvastatin Calcium', 'strengths' => ['10 mg', '20 mg', '40 mg', '80 mg']],
            ['name' => 'Losartan', 'generic' => 'Losartan Potassium', 'strengths' => ['25 mg', '50 mg', '100 mg']],
            ['name' => 'Omeprazole', 'generic' => 'Omeprazole', 'strengths' => ['20 mg', '40 mg']],
            ['name' => 'Cetirizine', 'generic' => 'Cetirizine Hydrochloride', 'strengths' => ['10 mg', '5 mg/5 mL']],
            ['name' => 'Levothyroxine', 'generic' => 'Levothyroxine Sodium', 'strengths' => ['25 mcg', '50 mcg', '75 mcg', '100 mcg', '125 mcg']],
            ['name' => 'Amlodipine', 'generic' => 'Amlodipine Besylate', 'strengths' => ['5 mg', '10 mg']],
            ['name' => 'Salbutamol', 'generic' => 'Salbutamol Sulfate', 'strengths' => ['100 mcg/inhalation', '2 mg/5 mL']],
            ['name' => 'Ciprofloxacin', 'generic' => 'Ciprofloxacin Hydrochloride', 'strengths' => ['250 mg', '500 mg', '750 mg']],
            ['name' => 'Diclofenac', 'generic' => 'Diclofenac Sodium', 'strengths' => ['50 mg', '75 mg', '100 mg', '1% gel']],
            ['name' => 'Esomeprazole', 'generic' => 'Esomeprazole Magnesium', 'strengths' => ['20 mg', '40 mg']],
            ['name' => 'Montelukast', 'generic' => 'Montelukast Sodium', 'strengths' => ['4 mg', '5 mg', '10 mg']],
            ['name' => 'Pantoprazole', 'generic' => 'Pantoprazole Sodium', 'strengths' => ['20 mg', '40 mg']],
            ['name' => 'Prednisolone', 'generic' => 'Prednisolone', 'strengths' => ['5 mg', '10 mg', '20 mg', '15 mg/5 mL']],
            ['name' => 'Tramadol', 'generic' => 'Tramadol Hydrochloride', 'strengths' => ['50 mg', '100 mg']],
            ['name' => 'Warfarin', 'generic' => 'Warfarin Sodium', 'strengths' => ['1 mg', '2 mg', '3 mg', '5 mg']],
            ['name' => 'Azithromycin', 'generic' => 'Azithromycin Dihydrate', 'strengths' => ['250 mg', '500 mg', '200 mg/5 mL']],
        ];

        foreach (range(1, 1000) as $index) {
            // Randomly choose between common medicine or generic medicine
            if ($faker->boolean(70)) { // 70% chance for common medicines
                $medicineData = $faker->randomElement($commonMedicines);
                $name = $medicineData['name'];
                $genericName = $medicineData['generic'];
                $strength = $faker->randomElement($medicineData['strengths']);
            } else { // 30% chance for generic medicines
                $name = $faker->word . ' ' . $faker->randomElement(['Tablets', 'Capsules', 'Injection', 'Syrup', 'Cream', 'Ointment']);
                $genericName = $faker->word . ' ' . $faker->randomElement(['Hydrochloride', 'Sulfate', 'Acetate', 'Citrate', 'Maleate']);
                $strength = $faker->randomElement($strengths);
            }

            // Generate realistic HNS codes (7-8 digits)
            $hnsCode = $faker->numberBetween(1000000, 99999999);
            
            // Generate QR code with meaningful format
            $qrCode = 'MED' . str_pad($hnsCode, 8, '0', STR_PAD_LEFT) . '-' . strtoupper(substr($faker->word, 0, 3));
            
            $medicine = new Medicine();
            $medicine->qr_code = $qrCode;
            $medicine->hns_code = $hnsCode;
            $medicine->name = $name;
            $medicine->strength = $strength;
            $medicine->purchase_price = $faker->randomFloat(2, 5, 500);
            $medicine->sell_price = $faker->randomFloat(2, 
                $medicine->purchase_price * 1.1, 
                $medicine->purchase_price * 1.5
            );            
            $medicine->generic_name = $genericName;
            $medicine->desc = $faker->sentence(6);
            $medicine->leafId = $faker->randomElement($leafId);
            $medicine->categoryId = $faker->randomElement($categoryId);
            $medicine->vendorId = $faker->randomElement($vendorId);
            $medicine->supplierId = $faker->randomElement($supplierId);
            $medicine->typeId = $faker->randomElement($typeId);
            $medicine->image = 'default.png';
            $medicine->save();
        }
    }
}
