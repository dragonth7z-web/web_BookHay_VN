<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'order_number',
        'user_id',
        'recipient_name',
        'recipient_phone',
        'shipping_address',
        'subtotal',
        'shipping_fee',
        'discount_amount',
        'total',
        'coupon_id',
        'transaction_ref',
        'payment_method',
        'payment_status',
        'status',
        'notes',
        'cancel_reason',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
        'payment_status' => PaymentStatus::class,
        'payment_method' => PaymentMethod::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function paymentTransactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class, 'order_id');
    }

    public function couponUsages(): HasMany
    {
        return $this->hasMany(CouponUsage::class, 'order_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'order_id');
    }
}
