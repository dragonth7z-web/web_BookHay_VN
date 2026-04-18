<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SearchHistory extends Model
{
    protected $table = 'search_histories';
    public $timestamps = false;

    protected $fillable = ['user_id', 'keyword'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
