<?php

namespace App\Contracts\Repositories;

use App\Models\SupportTicket;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface SupportTicketRepositoryInterface
{
    public function paginatedWithFilters(array $filters, int $perPage = 20): LengthAwarePaginator;

    public function findWithDetails(int $id): SupportTicket;

    public function create(array $data): SupportTicket;

    public function update(SupportTicket $ticket, array $data): SupportTicket;

    public function delete(SupportTicket $ticket): void;

    public function getStatusCounts(): array;
}
