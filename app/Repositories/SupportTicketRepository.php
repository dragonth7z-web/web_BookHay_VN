<?php

namespace App\Repositories;

use App\Contracts\Repositories\SupportTicketRepositoryInterface;
use App\Enums\SupportTicketStatus;
use App\Models\SupportTicket;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class SupportTicketRepository implements SupportTicketRepositoryInterface
{
    public function paginatedWithFilters(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = SupportTicket::with(['user', 'assignedAdmin'])
            ->orderByDesc('created_at');

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('contact_name', 'like', "%{$search}%")
                  ->orWhere('contact_email', 'like', "%{$search}%");
            });
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function findWithDetails(int $id): SupportTicket
    {
        return SupportTicket::with(['user', 'assignedAdmin'])->findOrFail($id);
    }

    public function create(array $data): SupportTicket
    {
        $data['ticket_number'] = 'TKT-' . strtoupper(Str::random(8));
        return SupportTicket::create($data);
    }

    public function update(SupportTicket $ticket, array $data): SupportTicket
    {
        if (isset($data['status']) && $data['status'] === SupportTicketStatus::Resolved->value) {
            $data['resolved_at'] = now();
        }

        $ticket->update($data);
        return $ticket->fresh();
    }

    public function delete(SupportTicket $ticket): void
    {
        $ticket->delete();
    }

    public function getStatusCounts(): array
    {
        $counts = SupportTicket::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $total    = array_sum($counts);
        $open     = $counts['open'] ?? 0;
        $progress = $counts['in_progress'] ?? 0;
        $resolved = $counts['resolved'] ?? 0;
        $closed   = $counts['closed'] ?? 0;
        $done     = $resolved + $closed;

        return [
            'total'       => $total,
            'open'        => $open,
            'in_progress' => $progress,
            'resolved'    => $resolved,
            'closed'      => $closed,
            'done'        => $done,
            'done_rate'   => $total > 0 ? round($done / $total * 100) : 0,
            'progress_rate' => $total > 0 ? round($progress / $total * 100) : 0,
        ];
    }
}
