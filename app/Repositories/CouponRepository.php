<?php

namespace App\Repositories;

use App\Enums\CouponStatus;
use App\Models\Coupon;
use Illuminate\Database\Eloquent\Collection;

class CouponRepository
{
    /**
     * Get all currently active coupons, ordered by expiry date.
     */
    public function getActiveCoupons(): Collection
    {
        return Coupon::where('status', CouponStatus::Active)
            ->where('starts_at', '<=', now())
            ->where('expires_at', '>=', now())
            ->orderBy('expires_at')
            ->get();
    }
}
