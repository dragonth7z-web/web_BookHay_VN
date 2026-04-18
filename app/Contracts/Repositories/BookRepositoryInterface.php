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
}
