<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model
{
    use SoftDeletes;

    protected $table = 'authors';
    public $timestamps = false;

    protected $fillable = ['name', 'slug', 'country', 'biography', 'avatar'];

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'book_author', 'author_id', 'book_id');
    }
}
