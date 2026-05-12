<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model
{
    use SoftDeletes;

    protected $table = 'authors';
    public $timestamps = true;

    protected $fillable = ['name', 'slug', 'country', 'biography', 'avatar'];

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'book_author', 'author_id', 'book_id');
    }

    // ── Display Logic Accessors ──────────────────────────────────────────────

    public function getAvatarUrlAttribute(): string
    {
        if (empty($this->avatar)) {
            return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=C92127&color=fff&size=80';
        }

        return preg_match('/^https?:\/\//', $this->avatar)
            ? $this->avatar
            : asset('storage/' . $this->avatar);
    }

    public function getCodeAttribute(): string
    {
        return 'AUTH-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }

    public function getStatusLabelAttribute(): string
    {
        $count = $this->books_count ?? $this->books()->count();
        return $count > 0 ? 'Đang hoạt động' : 'Chưa có sách';
    }

    public function getStatusBadgeClassAttribute(): string
    {
        $count = $this->books_count ?? $this->books()->count();
        return $count > 0
            ? 'bg-emerald-100 text-emerald-700 border-emerald-200'
            : 'bg-slate-100 text-slate-500 border-slate-200';
    }
}
