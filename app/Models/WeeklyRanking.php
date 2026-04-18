<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WeeklyRanking extends Model
{
    protected $table = 'weekly_rankings';

    protected $fillable = [
        'week_name',
        'week_start',
        'week_end',
    ];

    protected $casts = [
        'week_start' => 'date',
        'week_end' => 'date',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(WeeklyRankingItem::class, 'weekly_ranking_id')
            ->orderBy('rank');
    }

    public function scopeActive($query)
    {
        $today = now()->toDateString();
        return $query
            ->whereDate('week_start', '<=', $today)
            ->whereDate('week_end', '>=', $today);
    }
}
