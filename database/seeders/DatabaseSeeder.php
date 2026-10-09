<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin Default untuk Uji Penilaian Asesor LSP
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'     => 'Admin Toko',
                'password' => Hash::make('password123'),
            ]
        );

        // 2. Jalankan Seeder Katalog Makanan
        $this->call([
            FoodSeeder::class,
        ]);
    }
}
