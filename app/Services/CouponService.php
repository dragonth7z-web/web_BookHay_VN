<?php

namespace App\Services;

use App\Enums\CouponType;
use App\Models\Coupon;
use App\Models\CouponUsage;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class CouponService
{
    public function validate(string $code, float $orderAmount, int $userId): Coupon
    {
        $coupon = Coupon::where('code', $code)->firstOrFail();

        if ($coupon->expires_at && $coupon->expires_at->isPast()) {
            throw new InvalidArgumentException('Coupon đã hết hạn.');
        }

        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            throw new InvalidArgumentException('Coupon đã hết lượt sử dụng.');
        }

        if ($orderAmount < $coupon->min_order_amount) {
            throw new InvalidArgumentException("Đơn hàng tối thiểu {$coupon->min_order_amount}đ.");
        }

        return $coupon;
    }

    public function calculateDiscount(Coupon $coupon, float $orderAmount): float
    {
        if ($coupon->type === CouponType::Percentage) {
            return min($coupon->value / 100 * $orderAmount, $coupon->max_discount ?? PHP_FLOAT_MAX);
        }

        return min($coupon->value, $orderAmount);
    }

    public function markUsed(Coupon $coupon, int $userId, int $orderId): void
    {
        DB::transaction(function () use ($coupon, $userId, $orderId) {
            CouponUsage::create([
                'coupon_id' => $coupon->id,
                'user_id' => $userId,
                'order_id' => $orderId,
            ]);
            $coupon->increment('used_count');
        });
    }
}
