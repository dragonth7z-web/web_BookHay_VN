<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Publisher extends Model
{
    use SoftDeletes;

    protected $table = 'publishers';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'address',
        'phone',
        'email',
        'is_partner',
        'partner_icon',
        'partner_gradient',
    ];

    protected $casts = [
        'is_partner' => 'boolean',
    ];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class, 'publisher_id');
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class, 'publisher_id');
    }

    public function scopePartners($query)
    {
        return $query->where('is_partner', true);
    }
}
