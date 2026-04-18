<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    protected $table = 'comments';

    protected $fillable = [
        'book_id',
        'user_id',
        'parent_id',
        'content',
        'author_name',
        'author_avatar',
        'likes_count',
        'status',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
