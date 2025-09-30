<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Nette\Utils\Random;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'phone_number' => '08' . Random::generate('10', '0-9'),
            'username' => fake()->userName(),
            'password' => '123', // password
            'role' => fake()->randomElement(['admin', 'kasir', 'gudang']),
        ];
    }
}
