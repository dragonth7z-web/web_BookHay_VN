<?php

namespace App\Repositories;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use App\Enums\BookStatus;
use Illuminate\Database\Eloquent\Collection;
use App\Contracts\Repositories\BookRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class BookRepository implements BookRepositoryInterface
{
    public function available(): Collection
    {
        return Book::where('status', 'in_stock')->orderBy('title')->get();
    }

    public function findOrFail(int $id): Book
    {
        return Book::findOrFail($id);
    }

    public function findBySlug(string $slug): ?Book
    {
        return Book::where('slug', $slug)->first();
    }

    public function search(string $keyword, int $perPage = 20): LengthAwarePaginator
    {
        return Book::where('title', 'like', "%{$keyword}%")
            ->orWhere('short_description', 'like', "%{$keyword}%")
            ->paginate($perPage);
    }

    public function getFeatured(int $take = 8): Collection
    {
        return Book::where('is_featured', 1)
            ->where('status', 'in_stock')
            ->orderByDesc('id')
            ->take($take)
            ->get();
    }

    public function getRecommended(int $take = 10): Collection
    {
        return Book::where('status', 'in_stock')
            ->inRandomOrder()
            ->take($take)
            ->get();
    }

    public function decrementStock(int $bookId, int $quantity): void
    {
        Book::where('id', $bookId)
            ->decrement('stock', $quantity, ['sold_count' => \DB::raw("sold_count + {$quantity}")]);
    }

    public function paginatedWithRelations(int $perPage = 20): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Book::with(['category', 'publisher'])->orderByDesc('created_at')->paginate($perPage);
    }

    public function getAllCategories(): Collection
    {
        return Category::where('is_visible', true)->orderBy('sort_order')->get();
    }

    public function getAllPublishers(): Collection
    {
        return Publisher::orderBy('name')->get();
    }

    public function getAllAuthors(): Collection
    {
        return Author::orderBy('name')->get();
    }

    public function getForCardDisplay(int $take = 20): Collection
    {
        return Book::with(['category', 'authors'])->where('status', 'active')->take($take)->get();
    }

    /**
     * Pass 1: SQL LIKE — exact phrase + condensed (no-space) match on title and author name.
     */
    public function fuzzySearchPass1(string $query, int $limit = 6): Collection
    {
        $q        = mb_strtolower(trim($query));
        $qNoSpace = str_replace(' ', '', $q);

        return Book::with('authors')
            ->where('status', BookStatus::InStock)
            ->where(function ($sub) use ($q, $qNoSpace) {
                $sub->whereRaw('LOWER(title) LIKE ?', ["%{$q}%"])
                    ->orWhereRaw("REPLACE(LOWER(title), ' ', '') LIKE ?", ["%{$qNoSpace}%"])
                    ->orWhereHas('authors', fn($t) =>
                        $t->whereRaw('LOWER(name) LIKE ?', ["%{$q}%"])
                          ->orWhereRaw("REPLACE(LOWER(name), ' ', '') LIKE ?", ["%{$qNoSpace}%"])
                    );
            })
            ->orderByDesc('sold_count')
            ->take($limit)
            ->get();
    }

    /**
     * Pass 2: bigram scoring — PHP-side scoring for typo-tolerant search.
     * Requires at least 80% of bigrams to match.
     */
    public function fuzzySearchPass2(string $query, int $limit = 6): Collection
    {
        $q       = mb_strtolower(trim($query));
        $bigrams = $this->buildBigrams($q);

        if (count($bigrams) < 3) {
            return new Collection();
        }

        $needed     = (int) ceil(count($bigrams) * 0.8);
        $candidates = Book::with('authors')
            ->where('status', BookStatus::InStock)
            ->select('id', 'title', 'slug', 'cover_image', 'sale_price', 'sold_count')
            ->get();

        $scored = $candidates
            ->map(function ($book) use ($bigrams) {
                $titleLower    = mb_strtolower($book->title);
                $book->_score  = collect($bigrams)
                    ->filter(fn($bg) => mb_strpos($titleLower, $bg) !== false)
                    ->count();
                return $book;
            })
            ->filter(fn($b) => $b->_score >= $needed)
            ->sortByDesc('_score')
            ->take($limit)
            ->values();

        if ($scored->isNotEmpty()) {
            $scored->load('authors');
        }

        return $scored;
    }

    /**
     * Get IDs of books matching fuzzy search — used by paginated listing.
     */
    public function getFuzzySearchIds(string $query): Collection
    {
        $q        = mb_strtolower(trim($query));
        $qNoSpace = str_replace(' ', '', $q);

        $pass1Ids = Book::where('status', BookStatus::InStock)
            ->where(function ($sub) use ($q, $qNoSpace) {
                $sub->whereRaw('LOWER(title) LIKE ?', ["%{$q}%"])
                    ->orWhereRaw("REPLACE(LOWER(title), ' ', '') LIKE ?", ["%{$qNoSpace}%"])
                    ->orWhereHas('authors', fn($t) =>
                        $t->whereRaw('LOWER(name) LIKE ?', ["%{$q}%"])
                          ->orWhereRaw("REPLACE(LOWER(name), ' ', '') LIKE ?", ["%{$qNoSpace}%"])
                    );
            })
            ->pluck('id');

        if ($pass1Ids->isNotEmpty()) {
            return $pass1Ids;
        }

        // Bigram fallback
        $bigrams = $this->buildBigrams($q);
        if (count($bigrams) < 3) {
            return new Collection();
        }

        $needed = (int) ceil(count($bigrams) * 0.8);

        return Book::where('status', BookStatus::InStock)
            ->select('id', 'title')
            ->get()
            ->filter(function ($book) use ($bigrams, $needed) {
                $tl  = mb_strtolower($book->title);
                $cnt = collect($bigrams)->filter(fn($bg) => mb_strpos($tl, $bg) !== false)->count();
                return $cnt >= $needed;
            })
            ->pluck('id');
    }

    /**
     * Build overlapping 2-character bigrams from a string.
     */
    private function buildBigrams(string $str): array
    {
        $bigrams = [];
        for ($i = 0; $i < mb_strlen($str) - 1; $i++) {
            $bigrams[] = mb_substr($str, $i, 2);
        }
        return $bigrams;
    }

    /**
     * Get related books in the same category, ordered by sold count.
     */
    public function getRelatedBooks(int $excludeId, int $categoryId, int $take = 5): Collection
    {
        return Book::where('status', BookStatus::InStock)
            ->where('category_id', $categoryId)
            ->where('id', '!=', $excludeId)
            ->orderByDesc('sold_count')
            ->take($take)
            ->get();
    }

    /**
     * Get fallback books in the same category, in random order.
     */
    public function getFallbackBooks(int $excludeId, int $categoryId, int $take = 6): Collection
    {
        return Book::where('status', BookStatus::InStock)
            ->where('category_id', $categoryId)
            ->where('id', '!=', $excludeId)
            ->inRandomOrder()
            ->take($take)
            ->get();
    }
}
