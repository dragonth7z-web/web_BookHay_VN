<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending   = 'pending';
    case Confirmed = 'confirmed';
    case Shipping  = 'shipping';
    case Delivered = 'delivered';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case Returned  = 'returned';

    public function label(): string
    {
        return match ($this) {
            self::Pending   => 'Chờ xác nhận',
            self::Confirmed => 'Đã xác nhận',
            self::Shipping  => 'Đang giao hàng',
            self::Delivered => 'Đã giao hàng',
            self::Completed  => 'Hoàn thành',
            self::Cancelled => 'Đã hủy',
            self::Returned  => 'Trả hàng',
        };
    }
}
