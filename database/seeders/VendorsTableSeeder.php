<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorsTableSeeder extends Seeder
{
    public function run(): void
    {
        $vendors = [
            ['name' => 'City Medical Store', 'email' => 'citymedical@example.com', 'phone' => '+91-9876543210', 'address' => '123 Main Street, Delhi', 'payable' => 250000, 'due' => 50000],
            ['name' => 'Pharma Distributors', 'email' => 'pharmadist@example.com', 'phone' => '+91-9876543211', 'address' => '456 Market Road, Mumbai', 'payable' => 180000, 'due' => 30000],
            ['name' => 'Medi-Care Suppliers', 'email' => 'medicare@example.com', 'phone' => '+91-9876543212', 'address' => '789 Pharma Lane, Bangalore', 'payable' => 320000, 'due' => 75000],
            ['name' => 'Health Plus Medical', 'email' => 'healthplus@example.com', 'phone' => '+91-9876543213', 'address' => '101 Medical Square, Kolkata', 'payable' => 150000, 'due' => 25000],
            ['name' => 'MedLife Distributors', 'email' => 'medlife@example.com', 'phone' => '+91-9876543214', 'address' => '234 Health Street, Chennai', 'payable' => 280000, 'due' => 60000],
            ['name' => 'Apollo Medicals', 'email' => 'apollomed@example.com', 'phone' => '+91-9876543215', 'address' => '567 Cure Road, Hyderabad', 'payable' => 220000, 'due' => 45000],
            ['name' => 'Fortis Pharma', 'email' => 'fortis@example.com', 'phone' => '+91-9876543216', 'address' => '890 Wellness Avenue, Pune', 'payable' => 190000, 'due' => 35000],
            ['name' => 'Max Healthcare Suppliers', 'email' => 'maxhealth@example.com', 'phone' => '+91-9876543217', 'address' => '111 Treatment Street, Ahmedabad', 'payable' => 260000, 'due' => 55000],
            ['name' => 'MediCorp Distributors', 'email' => 'medicorp@example.com', 'phone' => '+91-9876543218', 'address' => '222 Pharma Circle, Jaipur', 'payable' => 170000, 'due' => 28000],
            ['name' => 'HealthFirst Medical', 'email' => 'healthfirst@example.com', 'phone' => '+91-9876543219', 'address' => '333 Care Road, Lucknow', 'payable' => 240000, 'due' => 50000],
            ['name' => 'MediWorld Suppliers', 'email' => 'mediworld@example.com', 'phone' => '+91-9876543220', 'address' => '444 Health Avenue, Nagpur', 'payable' => 210000, 'due' => 42000],
            ['name' => 'PharmaPlus Distributors', 'email' => 'pharmaplus@example.com', 'phone' => '+91-9876543221', 'address' => '555 Medical Lane, Indore', 'payable' => 290000, 'due' => 65000],
            ['name' => 'CarePoint Medicals', 'email' => 'carepoint@example.com', 'phone' => '+91-9876543222', 'address' => '666 Cure Street, Bhopal', 'payable' => 160000, 'due' => 27000],
            ['name' => 'MediExpress Suppliers', 'email' => 'mediexpress@example.com', 'phone' => '+91-9876543223', 'address' => '777 Pharma Road, Patna', 'payable' => 230000, 'due' => 48000],
            ['name' => 'HealthBridge Distributors', 'email' => 'healthbridge@example.com', 'phone' => '+91-9876543224', 'address' => '888 Medical Avenue, Guwahati', 'payable' => 200000, 'due' => 40000],
            ['name' => 'MediLine Suppliers', 'email' => 'mediline@example.com', 'phone' => '+91-9876543225', 'address' => '999 Health Road, Bhubaneswar', 'payable' => 270000, 'due' => 58000],
            ['name' => 'PharmaCare Medical', 'email' => 'pharmacare@example.com', 'phone' => '+91-9876543226', 'address' => '121 Cure Avenue, Chandigarh', 'payable' => 180000, 'due' => 32000],
            ['name' => 'MediPro Distributors', 'email' => 'medipro@example.com', 'phone' => '+91-9876543227', 'address' => '232 Pharma Street, Dehradun', 'payable' => 250000, 'due' => 52000],
            ['name' => 'HealthMax Suppliers', 'email' => 'healthmax@example.com', 'phone' => '+91-9876543228', 'address' => '343 Medical Road, Ranchi', 'payable' => 220000, 'due' => 46000],
            ['name' => 'MediTrust Distributors', 'email' => 'meditrust@example.com', 'phone' => '+91-9876543229', 'address' => '454 Health Lane, Raipur', 'payable' => 190000, 'due' => 38000],
            ['name' => 'PharmaFirst Medical', 'email' => 'pharmafirst@example.com', 'phone' => '+91-9876543230', 'address' => '565 Cure Circle, Surat', 'payable' => 310000, 'due' => 70000],
            ['name' => 'CareAll Suppliers', 'email' => 'careall@example.com', 'phone' => '+91-9876543231', 'address' => '676 Pharma Avenue, Vadodara', 'payable' => 240000, 'due' => 51000],
            ['name' => 'MediOne Distributors', 'email' => 'medione@example.com', 'phone' => '+91-9876543232', 'address' => '787 Medical Street, Kochi', 'payable' => 210000, 'due' => 44000],
            ['name' => 'HealthPro Medical', 'email' => 'healthpro@example.com', 'phone' => '+91-9876543233', 'address' => '898 Health Road, Thiruvananthapuram', 'payable' => 260000, 'due' => 56000],
            ['name' => 'PharmaWorld Distributors', 'email' => 'pharmaworld@example.com', 'phone' => '+91-9876543234', 'address' => '909 Cure Lane, Coimbatore', 'payable' => 230000, 'due' => 49000],
        ];

        foreach ($vendors as $vendor) {
            DB::table('vendors')->insert([
                'name' => $vendor['name'],
                'email' => $vendor['email'],
                'phone' => $vendor['phone'],
                'address' => $vendor['address'],
                'payable' => $vendor['payable'],
                'due' => $vendor['due'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}