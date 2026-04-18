<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FlashSale extends Model
{
    protected $table = 'flash_sales';

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(FlashSaleItem::class, 'flash_sale_id')
            ->orderBy('display_order');
    }

    public function scopeActive($query)
    {
        $now = now();
        return $query
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now);
    }
}
