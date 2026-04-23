<?php

namespace App\Repositories;

use App\Contracts\Repositories\WishlistRepositoryInterface;
use App\Models\ReadingList;
use Illuminate\Pagination\LengthAwarePaginator;

class WishlistRepository implements WishlistRepositoryInterface
{
    public function getPaginatedForUser(int $userId, int $perPage = 12): LengthAwarePaginator
    {
        return ReadingList::where('user_id', $userId)
            ->with(['book.category', 'book.authors'])
            ->latest()
            ->paginate($perPage);
    }

    public function existsForUser(int $userId, int $bookId): bool
    {
        return ReadingList::where('user_id', $userId)
            ->where('book_id', $bookId)
            ->exists();
    }

    public function addForUser(int $userId, int $bookId): void
    {
        ReadingList::create([
            'user_id' => $userId,
            'book_id' => $bookId,
        ]);
    }

    public function removeForUser(int $userId, int $bookId): void
    {
        ReadingList::where('user_id', $userId)
            ->where('book_id', $bookId)
            ->delete();
    }
}
