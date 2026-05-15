<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    protected $table = 'purchase_orders';
    public $timestamps = false;        // Bảng chỉ có created_at, không có updated_at
    const CREATED_AT = 'created_at';   // Vẫn tự điền created_at khi insert

    protected $fillable = [
        'po_number',
        'publisher_id',
        'created_by',
        'total_amount',
        'notes',
        'created_at',
    ];

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class, 'publisher_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Alias để tương thích với các view cũ dùng ->user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class, 'purchase_order_id');
    }
}
