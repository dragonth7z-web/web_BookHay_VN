<?php

namespace App\Contracts\Repositories;

use App\Models\FlashSale;
use Illuminate\Database\Eloquent\Collection;

interface FlashSaleRepositoryInterface
{
    public function getActiveSale(): ?FlashSale;

    public function getUpcomingSales(int $limit = 3): Collection;

    public function getDiscountedBooks(int $take = 8): Collection;
}
