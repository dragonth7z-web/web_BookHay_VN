<?php

namespace Database\Factories;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coupon>
 */
class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    public function definition(): array
    {
        $type = fake()->randomElement(['percentage', 'fixed_amount']);
        $value = $type === 'percentage' ? fake()->numberBetween(5, 50) : fake()->numberBetween(10, 200) * 1000;

        return [
            'code' => strtoupper(Str::random(8)),
            'name' => fake()->sentence(4),
            'type' => $type,
            'value' => $value,
            'max_discount' => $type === 'percentage' ? fake()->numberBetween(20, 200) * 1000 : $value,
            'min_order_amount' => fake()->numberBetween(50, 500) * 1000,
            'usage_limit' => fake()->numberBetween(50, 1000),
            'used_count' => fake()->numberBetween(0, 100),
            'starts_at' => now()->subDays(rand(1, 10)),
            'expires_at' => now()->addDays(rand(10, 60)),
            'status' => 'active',
        ];
    }

    public function expired(): static
    {
        return $this->state(fn () => [
            'starts_at' => now()->subDays(60),
            'expires_at' => now()->subDays(10),
            'status' => 'expired',
        ]);
    }
}
