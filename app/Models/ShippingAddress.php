<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingAddress extends Model
{
    protected $table = 'shipping_addresses';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'recipient_name',
        'recipient_phone',
        'province',
        'district',
        'ward',
        'address_detail',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
