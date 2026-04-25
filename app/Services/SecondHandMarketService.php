<?php

namespace App\Services;

use App\Contracts\Repositories\SecondHandMarketRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SecondHandMarketService
{
    public function __construct(
        private SecondHandMarketRepositoryInterface $marketRepository
    ) {}

    public function getFeaturedBooks(): Collection
    {
        return $this->marketRepository->getFeaturedBooks();
    }

    public function getMarketStats(): array
    {
        return $this->marketRepository->getMarketStats();
    }

    public function getFilterCategories(): array
    {
        return $this->marketRepository->getFilterCategories();
    }
}
