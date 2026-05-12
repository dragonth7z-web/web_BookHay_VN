<?php

namespace App\Repositories;

use App\Contracts\Repositories\ShoppingTrendRepositoryInterface;
use App\Enums\BookStatus;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ShoppingTrendRepository implements ShoppingTrendRepositoryInterface
{
    /**
     * Get trending books filtered by period and optional category.
     */
    public function getTrendingBooks(string $period, ?int $categoryId, int $limit): Collection
    {
        $startDate = match ($period) {
            'day'   => now()->startOfDay(),
            'week'  => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'year'  => now()->startOfYear(),
            default => now()->startOfDay(),
        };

        $cacheKey = "shopping_trend_{$period}_{$categoryId}_{$limit}";

        return Cache::remember($cacheKey, 600, function () use ($startDate, $categoryId, $limit) {
            $query = Book::with(['authors', 'category'])
                ->where('status', BookStatus::InStock)
                ->whereHas('orderItems', function ($q) use ($startDate) {
                    $q->whereHas('order', fn ($o) => $o->where('created_at', '>=', $startDate));
                })
                ->withSum([
                    'orderItems as period_sold' => function ($q) use ($startDate) {
                        $q->whereHas('order', fn ($o) => $o->where('created_at', '>=', $startDate));
                    },
                ], 'quantity')
                ->orderByDesc('period_sold');

            if ($categoryId) {
                $query->where('category_id', $categoryId);
            }

            $books = $query->take($limit)->get();

            // Fallback: use global sold_count when no order data exists
            if ($books->isEmpty()) {
                $fallback = Book::with(['authors', 'category'])
                    ->where('status', BookStatus::InStock);

                if ($categoryId) {
                    $fallback->where('category_id', $categoryId);
                }

                $books = $fallback->orderByDesc('sold_count')->take($limit)->get();
            }

            // Attach discount percentage accessor-style
            foreach ($books as $book) {
                if ($book->original_price > $book->sale_price && $book->original_price > 0) {
                    $book->is_sale      = true;
                    $book->sale_percent = round(
                        (($book->original_price - $book->sale_price) / $book->original_price) * 100
                    );
                }
            }

            return $books;
        });
    }

    /**
     * Get categories that have in-stock books, for filter tabs.
     */
    public function getFilterCategories(int $limit): Collection
    {
        return Cache::remember("shopping_trend_categories_{$limit}", 3600, function () use ($limit) {
            return Category::whereHas('books', fn ($q) => $q->where('status', BookStatus::InStock))
                ->withCount('books')
                ->orderByDesc('books_count')
                ->take($limit)
                ->get();
        });
    }
}
