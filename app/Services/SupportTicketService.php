<?php

namespace App\Services;

use App\Contracts\Repositories\SupportTicketRepositoryInterface;
use App\Models\SupportTicket;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SupportTicketService
{
    public function __construct(
        private SupportTicketRepositoryInterface $ticketRepository
    ) {}

    public function getPaginatedTickets(array $filters): LengthAwarePaginator
    {
        return $this->ticketRepository->paginatedWithFilters($filters);
    }

    public function getTicketDetails(int $id): SupportTicket
    {
        return $this->ticketRepository->findWithDetails($id);
    }

    public function createTicket(array $data): SupportTicket
    {
        return $this->ticketRepository->create($data);
    }

    public function updateTicket(SupportTicket $ticket, array $data): SupportTicket
    {
        return $this->ticketRepository->update($ticket, $data);
    }

    public function deleteTicket(SupportTicket $ticket): void
    {
        $this->ticketRepository->delete($ticket);
    }

    public function getStatusCounts(): array
    {
        return $this->ticketRepository->getStatusCounts();
    }
}
