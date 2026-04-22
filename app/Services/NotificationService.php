<?php

namespace App\Services;

use App\Contracts\Repositories\NotificationRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class NotificationService
{
    public function __construct(
        private NotificationRepositoryInterface $notificationRepository
    ) {}

    public function getNotificationsForUser(int $userId, ?string $type): LengthAwarePaginator
    {
        return $this->notificationRepository->getPaginatedForUser($userId, $type);
    }

    public function getUnreadCountForUser(int $userId): int
    {
        return $this->notificationRepository->countUnreadForUser($userId);
    }

    public function markAsRead(int $notificationId, int $userId): void
    {
        $this->notificationRepository->markAsRead($notificationId, $userId);
    }

    public function markAllAsRead(int $userId): void
    {
        $this->notificationRepository->markAllAsRead($userId);
    }
}
