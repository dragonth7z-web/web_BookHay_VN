<?php

namespace App\Repositories;

use App\Contracts\Repositories\DashboardRepositoryInterface;
use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\ReadingList;
use Illuminate\Database\Eloquent\Collection;

class DashboardRepository implements DashboardRepositoryInterface
{
    public function getRecentOrders(int $userId, int $take = 5): Collection
    {
        return Order::where('user_id', $userId)
            ->with('items.book')
            ->latest()
            ->take($take)
            ->get();
    }

    public function getOrderStats(int $userId): array
    {
        return [
            'total'     => Order::where('user_id', $userId)->count(),
            'pending'   => Order::where('user_id', $userId)->where('status', OrderStatus::Pending)->count(),
            'shipping'  => Order::where('user_id', $userId)->where('status', OrderStatus::Shipping)->count(),
            'completed' => Order::where('user_id', $userId)->where('status', OrderStatus::Completed)->count(),
        ];
    }

    public function getCurrentlyReading(int $userId): ?ReadingList
    {
        return ReadingList::where('user_id', $userId)
            ->whereNotNull('current_page')
            ->where('current_page', '>', 0)
            ->with('book')
            ->latest()
            ->first();
    }
}
