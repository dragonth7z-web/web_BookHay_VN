<?php

namespace App\Services;

use App\Contracts\Repositories\FlashSaleRepositoryInterface;
use App\Models\FlashSale;
use Illuminate\Database\Eloquent\Collection;

class FlashSalePageService
{
    public function __construct(
        private FlashSaleRepositoryInterface $flashSaleRepository
    ) {}

    public function getActiveSale(): ?FlashSale
    {
        return $this->flashSaleRepository->getActiveSale();
    }

    public function getUpcomingSales(int $limit = 3): Collection
    {
        return $this->flashSaleRepository->getUpcomingSales($limit);
    }
}
