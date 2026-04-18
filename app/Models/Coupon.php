<?php

namespace App\Models;

use App\Enums\CouponStatus;
use App\Enums\CouponType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use SoftDeletes;

    protected $table = 'coupons';
    public $timestamps = false;

    protected $fillable = [
        'code',
        'name',
        'type',
        'value',
        'max_discount',
        'min_order_amount',
        'usage_limit',
        'used_count',
        'starts_at',
        'expires_at',
        'status',
        'ui_icon',
        'theme_class',
        'overlay_gradient',
        'glow_color',
    ];

    protected $casts = [
        'type' => CouponType::class,
        'status' => CouponStatus::class,
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'coupon_id');
    }

    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class, 'coupon_id');
    }
}
