<?php

namespace App\Contracts\Repositories;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface BookRepositoryInterface
{
    public function available(): Collection;
    public function findOrFail(int $id): Book;
    public function findBySlug(string $slug): ?Book;
    public function search(string $keyword, int $perPage = 20): LengthAwarePaginator;
    public function getFeatured(int $take = 8): Collection;
    public function getRecommended(int $take = 10): Collection;
    public function decrementStock(int $bookId, int $quantity): void;
    public function paginatedWithRelations(int $perPage = 20): \Illuminate\Contracts\Pagination\LengthAwarePaginator;
    public function getAllCategories(): Collection;
    public function getAllPublishers(): Collection;
    public function getAllAuthors(): Collection;
    public function getForCardDisplay(int $take = 20): Collection;

    /**
     * Fuzzy search books by query string (pass 1: SQL LIKE).
     * Returns books matching exact phrase or condensed (no-space) match.
     */
    public function fuzzySearchPass1(string $query, int $limit = 6): Collection;

    /**
     * Bigram scoring search (pass 2: PHP-side).
     * Returns books matching at least 80% of bigrams extracted from query.
     */
    public function fuzzySearchPass2(string $query, int $limit = 6): Collection;

    /**
     * Get IDs of books matching fuzzy search (for pagination queries).
     */
    public function getFuzzySearchIds(string $query): Collection;

    /**
     * Get related books in the same category.
     */
    public function getRelatedBooks(int $excludeId, int $categoryId, int $take = 5): Collection;

    /**
     * Get fallback books in the same category, random order.
     */
    public function getFallbackBooks(int $excludeId, int $categoryId, int $take = 6): Collection;
}
