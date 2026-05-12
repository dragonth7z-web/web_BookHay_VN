<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Publisher extends Model
{
    use SoftDeletes;

    protected $table = 'publishers';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'address',
        'phone',
        'email',
        'is_partner',
        'partner_icon',
        'partner_gradient',
    ];

    protected $casts = [
        'is_partner' => 'boolean',
    ];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class, 'publisher_id');
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class, 'publisher_id');
    }

    public function scopePartners($query)
    {
        return $query->where('is_partner', true);
    }

    // ── Display Logic Accessors ──────────────────────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return $this->is_partner ? 'Hoạt động' : 'Tạm dừng';
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return $this->is_partner
            ? 'bg-emerald-100 text-emerald-700 border-emerald-200'
            : 'bg-slate-100 text-slate-500 border-slate-200';
    }

    public function getLogoUrlAttribute(): string
    {
        if (empty($this->logo)) {
            return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=f1f5f9&color=64748b&size=80';
        }

        return preg_match('/^https?:\/\//', $this->logo)
            ? $this->logo
            : asset('storage/' . $this->logo);
    }

    public function getCodeAttribute(): string
    {
        return 'NXB' . str_pad($this->id, 3, '0', STR_PAD_LEFT);
    }
}
