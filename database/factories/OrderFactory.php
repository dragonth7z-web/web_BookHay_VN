<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $subtotal = fake()->numberBetween(50, 500) * 1000;
        $shippingFee = $subtotal >= 200000 ? 0 : 30000;
        $discount = rand(0, 1) ? round($subtotal * 0.1) : 0;
        $total = $subtotal + $shippingFee - $discount;

        return [
            'order_number' => 'ORD-' . str_pad(fake()->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'user_id' => User::where('role_id', 2)->inRandomOrder()->value('id') ?? 1,
            'recipient_name' => fake()->name(),
            'recipient_phone' => '09' . fake()->numerify('########'),
            'shipping_address' => fake()->address(),
            'subtotal' => $subtotal,
            'shipping_fee' => $shippingFee,
            'discount_amount' => $discount,
            'total' => $total,
            'coupon_id' => null,
            'payment_method' => fake()->randomElement(['cod', 'vnpay', 'momo', 'bank_transfer']),
            'payment_status' => 'unpaid',
            'status' => 'pending',
            'notes' => null,
            'cancel_reason' => null,
        ];
    }

    public function completed(): static
    {
        return $this->state(fn () => [
            'status' => 'completed',
            'payment_status' => 'paid',
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn () => [
            'status' => 'cancelled',
            'cancel_reason' => fake()->sentence(),
        ]);
    }
}
