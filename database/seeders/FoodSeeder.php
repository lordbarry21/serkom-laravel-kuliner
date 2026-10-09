<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seed initial menu dataset across Makanan, Minuman, and Cemilan.
     */
    public function run(): void
    {
        DB::table('foods')->insert([
            [
                'name'        => 'Nasi Goreng Spesial',
                'category'    => 'Makanan',
                'price'       => 25000,
                'description' => 'Nasi goreng harum dengan telur mata sapi, suwiran ayam, dan kerupuk renyah.',
                'image'       => null,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Mie Goreng Seafood',
                'category'    => 'Makanan',
                'price'       => 28000,
                'description' => 'Mie goreng gurih pedas dengan topping udang segar dan cumi pilihan.',
                'image'       => null,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Es Teh Manis',
                'category'    => 'Minuman',
                'price'       => 5000,
                'description' => 'Es teh melati segar dengan manis alami pelepas dahaga.',
                'image'       => null,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Jus Alpukat',
                'category'    => 'Minuman',
                'price'       => 15000,
                'description' => 'Jus alpukat kental murni dipadu susu kental manis cokelat premium.',
                'image'       => null,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Kentang Goreng Truffle',
                'category'    => 'Cemilan',
                'price'       => 14000,
                'description' => 'Kentang goreng renyah keemasan bertabur keju parmesan dan saus celup spesial.',
                'image'       => null,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
