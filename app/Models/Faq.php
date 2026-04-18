<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faq extends Model
{
    use SoftDeletes;

    protected $table = 'faqs';
    public $timestamps = false;

    protected $fillable = [
        'question',
        'answer',
        'sort_order',
        'is_visible',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
    ];
}
