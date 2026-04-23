<?php

namespace App\Models;

use App\Enums\NotificationType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $table = 'notifications';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'content',
        'url',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'type' => NotificationType::class,
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ── Display Logic Accessors ──
    public function getTypeConfigAttribute(): array
    {
        return match ($this->type?->value ?? 'system') {
            'order'     => ['icon' => 'package_2',     'bg' => 'bg-blue-50',  'color' => 'text-blue-500', 'label' => 'Đơn hàng'],
            'promotion' => ['icon' => 'local_offer',   'bg' => 'bg-rose-50',  'color' => 'text-primary',  'label' => 'Khuyến mãi'],
            'system'    => ['icon' => 'shield',        'bg' => 'bg-gray-100', 'color' => 'text-gray-500', 'label' => 'Hệ thống'],
            default     => ['icon' => 'notifications', 'bg' => 'bg-gray-100', 'color' => 'text-gray-500', 'label' => 'Thông báo'],
        };
    }

    public function getTimeAgoAttribute(): string
    {
        if (!$this->created_at) {
            return '';
        }
        return $this->created_at->diffForHumans();
    }
}
