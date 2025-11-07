<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'kasir',
            'phone_number' => '00000000',
            'role' => 'kasir',
            'username' => 'kasir',
            'password' => Hash::make('kasir'),
        ]);

        User::create([
            'name' => 'admin',
            'phone_number' => '11111111',
            'role' => 'admin',
            'username' => 'admin',
            'password' => Hash::make('admin'),
        ]);

        User::create([
            'name' => 'gudang',
            'phone_number' => '22222222',
            'role' => 'gudang',
            'username' => 'gudang',
            'password' => Hash::make('gudang'),
        ]);

        // User::factory(50)->create();
        // Category::factory(50)->create();
        // Unit::factory(50)->create();
        // Product::factory(50)->create();
    }
}
