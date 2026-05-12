<?php

namespace App\Models;

use App\Enums\BookStatus;
use App\Enums\CoverType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'books';

    protected $fillable = [
        'sku',
        'title',
        'slug',
        'category_id',
        'publisher_id',
        'cost_price',
        'original_price',
        'sale_price',
        'stock',
        'sold_count',
        'description',
        'short_description',
        'cover_image',
        'extra_images',
        'isbn',
        'pages',
        'weight',
        'cover_type',
        'language',
        'published_year',
        'rating_avg',
        'rating_count',
        'status',
        'is_featured',
        'dimensions',
        'material',
        'base_interest',
    ];

    protected $casts = [
        'status' => BookStatus::class,
        'cover_type' => CoverType::class,
        'extra_images' => 'array',
        'is_featured' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class, 'publisher_id');
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'book_author', 'book_id', 'author_id');
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class, 'book_id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'book_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'book_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'book_id');
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(ReadingList::class, 'book_id');
    }

    public function readingLists(): HasMany
    {
        return $this->hasMany(ReadingList::class, 'book_id');
    }

    public function purchaseOrderItems(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class, 'book_id');
    }

    public function combos(): BelongsToMany
    {
        return $this->belongsToMany(Combo::class, 'book_combo', 'book_id', 'combo_id');
    }
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getCoverImageUrlAttribute(): string
    {
        if (empty($this->cover_image)) {
            return 'https://ui-avatars.com/api/?name=' . urlencode($this->title) . '&background=f1f5f9&color=64748b&size=512';
        }

        return preg_match('/^https?:\/\//', $this->cover_image)
            ? $this->cover_image
            : asset('storage/' . $this->cover_image);
    }

    public function getStatusBadgeAttribute(): array
    {
        $status = $this->status?->value ?? 'in_stock';
        
        return match ($status) {
            'in_stock' => [
                'class' => 'admin-badge admin-badge-green',
                'label' => 'Còn hàng',
            ],
            'out_of_stock' => [
                'class' => 'admin-badge admin-badge-yellow',
                'label' => 'Hết hàng',
            ],
            default => [
                'class' => 'admin-badge admin-badge-gray',
                'label' => 'Ngừng KB',
            ],
        };
    }

    // ── Wishlist Display Logic Accessors ──
    public function getCurrentPriceAttribute(): float
    {
        return $this->sale_price ?? $this->original_price ?? 0;
    }

    public function getHasDiscountAttribute(): bool
    {
        return $this->original_price > 0 && $this->current_price < $this->original_price;
    }

    public function getDiscountPercentageAttribute(): int
    {
        if (!$this->has_discount) {
            return 0;
        }
        return (int) round((1 - $this->current_price / $this->original_price) * 100);
    }

    public function getIsOutOfStockAttribute(): bool
    {
        return $this->stock !== null && $this->stock <= 0;
    }

    public function getFormattedCurrentPriceAttribute(): string
    {
        return number_format($this->current_price, 0, ',', '.') . 'đ';
    }

    public function getFormattedOriginalPriceAttribute(): string
    {
        return number_format($this->original_price, 0, ',', '.') . 'đ';
    }

    // ── Second-hand market display ──
    public function getConditionBadgeAttribute(): array
    {
        $conditions = [
            ['label' => 'LIKE NEW',  'class' => 'bg-green-500'],
            ['label' => 'GOOD',      'class' => 'bg-blue-500'],
            ['label' => 'FAIR',      'class' => 'bg-amber-500'],
            ['label' => 'RARE FIND', 'class' => 'bg-purple-600'],
        ];

        return $conditions[$this->id % 4];
    }
}
