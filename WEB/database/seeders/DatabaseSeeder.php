<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'guest',
            'phone_number' => '00000000',
            'remember_token' => Str::random(10),
            'role' => 'kasir',
            'username' => 'guest',
            'password' => 'guest',
        ]);

        User::create([
            'name' => 'admin',
            'phone_number' => '11111111',
            'remember_token' => Str::random(10),
            'role' => 'admin',
            'username' => 'admin',
            'password' => 'admin',
        ]);

        User::create([
            'name' => 'gudang',
            'phone_number' => '22222222',
            'remember_token' => Str::random(10),
            'role' => 'gudang',
            'username' => 'gudang',
            'password' => 'gudang',
        ]);

        //User::factory(10)->create();
    }
}
