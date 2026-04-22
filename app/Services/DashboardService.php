<?php

namespace App\Services;

use App\Contracts\Repositories\DashboardRepositoryInterface;
use App\Models\ReadingList;
use Illuminate\Database\Eloquent\Collection;

class DashboardService
{
    public function __construct(
        private DashboardRepositoryInterface $dashboardRepository
    ) {}

    public function getRecentOrders(int $userId): Collection
    {
        return $this->dashboardRepository->getRecentOrders($userId);
    }

    public function getOrderStats(int $userId): array
    {
        return $this->dashboardRepository->getOrderStats($userId);
    }

    public function getCurrentlyReading(int $userId): ?ReadingList
    {
        return $this->dashboardRepository->getCurrentlyReading($userId);
    }
}
