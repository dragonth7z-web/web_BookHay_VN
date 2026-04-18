<?php

namespace Database\Factories;

use App\Models\Publisher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Publisher>
 */
class PublisherFactory extends Factory
{
    protected $model = Publisher::class;

    public function definition(): array
    {
        $name = 'NXB ' . fake()->company();

        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . strtolower(Str::random(3)),
            'logo' => 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($name),
            'address' => fake()->address(),
            'phone' => '028 ' . fake()->numerify('########'),
            'email' => fake()->companyEmail(),
            'is_partner' => fake()->boolean(40),
            'partner_icon' => null,
            'partner_gradient' => null,
        ];
    }

    public function partner(): static
    {
        return $this->state(fn () => [
            'is_partner' => true,
            'partner_icon' => fake()->randomElement(['castle', 'auto_awesome', 'menu_book', 'stars']),
            'partner_gradient' => 'linear-gradient(135deg, ' . fake()->hexColor() . ', ' . fake()->hexColor() . ')',
        ]);
    }
}
