<?php

namespace App\Models;

use App\Enums\SupportTicketPriority;
use App\Enums\SupportTicketStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportTicket extends Model
{
    protected $table = 'support_tickets';

    protected $fillable = [
        'user_id',
        'ticket_number',
        'subject',
        'description',
        'status',
        'priority',
        'category',
        'contact_email',
        'contact_name',
        'admin_note',
        'assigned_to',
        'resolved_at',
    ];

    protected $casts = [
        'status'      => SupportTicketStatus::class,
        'priority'    => SupportTicketPriority::class,
        'resolved_at' => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignedAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // ── Accessors ──────────────────────────────────────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return $this->status?->label() ?? '—';
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return $this->status?->badgeClass() ?? 'bg-gray-100 text-gray-600';
    }

    public function getPriorityLabelAttribute(): string
    {
        return $this->priority?->label() ?? '—';
    }

    public function getPriorityBadgeClassAttribute(): string
    {
        return $this->priority?->badgeClass() ?? 'bg-gray-100 text-gray-600';
    }

    public function getRequesterNameAttribute(): string
    {
        return $this->user?->name ?? $this->contact_name ?? 'Khách vãng lai';
    }

    public function getRequesterEmailAttribute(): string
    {
        return $this->user?->email ?? $this->contact_email ?? '—';
    }
}
