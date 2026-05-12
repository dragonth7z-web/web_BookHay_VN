<?php

namespace App\Services;

use App\Contracts\Repositories\ShoppingTrendRepositoryInterface;
use Illuminate\Support\Collection;

class ShoppingTrendService
{
    public function __construct(
        private ShoppingTrendRepositoryInterface $trendRepo
    ) {}

    /**
     * Get trending books for the given period and optional category filter.
     *
     * @param  string    $period     day|week|month|year
     * @param  int|null  $categoryId
     * @param  int       $limit
     */
    public function getTrendingBooks(string $period, ?int $categoryId, int $limit = 10): Collection
    {
        $validPeriods = ['day', 'week', 'month', 'year'];
        $period       = in_array($period, $validPeriods) ? $period : 'day';

        return $this->trendRepo->getTrendingBooks($period, $categoryId, $limit);
    }

    /**
     * Get categories for the filter tab bar.
     */
    public function getFilterCategories(int $limit = 10): Collection
    {
        return $this->trendRepo->getFilterCategories($limit);
    }
}
