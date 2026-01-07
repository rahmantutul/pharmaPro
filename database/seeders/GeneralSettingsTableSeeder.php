<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GeneralSettingsTableSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'appname' => 'PharmaPro',
            'currency' => '$',
            'email' => 'info@pharmapro.com',
            'phone' => '+1 234 567 890',
            'address' => '123 Pharmacy Street, New York, USA',
            'lowstockalert' => 10,
            'expiryalert' => 10,
            'timezone' => 'UTC',
            'mail_driver' => 'smtp',
            'mail_host' => 'smtp.mailtrap.io',
            'mail_port' => '2525',
            'mail_username' => 'demo_user',
            'mail_password' => 'demo_password',
            'mail_encryption' => 'tls',
            'mail_from_address' => 'noreply@pharmapro.com',
            'mail_from_name' => 'PharmaPro System',
            'logo' => null,
            'favicon' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('general_settings')->insert($settings);
    }
}