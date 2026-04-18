<?php

namespace Database\Factories;

use App\Models\Combo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Combo>
 */
class ComboFactory extends Factory
{
    protected $model = Combo::class;

    public function definition(): array
    {
        $name = 'Combo ' . fake()->words(3, true);
        $originalPrice = fake()->numberBetween(200, 800) * 1000;

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name) . '-' . strtolower(Str::random(3)),
            'description' => fake()->paragraph(),
            'original_price' => $originalPrice,
            'sale_price' => round($originalPrice * 0.85),
            'bg_from' => fake()->hexColor(),
            'bg_to' => fake()->hexColor(),
            'icon' => fake()->randomElement(['psychology', 'auto_stories', 'rocket_launch', 'school']),
            'image' => null,
            'badge_text' => fake()->randomElement(['HOT', 'MỚI', 'BÁN CHẠY', null]),
            'button_text' => fake()->randomElement(['Khám Phá', 'Xem Chi Tiết', 'Mua Ngay']),
            'is_visible' => true,
            'sort_order' => fake()->numberBetween(1, 10),
        ];
    }
}
