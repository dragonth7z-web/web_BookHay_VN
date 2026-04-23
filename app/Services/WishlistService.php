<?php

namespace App\Services;

use App\Contracts\Repositories\WishlistRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class WishlistService
{
    public function __construct(
        private WishlistRepositoryInterface $wishlistRepository
    ) {}

    public function getWishlistForUser(int $userId): LengthAwarePaginator
    {
        return $this->wishlistRepository->getPaginatedForUser($userId);
    }

    public function toggleWishlist(int $userId, int $bookId): array
    {
        $exists = $this->wishlistRepository->existsForUser($userId, $bookId);

        if ($exists) {
            $this->wishlistRepository->removeForUser($userId, $bookId);
            return ['wishlisted' => false, 'message' => 'Đã xóa khỏi danh sách yêu thích.'];
        }

        $this->wishlistRepository->addForUser($userId, $bookId);
        return ['wishlisted' => true, 'message' => 'Đã thêm vào danh sách yêu thích!'];
    }
}
