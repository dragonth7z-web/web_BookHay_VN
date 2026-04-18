<?php

namespace App\Models;

use App\Enums\Gender;
use App\Enums\UserStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'avatar',
        'date_of_birth',
        'gender',
        'role_id',
        'status',
        'loyalty_points',
        'total_spent',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'gender' => Gender::class,
            'status' => UserStatus::class,
            'date_of_birth' => 'date',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class, 'user_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function shippingAddresses(): HasMany
    {
        return $this->hasMany(ShippingAddress::class, 'user_id');
    }

    public function loginHistories(): HasMany
    {
        return $this->hasMany(LoginHistory::class, 'user_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'user_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    public function wishlistBooks(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'wishlists', 'user_id', 'book_id')
            ->withTimestamps();
    }

    public function readingLists(): HasMany
    {
        return $this->hasMany(ReadingList::class, 'user_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function searchHistories(): HasMany
    {
        return $this->hasMany(SearchHistory::class, 'user_id');
    }

    public function couponUsages(): HasMany
    {
        return $this->hasMany(CouponUsage::class, 'user_id');
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class, 'created_by');
    }
}
