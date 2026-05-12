<?php

namespace App\Enums;

enum SupportTicketPriority: string
{
    case Low    = 'low';
    case Medium = 'medium';
    case High   = 'high';
    case Urgent = 'urgent';

    public function label(): string
    {
        return match ($this) {
            self::Low    => 'Thấp',
            self::Medium => 'Trung bình',
            self::High   => 'Cao',
            self::Urgent => 'Khẩn cấp',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Low    => 'bg-gray-100 text-gray-600',
            self::Medium => 'bg-blue-100 text-blue-700',
            self::High   => 'bg-orange-100 text-orange-700',
            self::Urgent => 'bg-red-100 text-red-700',
        };
    }
}
