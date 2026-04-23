<?php

namespace App\Contracts\Repositories;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface NotificationRepositoryInterface
{
    public function getPaginatedForUser(int $userId, ?string $type, int $perPage = 20): LengthAwarePaginator;

    public function countUnreadForUser(int $userId): int;

    public function markAsRead(int $notificationId, int $userId): void;

    public function markAllAsRead(int $userId): void;
}
