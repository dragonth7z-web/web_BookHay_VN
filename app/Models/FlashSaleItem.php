<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlashSaleItem extends Model
{
    protected $table = 'flash_sale_items';

    protected $fillable = [
        'flash_sale_id',
        'book_id',
        'flash_price',
        'display_order',
    ];

    protected $casts = [
        'flash_price' => 'decimal:0',
        'display_order' => 'int',
    ];

    public function flashSale(): BelongsTo
    {
        return $this->belongsTo(FlashSale::class, 'flash_sale_id');
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}
