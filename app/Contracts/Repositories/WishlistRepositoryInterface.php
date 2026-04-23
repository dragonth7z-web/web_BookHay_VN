<?php

namespace App\Contracts\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;

interface WishlistRepositoryInterface
{
    public function getPaginatedForUser(int $userId, int $perPage = 12): LengthAwarePaginator;

    public function existsForUser(int $userId, int $bookId): bool;

    public function addForUser(int $userId, int $bookId): void;

    public function removeForUser(int $userId, int $bookId): void;
}
