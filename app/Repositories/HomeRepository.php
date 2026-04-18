<?php

namespace App\Repositories;

use App\Contracts\Repositories\HomeRepositoryInterface;
use App\Models\FlashSale;
use App\Models\WeeklyRanking;
use App\Models\Book;
use App\Models\Combo;
use App\Models\Collection;
use App\Models\Publisher;
use App\Models\Category;
use App\Models\Banner;
use App\Models\Coupon;
use App\Models\Setting;
use App\Enums\BookStatus;
use App\Enums\CouponStatus;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection as EloquentCollection;

class HomeRepository implements HomeRepositoryInterface
{
    protected $cacheTtl = 3600; // 1 hour caching

    /**
     * Helper to get limit from settings with fallback.
     */
    private function getLimit(string $key, int $default): int
    {
        return Cache::remember("setting_limit_{$key}", $this->cacheTtl, function () use ($key, $default) {
            try {
                $setting = Setting::where('key', $key)->first();
                return $setting ? (int) $setting->value : $default;
            } catch (\Exception $e) {
                return $default;
            }
        });
    }

    /**
     * Get flash sale books with fallbacks.
     */
    public function getFlashSaleBooks(): array
    {
        $limit = $this->getLimit('home_flash_sale_limit', 15);
        $flashSaleBooks = collect();
        $activeFlashSale = null;

        if (Schema::hasTable('flash_sales') && Schema::hasTable('flash_sale_items')) {
            $activeFlashSale = FlashSale::with([
                'items' => fn($q) => $q->orderBy('display_order')->with(['book' => fn($q) => $q->with('authors')]),
            ])->active()->first();

            if ($activeFlashSale && $activeFlashSale->items->count()) {
                foreach ($activeFlashSale->items->take($limit) as $item) {
                    $book = $item->book;
                    if (!$book)
                        continue;

                    $flashPrice = (int) ($item->flash_price ?? 0);
                    $book->is_sale = true;
                    $book->flash_price = $flashPrice;
                    $book->sale_percent = $book->original_price > 0
                        ? round((($book->original_price - $flashPrice) / $book->original_price) * 100)
                        : 0;

                    $total = ($book->stock ?? 0) + ($book->sold_count ?? 0);
                    $book->sold_percent = $total > 0
                        ? round((($book->sold_count ?? 0) / $total) * 100)
                        : 0;

                    $flashSaleBooks->push($book);
                }
            }
        }

        if ($flashSaleBooks->isEmpty()) {
            $flashSaleBooks = Book::with('authors')
                ->where('original_price', '>', \DB::raw('sale_price'))
                ->where('status', BookStatus::InStock)
                ->orderByDesc('sold_count')
                ->take($limit)->get();
        }

        if ($flashSaleBooks->isEmpty()) {
            $flashSaleBooks = Book::with('authors')
                ->where('status', BookStatus::InStock)
                ->orderByDesc('id')->take($limit)->get();
        }

        foreach ($flashSaleBooks as $book) {
            $book->is_sale = $book->is_sale ?? true;
            $book->flash_price = $book->flash_price ?? $book->sale_price;
            $fp = (int) ($book->flash_price ?? 0);
            $book->sale_percent = $book->original_price > 0
                ? round((($book->original_price - $fp) / $book->original_price) * 100)
                : 0;
            $total = ($book->stock ?? 0) + ($book->sold_count ?? 0);
            $book->sold_percent = $total > 0
                ? round((($book->sold_count ?? 0) / $total) * 100) : 0;
        }

        return [$flashSaleBooks, $activeFlashSale];
    }

    /**
     * Get weekly rankings.
     */
    public function getWeeklyRankings(int $limit = 15): EloquentCollection
    {
        // Don't override if a specific limit is requested via argument
        $limit = ($limit === 15) ? $this->getLimit('home_weekly_ranking_limit', 15) : $limit;
        $weeklyRankings = collect();
        if (Schema::hasTable('weekly_rankings') && Schema::hasTable('weekly_ranking_items')) {
            $activeWeeklyRanking = WeeklyRanking::with([
                'items' => fn($q) => $q->orderBy('rank')->with(['book' => fn($q) => $q->with(['authors', 'publisher', 'category'])]),
            ])->active()->first();

            if ($activeWeeklyRanking && $activeWeeklyRanking->items->count()) {
                $rankingBooks = $activeWeeklyRanking->items->take($limit)->pluck('book')->filter();

                // Backfill if not enough items in ranking
                if ($rankingBooks->count() < $limit) {
                    $need = $limit - $rankingBooks->count();
                    $excludeIds = $rankingBooks->pluck('id')->toArray();
                    $bestSellers = Book::with(['authors', 'publisher', 'category'])
                        ->where('status', BookStatus::InStock)
                        ->whereNotIn('id', $excludeIds)
                        ->orderByDesc('sold_count')
                        ->take($need)
                        ->get();
                    $weeklyRankings = $rankingBooks->merge($bestSellers);
                } else {
                    $weeklyRankings = $rankingBooks;
                }
            }
        }

        if ($weeklyRankings->isEmpty()) {
            $weeklyRankings = Book::with(['authors', 'publisher', 'category'])
                ->where('status', BookStatus::InStock)
                ->orderByDesc('sold_count')
                ->take($limit)
                ->get();
        }

        return $weeklyRankings;
    }

    /**
     * Get featured books.
     */
    public function getFeaturedBooks(): EloquentCollection
    {
        $limit = $this->getLimit('home_featured_limit', 15);

        $featuredBooks = Book::with(['authors', 'publisher', 'category'])
            ->where('is_featured', 1)
            ->where('status', BookStatus::InStock)
            ->orderByDesc('id')->take($limit)->get();

        if ($featuredBooks->isEmpty()) {
            $featuredBooks = Book::with('authors')
                ->where('status', BookStatus::InStock)->inRandomOrder()->take($limit)->get();
        }

        // Ensure we always return a collection even if DB is empty
        if (!$featuredBooks) {
            $featuredBooks = collect();
        }

        foreach ($featuredBooks as $book) {
            if ($book->original_price > $book->sale_price) {
                $book->is_sale = true;
                $book->sale_percent = $book->original_price > 0
                    ? round((($book->original_price - $book->sale_price) / $book->original_price) * 100)
                    : 0;
            }
        }

        return $featuredBooks;
    }

    /**
     * Get latest books with smart backfilling (12 books total).
     */
    public function getLatestBooks(int $limit = 12): EloquentCollection
    {
        return Cache::remember("home_latest_books_{$limit}", $this->cacheTtl, function () use ($limit) {
            $latest = Book::with(['authors', 'publisher', 'category'])
                ->where('status', BookStatus::InStock)
                ->latest()
                ->take($limit)
                ->get();

            if ($latest->count() < $limit) {
                $additionalNeeded = $limit - $latest->count();
                $others = Book::with(['authors', 'publisher', 'category'])
                    ->where('status', BookStatus::InStock)
                    ->whereNotIn('id', $latest->pluck('id'))
                    ->inRandomOrder()
                    ->take($additionalNeeded)
                    ->get();
                $latest = $latest->concat($others);
            }

            return $latest;
        });
    }

    /**
     * Get genre combos.
     */
    public function getCombos(int $take = 12): EloquentCollection
    {
        $limit = $this->getLimit('home_combos_limit', 12);
        return Cache::remember("home_all_combos_{$limit}", $this->cacheTtl, function () use ($limit) {
            return Combo::with(['books.category'])->where('is_visible', 1)->orderBy('sort_order')->take($limit)->get();
        });
    }

    /**
     * Get series.
     */
    public function getSeries(int $take = 4): EloquentCollection
    {
        return Cache::remember("home_series_{$take}", $this->cacheTtl, function () use ($take) {
            return Combo::with('books')->where('type', 'series')->where('is_visible', 1)->orderBy('sort_order')->take($take)->get();
        });
    }

    /**
     * Get collections.
     */
    public function getCollections(int $take = 6): EloquentCollection
    {
        return Cache::remember("home_collections_{$take}", $this->cacheTtl, function () use ($take) {
            return Collection::where('is_visible', 1)->orderBy('sort_order')->take($take)->get();
        });
    }

    /**
     * Get partners.
     */
    public function getPartners(): EloquentCollection
    {
        $limit = $this->getLimit('home_partners_limit', 8);
        return Cache::remember('home_partners', $this->cacheTtl, function () use ($limit) {
            return Publisher::where('is_partner', 1)->orderByDesc('id')->take($limit)->get();
        });
    }

    /**
     * Get recommended books.
     */
    public function getRecommendedBooks(int $take = 15): EloquentCollection
    {
        return Book::with(['authors', 'publisher', 'category'])
            ->where('status', BookStatus::InStock)->inRandomOrder()->take($take)->get();
    }

    /**
     * Get banners by position.
     */
    public function getBanners(string $position, int $take = null): EloquentCollection
    {
        return Cache::remember("home_banners_{$position}_{$take}", $this->cacheTtl, function () use ($position, $take) {
            // Position 'home_main' is core, others can be stricter
            $query = Banner::where('is_visible', 1)->where('position', $position)->orderBy('sort_order');
            if ($take) {
                $query->take($take);
            }
            return $query->get();
        });
    }

    /**
     * Get sidebar categories.
     */
    public function getSidebarCategories(int $take = 12): EloquentCollection
    {
        return Cache::remember("home_sidebar_cats_{$take}", $this->cacheTtl, function () use ($take) {
            return Category::whereNull('parent_id')->orderBy('sort_order')->take($take)->get();
        });
    }

    /**
     * Get vouchers.
     */
    public function getVouchers(int $take = 3): EloquentCollection
    {
        return Coupon::where('status', CouponStatus::Active)
            ->where('starts_at', '<=', now())
            ->where('expires_at', '>=', now())
            ->take($take)->get();
    }

    /**
     * Get Young Authors books.
     */
    public function getYoungAuthorsBooks(int $take = 4): EloquentCollection
    {
        $limit = $this->getLimit('home_featured_limit', 12); // Reusing featured limit or default to 12

        $books = Book::with(['authors', 'publisher', 'category'])
            ->where('is_featured', 1)
            ->where('status', BookStatus::InStock)
            ->orderByDesc('id')
            ->take($limit)
            ->get();

        if ($books->isEmpty()) {
            $books = Book::with(['authors', 'publisher', 'category'])
                ->where('status', BookStatus::InStock)
                ->orderByDesc('id')
                ->take($limit)
                ->get();
        }

        return $books;
    }

    /**
     * Get global settings/configs.
     */
    public function getConfigs(): EloquentCollection
    {
        return Cache::remember('home_configs', $this->cacheTtl, function () {
            return Setting::pluck('value', 'key');
        });
    }


    /**
     * Get static quick features.
     */
    public function getQuickFeatures(): array
    {
        return [
            ['icon' => 'local_offer', 'label' => 'Khuyến Mãi', 'badge' => 'Hot', 'color' => '#fef2f2', 'icolor' => '#C92127', 'gFrom' => '#C92127', 'gTo' => '#f43f5e', 'href' => route('books.search')],
            ['icon' => 'bolt', 'label' => 'Flash Sale', 'badge' => 'Hot', 'color' => '#fffbeb', 'icolor' => '#d97706', 'gFrom' => '#f59e0b', 'gTo' => '#d97706', 'href' => '#flash-sale'],
            ['icon' => 'star', 'label' => 'Bán Chạy', 'badge' => 'Hot', 'color' => '#f0fdf4', 'icolor' => '#16a34a', 'gFrom' => '#22c55e', 'gTo' => '#16a34a', 'href' => route('books.search')],
            ['icon' => 'confirmation_number', 'label' => 'Mã Giảm Giá', 'badge' => '', 'color' => '#faf5ff', 'icolor' => '#9333ea', 'gFrom' => '#a855f7', 'gTo' => '#9333ea', 'href' => route('books.search')],
            ['icon' => 'fiber_new', 'label' => 'Sản Phẩm Mới', 'badge' => 'Mới', 'color' => '#fff0f6', 'icolor' => '#db2777', 'gFrom' => '#ec4899', 'gTo' => '#db2777', 'href' => route('books.search')],
            ['icon' => 'published_with_changes', 'label' => 'Chợ Thu Cũ', 'badge' => 'Sắp ra mắt', 'color' => '#eff6ff', 'icolor' => '#2563eb', 'gFrom' => '#3b82f6', 'gTo' => '#2563eb', 'href' => route('books.search')],
            ['icon' => 'headphones', 'label' => 'Sách Nói', 'badge' => 'Sắp ra mắt', 'color' => '#f0fdf4', 'icolor' => '#0d9488', 'gFrom' => '#14b8a6', 'gTo' => '#0d9488', 'href' => route('account.dashboard')],
            ['icon' => 'newspaper', 'label' => 'Tin Tức - Sự Kiện', 'badge' => 'mới', 'color' => '#fff7ed', 'icolor' => '#ea580c', 'gFrom' => '#f97316', 'gTo' => '#ea580c', 'href' => route('books.search')],
            ['icon' => 'smart_toy', 'label' => 'Trợ Lý Sách', 'badge' => 'Sắp ra mắt', 'color' => '#f0fdf4', 'icolor' => '#15803d', 'gFrom' => '#16a34a', 'gTo' => '#15803d', 'href' => route('books.search')],
            ['icon' => 'support_agent', 'label' => 'Hỗ Trợ 24/7', 'badge' => '', 'color' => '#f8fafc', 'icolor' => '#475569', 'gFrom' => '#64748b', 'gTo' => '#475569', 'href' => route('home')],
        ];
    }
    /**
     * Get real trending search keywords from search_histories table.
     * Fallback to popular categories if search history is empty.
     */
    public function getTrendingKeywords(int $limit = 5): EloquentCollection
    {
        $keywords = collect();

        if (Schema::hasTable('search_histories')) {
            $keywords = DB::table('search_histories')
                ->select('keyword', DB::raw('count(*) as count'))
                ->groupBy('keyword')
                ->orderByDesc('count')
                ->limit($limit)
                ->pluck('keyword');
        }

        // Fallback: Use top categories if no search history
        if ($keywords->isEmpty() && Schema::hasTable('categories')) {
            $keywords = Category::orderByDesc('id') // Simplified popular category logic
                ->limit($limit)
                ->pluck('name');
        }

        return $keywords;
    }

    /**
     * Get dynamic categories that actually have books for the weekly ranking tabs.
     */
    public function getRankingCategories(int $limit = 8): EloquentCollection
    {
        return Cache::remember("home_ranking_categories_{$limit}", $this->cacheTtl, function () use ($limit) {
            return Category::whereHas('books', function ($query) {
                $query->where('status', BookStatus::InStock);
            })->withCount('books')
                ->orderByDesc('books_count')
                ->take($limit)
                ->get();
        });
    }

    /**
     * Get top selling books for a specific category.
     */
    public function getTopBooksByCategory($categoryId, int $limit = 5): EloquentCollection
    {
        return Book::with(['authors', 'publisher', 'category'])
            ->where('category_id', $categoryId)
            ->where('status', BookStatus::InStock)
            ->orderByDesc('sold_count')
            ->take($limit)
            ->get();
    }

    /**
     * Get trending books by period (week / month / year) based on order_items.
     * Falls back to sold_count if no order data exists for the period.
     */
    public function getTrendingBooks(string $period = 'week', $categoryId = null, int $limit = 10): EloquentCollection
    {
        $startDate = match ($period) {
            'day' => now()->startOfDay(),
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'year' => now()->startOfYear(),
            default => now()->startOfDay(),
        };

        $cacheKey = "trending_{$period}_{$categoryId}_{$limit}";

        return Cache::remember($cacheKey, 600, function () use ($startDate, $categoryId, $limit) {
            // Try to get real order-based trending data
            $query = Book::with(['authors', 'publisher', 'category'])
                ->where('status', BookStatus::InStock)
                ->whereHas('orderItems', function ($q) use ($startDate) {
                    $q->whereHas('order', fn($o) => $o->where('created_at', '>=', $startDate));
                })
                ->withSum([
                    'orderItems as period_sold' => function ($q) use ($startDate) {
                        $q->whereHas('order', fn($o) => $o->where('created_at', '>=', $startDate));
                    }
                ], 'quantity')
                ->orderByDesc('period_sold');

            if ($categoryId && $categoryId !== 'all') {
                $query->where('category_id', $categoryId);
            }

            $books = $query->take($limit)->get();

            // Fallback: use global sold_count if no order data
            if ($books->isEmpty()) {
                $fallback = Book::with(['authors', 'publisher', 'category'])
                    ->where('status', BookStatus::InStock);

                if ($categoryId && $categoryId !== 'all') {
                    $fallback->where('category_id', $categoryId);
                }

                $books = $fallback->orderByDesc('sold_count')->take($limit)->get();
            }

            return $books;
        });
    }
}
