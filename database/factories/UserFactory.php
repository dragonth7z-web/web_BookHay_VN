<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'phone' => '09' . fake()->numerify('########'),
            'gender' => fake()->randomElement(['male', 'female']),
            'role_id' => 2,
            'status' => 'active',
            'loyalty_points' => fake()->numberBetween(0, 1000),
            'total_spent' => fake()->numberBetween(0, 5000000),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => 1,
        ]);
    }

    public function staff(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => 3,
        ]);
    }
}
