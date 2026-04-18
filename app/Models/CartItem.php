<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $table = 'cart_items';
    public $timestamps = false;

    protected $fillable = [
        'cart_id', 'book_id', 'quantity', 'price_snapshot', 'added_at',
    ];

    protected $casts = [
        'added_at' => 'datetime',
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}
