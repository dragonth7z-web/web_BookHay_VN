<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $table = 'collections';

    protected $fillable = [
        'title', 'subtitle', 'badge', 'image',
        'url', 'is_visible', 'sort_order',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
    ];
}
