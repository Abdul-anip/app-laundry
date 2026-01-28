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
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Customer User',
            'email' => 'customer@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        \App\Models\Service::create([
            'name' => 'Cuci Komplit (Cuci + Strika)',
            'price_per_kg' => 7000,
        ]);

        \App\Models\Service::create([
            'name' => 'Setrika Saja',
            'price_per_kg' => 5000,
        ]);

        \App\Models\Bundle::create([
            'name' => 'Paket Bed Cover (Besar)',
            'description' => 'Cuci bed cover ukuran King/Queen',
            'price' => 35000,
        ]);

        \App\Models\Promo::create([
            'code' => 'DISKON10',
            'discount_type' => 'percent',
            'value' => 10,
            'is_active' => true,
            'expired_at' => now()->addDays(7),
        ]);
    }
}
