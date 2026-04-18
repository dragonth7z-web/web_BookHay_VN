<?php

namespace App\Models;

use App\Enums\ReviewStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $table = 'reviews';

    // reviews table only has created_at (no updated_at)
    public $timestamps = false;

    protected $fillable = [
        'book_id',
        'user_id',
        'order_id',
        'rating',
        'content',
        'status',
    ];

    protected $casts = [
        'status' => ReviewStatus::class,
        'created_at' => 'datetime',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
