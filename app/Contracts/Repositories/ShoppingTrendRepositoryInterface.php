<?php

namespace App\Contracts\Repositories;

use Illuminate\Support\Collection;

interface ShoppingTrendRepositoryInterface
{
    /**
     * Get trending books filtered by period and optional category.
     *
     * @param  string      $period     day|week|month|year
     * @param  int|null    $categoryId
     * @param  int         $limit
     */
    public function getTrendingBooks(string $period, ?int $categoryId, int $limit): Collection;

    /**
     * Get all categories that have in-stock books, for filter tabs.
     *
     * @param  int  $limit
     */
    public function getFilterCategories(int $limit): Collection;
}
