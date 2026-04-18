<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class SystemLog extends Model
{
    protected $table = 'system_logs';

    protected $fillable = [
        'type',
        'action',
        'level',
        'description',
        'object_type',
        'object_id',
        'old_data',
        'new_data',
        'user_id',
        'user_name',
        'ip_address',
        'user_agent',
        'url',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];

    // ─── Relationships ───────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ─── Scopes ──────────────────────────────────────────────────────
    public function scopeAuth(Builder $query): Builder
    {
        return $query->where('type', 'auth');
    }

    public function scopeData(Builder $query): Builder
    {
        return $query->where('type', 'data');
    }

    public function scopeError(Builder $query): Builder
    {
        return $query->where('type', 'error');
    }

    public function scopeSecurity(Builder $query): Builder
    {
        return $query->where('type', 'security');
    }

    public function scopeCritical(Builder $query): Builder
    {
        return $query->where('level', 'critical');
    }
    public function scopeInfo(Builder $query): Builder
    {
        return $query->where('level', 'info');
    }
    // ─── Static helper – ghi log nhanh ───────────────────────────────
    public static function ghi(
        string $type,
        string $action,
        string $description,
        string $level = 'info',
        ?string $objectType = null,
        ?int $objectId = null,
        ?array $oldData = null,
        ?array $newData = null
    ): self {
        $user = auth()->user();
        $request = request();

        return self::create([
            'type' => $type,
            'action' => $action,
            'level' => $level,
            'description' => $description,
            'object_type' => $objectType,
            'object_id' => $objectId,
            'old_data' => $oldData,
            'new_data' => $newData,
            'user_id' => $user?->id,
            'user_name' => $user?->name ?? 'Hệ thống',
            'ip_address' => $request->ip(),
            'user_agent' => substr($request->userAgent() ?? '', 0, 255),
            'url' => substr($request->fullUrl() ?? '', 0, 500),
        ]);
    }

    // ─── Badge color helpers ─────────────────────────────────────────
    public function getLevelColorAttribute(): string
    {
        return match ($this->level) {
            'critical' => 'bg-red-100 text-red-700 border-red-200',
            'error' => 'bg-orange-100 text-orange-700 border-orange-200',
            'warning' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
            default => 'bg-blue-100 text-blue-700 border-blue-200',
        };
    }

    public function getTypeIconAttribute(): string
    {
        return match ($this->type) {
            'auth' => 'shield_person',
            'data' => 'database',
            'error' => 'error',
            'security' => 'gpp_maybe',
            default => 'info',
        };
    }

    public function getTypeColorAttribute(): string
    {
        return match ($this->type) {
            'auth' => 'bg-blue-500',
            'data' => 'bg-emerald-500',
            'error' => 'bg-red-500',
            'security' => 'bg-amber-500',
            default => 'bg-gray-500',
        };
    }
}
