<?php

namespace App\Repositories;

use App\Contracts\Repositories\FlashSaleRepositoryInterface;
use App\Enums\BookStatus;
use App\Models\FlashSale;
use Illuminate\Database\Eloquent\Collection;

class FlashSaleRepository implements FlashSaleRepositoryInterface
{
    public function getActiveSale(): ?FlashSale
    {
        return FlashSale::with([
            'items' => fn($q) => $q->orderBy('display_order')
                ->with(['book' => fn($q) => $q->with(['authors', 'category'])]),
        ])
        ->active()
        ->first();
    }

    public function getUpcomingSales(int $limit = 3): Collection
    {
        return FlashSale::where('start_date', '>', now())
            ->orderBy('start_date')
            ->take($limit)
            ->get();
    }

    public function getDiscountedBooks(int $take = 8): Collection
    {
        return \App\Models\Book::with(['authors', 'category'])
            ->where('status', BookStatus::InStock)
            ->whereColumn('original_price', '>', 'sale_price')
            ->orderByDesc('sold_count')
            ->take($take)
            ->get();
    }
}
