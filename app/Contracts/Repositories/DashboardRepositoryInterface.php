<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface DashboardRepositoryInterface
{
    public function getRecentOrders(int $userId, int $take = 5): Collection;

    public function getOrderStats(int $userId): array;

    public function getCurrentlyReading(int $userId): ?\App\Models\ReadingList;
}
