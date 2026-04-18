<?php

namespace App\Services;

use App\Models\FlashSale;
use Illuminate\Support\Collection;

class FlashSaleService
{
    public function getActive(): ?FlashSale
    {
        return FlashSale::active()->with(['items.book'])->first();
    }

    public function isActive(FlashSale $fs): bool
    {
        $now = now();
        return $fs->start_date <= $now && $fs->end_date >= $now;
    }

    public function getBooksWithPrice(FlashSale $fs): Collection
    {
        return $fs->items->map(function ($item) {
            $book = $item->book;
            $original = (float) ($book->sale_price ?? $book->original_price ?? 0);
            $flash = (float) $item->flash_price;

            $item->discount_percent = $original > 0
                ? round((($original - $flash) / $original) * 100)
                : 0;

            $total = (int) ($book->stock ?? 0) + (int) ($book->sold_count ?? 0);
            $item->sold_percent = $total > 0
                ? round(((int) $book->sold_count / $total) * 100)
                : 0;

            return $item;
        });
    }
}
