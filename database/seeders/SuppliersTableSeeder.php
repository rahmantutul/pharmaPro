<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SuppliersTableSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            ['name' => 'Sun Pharmaceutical', 'email' => 'sunpharma@example.com', 'address' => 'Sun House, Mumbai', 'phone' => '+91-2222222222'],
            ['name' => 'Cipla Ltd', 'email' => 'cipla@example.com', 'address' => 'Cipla House, Mumbai', 'phone' => '+91-2222222223'],
            ['name' => 'Dr. Reddys Laboratories', 'email' => 'drreddys@example.com', 'address' => 'Dr. Reddys, Hyderabad', 'phone' => '+91-2222222224'],
            ['name' => 'Lupin Limited', 'email' => 'lupin@example.com', 'address' => 'Lupin House, Mumbai', 'phone' => '+91-2222222225'],
            ['name' => 'Aurobindo Pharma', 'email' => 'aurobindo@example.com', 'address' => 'Aurobindo, Hyderabad', 'phone' => '+91-2222222226'],
            ['name' => 'Torrent Pharmaceuticals', 'email' => 'torrent@example.com', 'address' => 'Torrent House, Ahmedabad', 'phone' => '+91-2222222227'],
            ['name' => 'Cadila Healthcare', 'email' => 'cadila@example.com', 'address' => 'Zydus, Ahmedabad', 'phone' => '+91-2222222228'],
            ['name' => 'Glenmark Pharmaceuticals', 'email' => 'glenmark@example.com', 'address' => 'Glenmark, Mumbai', 'phone' => '+91-2222222229'],
            ['name' => 'Biocon Limited', 'email' => 'biocon@example.com', 'address' => 'Biocon, Bangalore', 'phone' => '+91-2222222230'],
            ['name' => 'Divis Laboratories', 'email' => 'divis@example.com', 'address' => 'Divis, Hyderabad', 'phone' => '+91-2222222231'],
            ['name' => 'Mankind Pharma', 'email' => 'mankind@example.com', 'address' => 'Mankind, Delhi', 'phone' => '+91-2222222232'],
            ['name' => 'Alkem Laboratories', 'email' => 'alkem@example.com', 'address' => 'Alkem, Mumbai', 'phone' => '+91-2222222233'],
            ['name' => 'Intas Pharmaceuticals', 'email' => 'intas@example.com', 'address' => 'Intas, Ahmedabad', 'phone' => '+91-2222222234'],
            ['name' => 'Pfizer India', 'email' => 'pfizer@example.com', 'address' => 'Pfizer, Mumbai', 'phone' => '+91-2222222235'],
            ['name' => 'Sanofi India', 'email' => 'sanofi@example.com', 'address' => 'Sanofi, Mumbai', 'phone' => '+91-2222222236'],
            ['name' => 'Novartis India', 'email' => 'novartis@example.com', 'address' => 'Novartis, Mumbai', 'phone' => '+91-2222222237'],
            ['name' => 'GlaxoSmithKline', 'email' => 'gsk@example.com', 'address' => 'GSK, Mumbai', 'phone' => '+91-2222222238'],
            ['name' => 'Abbott India', 'email' => 'abbott@example.com', 'address' => 'Abbott, Mumbai', 'phone' => '+91-2222222239'],
            ['name' => 'Merck India', 'email' => 'merck@example.com', 'address' => 'Merck, Mumbai', 'phone' => '+91-2222222240'],
            ['name' => 'Bayer India', 'email' => 'bayer@example.com', 'address' => 'Bayer, Mumbai', 'phone' => '+91-2222222241'],
            ['name' => 'Serum Institute', 'email' => 'serum@example.com', 'address' => 'Serum, Pune', 'phone' => '+91-2222222242'],
            ['name' => 'Panacea Biotec', 'email' => 'panacea@example.com', 'address' => 'Panacea, Delhi', 'phone' => '+91-2222222243'],
            ['name' => 'Jubilant Life Sciences', 'email' => 'jubilant@example.com', 'address' => 'Jubilant, Noida', 'phone' => '+91-2222222244'],
            ['name' => 'Ipca Laboratories', 'email' => 'ipca@example.com', 'address' => 'Ipca, Mumbai', 'phone' => '+91-2222222245'],
            ['name' => 'Wockhardt Ltd', 'email' => 'wockhardt@example.com', 'address' => 'Wockhardt, Mumbai', 'phone' => '+91-2222222246'],
        ];

        foreach ($suppliers as $supplier) {
            DB::table('suppliers')->insert([
                'name' => $supplier['name'],
                'email' => $supplier['email'],
                'address' => $supplier['address'],
                'phone' => $supplier['phone'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}