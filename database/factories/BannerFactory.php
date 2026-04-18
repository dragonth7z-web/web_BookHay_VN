<?php

namespace Database\Factories;

use App\Models\Banner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Banner>
 */
class BannerFactory extends Factory
{
    protected $model = Banner::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(5),
            'badge_text' => fake()->randomElement(['HOT', 'MỚI', 'SALE', null]),
            'image' => 'https://images.unsplash.com/photo-1507842217343-58387272b84d?auto=format&fit=crop&q=80&w=1200&h=500',
            'image_url' => null,
            'url' => '/sieu-thi-sach/tim-kiem',
            'button_text' => fake()->randomElement(['Mua Ngay', 'Xem Ngay', 'Khám Phá']),
            'position' => fake()->randomElement(['home_main', 'home_mini', 'Slider']),
            'sort_order' => fake()->numberBetween(1, 10),
            'is_visible' => true,
        ];
    }

    public function mainBanner(): static
    {
        return $this->state(fn () => ['position' => 'home_main']);
    }

    public function miniBanner(): static
    {
        return $this->state(fn () => ['position' => 'home_mini']);
    }

    public function visible(): static
    {
        return $this->state(fn () => ['is_visible' => true]);
    }

    public function homeMain(): static
    {
        return $this->state(fn () => ['position' => 'home_main']);
    }
}
