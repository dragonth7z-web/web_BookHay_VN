<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrderItem extends Model
{
    protected $table = 'purchase_order_items';
    public $timestamps = false;

    protected $fillable = [
        'purchase_order_id',
        'book_id',
        'quantity',
        'cost_price',
    ];

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}
