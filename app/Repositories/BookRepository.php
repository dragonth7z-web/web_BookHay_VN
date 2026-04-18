<?php

namespace App\Repositories;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
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
}
