<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface SecondHandMarketRepositoryInterface
{
    public function getFeaturedBooks(int $take = 9): Collection;

    public function getMarketStats(): array;

    public function getFilterCategories(): array;
}
