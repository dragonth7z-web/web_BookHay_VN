<?php

namespace App\Enums;

enum SupportTicketStatus: string
{
    case Open       = 'open';
    case InProgress = 'in_progress';
    case Resolved   = 'resolved';
    case Closed     = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::Open       => 'Mở',
            self::InProgress => 'Đang xử lý',
            self::Resolved   => 'Đã giải quyết',
            self::Closed     => 'Đã đóng',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Open       => 'bg-blue-100 text-blue-700 border-blue-200',
            self::InProgress => 'bg-orange-100 text-orange-700 border-orange-200',
            self::Resolved   => 'bg-green-100 text-green-700 border-green-200',
            self::Closed     => 'bg-gray-100 text-gray-600 border-gray-200',
        };
    }
}
