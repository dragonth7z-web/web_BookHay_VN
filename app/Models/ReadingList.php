<?php

namespace App\Models;

use App\Enums\ReadingStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReadingList extends Model
{
    protected $table = 'reading_lists';

    protected $fillable = [
        'user_id',
        'book_id',
        'reading_status',
        'current_page',
        'total_pages',
        'personal_rating',
        'personal_notes',
        'quotes',
        'tags',
        'daily_page_goal',
        'started_at',
        'finished_at',
        'is_public',
    ];

    protected $casts = [
        'reading_status' => ReadingStatus::class,
        'is_public' => 'boolean',
        'started_at' => 'date',
        'finished_at' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}
