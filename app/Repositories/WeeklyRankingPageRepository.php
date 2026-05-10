<?php

namespace App\Repositories;

use App\Contracts\Repositories\WeeklyRankingPageRepositoryInterface;
use App\Enums\BookStatus;
use App\Models\Book;
use App\Models\Category;
use App\Models\WeeklyRanking;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WeeklyRankingPageRepository implements WeeklyRankingPageRepositoryInterface
{
    /**
     * Get the currently active weekly ranking with all items and books.
     */
    public function getActiveRanking(): ?WeeklyRanking
    {
        return WeeklyRanking::active()
            ->with([
                'items' => fn ($q) => $q->orderBy('rank')
                    ->with(['book' => fn ($q) => $q->with(['authors', 'category', 'publisher'])]),
            ])
            ->first();
    }

    /**
     * Get the top-ranked book (rank = 1) from the active ranking.
     * Falls back to the best-selling book if no ranking exists.
     */
    public function getTopBook(): ?object
    {
        $ranking = $this->getActiveRanking();

        if ($ranking && $ranking->items->isNotEmpty()) {
            $topItem = $ranking->items->firstWhere('rank', 1) ?? $ranking->items->first();
            return $topItem?->book;
        }

        return Book::with(['authors', 'category', 'publisher'])
            ->where('status', BookStatus::InStock)
            ->orderByDesc('sold_count')
            ->first();
    }

    /**
     * Get ranked books from the active ranking, excluding rank 1.
     */
    public function getRankedBooks(int $limit = 10): Collection
    {
        $ranking = $this->getActiveRanking();

        if ($ranking && $ranking->items->count() > 1) {
            return $ranking->items
                ->where('rank', '>', 1)
                ->take($limit)
                ->map(fn ($item) => $item->book)
                ->filter()
                ->values();
        }

        // Fallback: best-sellers excluding the #1 book
        $topBook = $this->getTopBook();

        return Book::with(['authors', 'category'])
            ->where('status', BookStatus::InStock)
            ->when($topBook, fn ($q) => $q->where('id', '!=', $topBook->id))
            ->orderByDesc('sold_count')
            ->take($limit)
            ->get();
    }

    /**
     * Get insight stats for the sidebar.
     */
    public function getInsightStats(): array
    {
        $newTitlesCount = Book::where('status', BookStatus::InStock)
            ->where('created_at', '>=', now()->startOfWeek())
            ->count();

        $discussionCount = 0;
        if (Schema::hasTable('comments')) {
            $discussionCount = DB::table('comments')
                ->where('created_at', '>=', now()->startOfWeek())
                ->count();
        }

        return [
            'new_titles'       => $newTitlesCount,
            'discussion_count' => $discussionCount,
        ];
    }

    /**
     * Get trending hashtags derived from top categories and book titles.
     */
    public function getTrendingTags(int $limit = 8): Collection
    {
        return Category::whereHas('books', fn ($q) => $q->where('status', BookStatus::InStock))
            ->withCount('books')
            ->orderByDesc('books_count')
            ->take($limit)
            ->get()
            ->map(fn ($cat) => '#' . str_replace(' ', '', $cat->name));
    }
}
