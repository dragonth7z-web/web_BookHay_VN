<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $table = 'order_items';
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'book_id',
        'book_title_snapshot',
        'book_image_snapshot',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}
