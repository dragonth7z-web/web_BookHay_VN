<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $table = 'categories';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'parent_id',
        'sort_order',
        'is_visible',
        'badge_text',
        'badge_color',
        'icon',
        'text_color',
        'bg_gradient',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function books(): HasMany
    {
        return $this->hasMany(Book::class, 'category_id');
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }
}
