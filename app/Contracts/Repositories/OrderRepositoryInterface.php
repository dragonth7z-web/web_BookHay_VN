<?php

namespace App\Contracts\Repositories;

use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrderRepositoryInterface
{
    public function forUser(int $userId, int $perPage = 10): LengthAwarePaginator;
    public function paginated(array $filters = [], int $perPage = 10): LengthAwarePaginator;
    public function findWithDetails(int $id): Order;
    public function paginatedWithFilters(array $filters, int $perPage = 20): \Illuminate\Contracts\Pagination\LengthAwarePaginator;
    public function findWithFullDetails(int $id): Order;
}
