<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Book;

class Combo extends Model
{
    protected $table = 'combos';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'type',
        'name',
        'badge_text',
        'slug',
        'button_text',
        'description',
        'original_price',
        'sale_price',
        'bg_from',
        'bg_to',
        'icon',
        'image',
        'is_visible',
        'sort_order',
    ];

    protected $casts = [
        'original_price' => 'decimal:0',
        'sale_price' => 'decimal:0',
        'is_visible' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    public function books()
    {
        return $this->belongsToMany(Book::class, 'book_combo', 'combo_id', 'book_id');
    }

    public function getImageUrlAttribute(): string
    {
        if (empty($this->image)) {
            return asset('images/placeholder-collection.png');
        }

        return preg_match('/^https?:\/\//', $this->image)
            ? $this->image
            : asset('storage/' . $this->image);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
