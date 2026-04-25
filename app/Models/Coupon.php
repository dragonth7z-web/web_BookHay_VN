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

    // ── Accessors ─────────────────────────────────────────────────────────────

    /**
     * Human-readable discount label: "-20%" or "-50.000đ"
     */
    public function getDiscountLabelAttribute(): string
    {
        if ($this->type === CouponType::Percentage) {
            return '-' . $this->value . '%';
        }

        return '-' . number_format($this->value, 0, ',', '.') . 'đ';
    }

    /**
     * Icon configuration for voucher card display.
     * Returns bg color, icon content, and label based on coupon type and ui_icon field.
     */
    public function getIconConfigAttribute(): array
    {
        // Free shipping — detected via ui_icon field or name keyword
        $isFreeShip = $this->ui_icon === 'free_ship'
            || str_contains(strtolower($this->name ?? ''), 'free')
            || str_contains(strtolower($this->name ?? ''), 'ship')
            || str_contains(strtolower($this->name ?? ''), 'vận chuyển');

        if ($isFreeShip) {
            return [
                'type'     => 'freeship',
                'bg'       => 'bg-green-50',
                'tab_bg'   => 'bg-green-800',
                'icon_bg'  => 'bg-green-100',
                'symbol'   => null,
                'label'    => 'FREESHIP',
            ];
        }

        if ($this->type === CouponType::Percentage) {
            return [
                'type'     => 'percentage',
                'bg'       => 'bg-rose-50',
                'tab_bg'   => 'bg-primary',
                'icon_bg'  => 'bg-primary/10',
                'symbol'   => '%',
                'label'    => null,
            ];
        }

        // Fixed amount
        return [
            'type'     => 'fixed',
            'bg'       => 'bg-rose-50',
            'tab_bg'   => 'bg-primary',
            'icon_bg'  => 'bg-primary/10',
            'symbol'   => 'đ',
            'label'    => null,
        ];
    }

    /**
     * Days remaining until expiry. Negative means expired.
     */
    public function getDaysRemainingAttribute(): int
    {
        return (int) now()->diffInDays($this->expires_at, false);
    }

    /**
     * Remaining usage count, or null if unlimited.
     */
    public function getRemainingUsageAttribute(): ?int
    {
        return $this->usage_limit ? $this->usage_limit - $this->used_count : null;
    }

    /**
     * CSS urgency class for expiry display.
     */
    public function getExpiryUrgencyClassAttribute(): string
    {
        return $this->days_remaining <= 3 ? 'text-rose-500' : 'text-gray-400';
    }

    /**
     * Human-readable expiry label.
     */
    public function getExpiryLabelAttribute(): string
    {
        if ($this->days_remaining <= 0) {
            return 'Hết hạn hôm nay';
        }

        if ($this->days_remaining <= 3) {
            return 'Còn ' . $this->days_remaining . ' ngày';
        }

        return 'HSD: ' . $this->expires_at->format('d/m/Y');
    }
}
