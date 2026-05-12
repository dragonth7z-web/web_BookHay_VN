<?php

namespace App\Contracts\Repositories;

use App\Models\Author;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AuthorRepositoryInterface
{
    public function paginatedWithBookCount(int $perPage = 10): LengthAwarePaginator;

    public function getStats(): array;

    public function create(array $data): Author;

    public function update(Author $author, array $data): Author;

    public function delete(Author $author): void;
}
