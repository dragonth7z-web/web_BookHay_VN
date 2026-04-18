<?php

namespace App\Repositories;

use App\Models\Order;
use App\Contracts\Repositories\OrderRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderRepository implements OrderRepositoryInterface
{
    public function forUser(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        return Order::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function paginated(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Order::query();

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function findWithDetails(int $id): Order
    {
        return Order::with(['user', 'coupon', 'items'])->findOrFail($id);
    }

    public function paginatedWithFilters(array $filters, int $perPage = 20): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = Order::with('user')->orderByDesc('created_at');

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->paginate($perPage);
    }

    public function findWithFullDetails(int $id): Order
    {
        return Order::with(['user', 'items.book', 'coupon', 'paymentTransactions'])->findOrFail($id);
    }
}
