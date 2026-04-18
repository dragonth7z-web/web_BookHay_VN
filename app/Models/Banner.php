<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use SoftDeletes;

    protected $table = 'banner';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'title',
        'badge_text',
        'description',
        'image',
        'image_url',
        'url',
        'button_text',
        'position',
        'sort_order',
        'is_visible',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
    ];
}
