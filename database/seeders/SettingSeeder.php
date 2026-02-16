<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'footer_company_description',
                'value' => 'Your premium laundry service partner. Fast, clean, and fragrant - every single time.',
                'group' => 'footer',
                'type' => 'textarea',
            ],
            [
                'key' => 'footer_email',
                'value' => 'support@viplaundry.com',
                'group' => 'footer',
                'type' => 'email',
            ],
            [
                'key' => 'footer_phone',
                'value' => '+62 812-3456-7890',
                'group' => 'footer',
                'type' => 'text',
            ],
            [
                'key' => 'footer_address',
                'value' => 'Jakarta, Indonesia',
                'group' => 'footer',
                'type' => 'text',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
