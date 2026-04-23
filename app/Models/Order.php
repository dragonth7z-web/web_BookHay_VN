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

    // ── Display Logic Accessors ──
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status?->value) {
            'completed', 'delivered' => 'bg-green-100 text-green-700 border-green-200',
            'pending'                => 'bg-orange-100 text-orange-700 border-orange-200',
            'shipping'               => 'bg-blue-100 text-blue-700 border-blue-200',
            'cancelled'              => 'bg-gray-100 text-gray-600 border-gray-200',
            default                  => 'bg-slate-100 text-slate-600 border-slate-200',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status?->value) {
            'completed', 'delivered' => 'Hoàn thành',
            'pending'                => 'Chờ xác nhận',
            'shipping'               => 'Đang giao',
            'cancelled'              => 'Đã hủy',
            default                  => $this->status?->value ?? '—',
        };
    }

    public function getFormattedTotalAttribute(): string
    {
        return number_format($this->total, 0, ',', '.') . ' đ';
    }

    public function getOrderSummaryAttribute(): string
    {
        $first = $this->items->first();
        if (!$first) {
            return 'Trống';
        }

        $title = $first->book->title ?? $first->book_title_snapshot;
        $remaining = $this->items->count() - 1;

        return $remaining > 0 ? "{$title} + {$remaining} sản phẩm khác..." : $title;
    }
}
