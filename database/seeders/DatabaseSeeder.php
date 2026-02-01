<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (!User::where('email', 'admin@gmail.com')->exists()) {
            User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]);
        }

        if (!User::where('email', 'customer@gmail.com')->exists()) {
            User::factory()->create([
                'name' => 'Customer User',
                'email' => 'customer@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'customer',
            ]);
        }

        if (\App\Models\Service::count() == 0) {
            \App\Models\Service::create([
                'name' => 'Cuci Komplit (Cuci + Strika)',
                'price_per_kg' => 7000,
            ]);

            \App\Models\Service::create([
                'name' => 'Setrika Saja',
                'price_per_kg' => 5000,
            ]);
        }

        if (\App\Models\Bundle::count() == 0) {
            \App\Models\Bundle::create([
                'name' => 'Paket Bed Cover (Besar)',
                'description' => 'Cuci bed cover ukuran King/Queen',
                'price' => 35000,
            ]);
        }

        if (\App\Models\Promo::count() == 0) {
            \App\Models\Promo::create([
                'code' => 'DISKON10',
                'discount_type' => 'percent',
                'value' => 10,
                'is_active' => true,
                'expired_at' => now()->addDays(7),
            ]);
        }
    }
}
