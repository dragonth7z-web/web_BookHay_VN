<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeeklyRankingItem extends Model
{
    protected $table = 'weekly_ranking_items';

    protected $fillable = [
        'weekly_ranking_id',
        'book_id',
        'rank',
    ];

    protected $casts = [
        'rank' => 'int',
    ];

    public function weeklyRanking(): BelongsTo
    {
        return $this->belongsTo(WeeklyRanking::class, 'weekly_ranking_id');
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}
