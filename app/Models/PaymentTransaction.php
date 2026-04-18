<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTransaction extends Model
{
    protected $table = 'payment_transactions';
    // migration has timestamps() — keep default true

    protected $fillable = [
        'order_id',
        'transaction_code',
        'method',
        'amount',
        'status',
        'response_data',
        'notes',
    ];

    protected $casts = [
        'response_data' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
