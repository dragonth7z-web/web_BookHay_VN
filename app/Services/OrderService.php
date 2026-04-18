<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use Carbon\Carbon;

class OrderService
{
    public function updateStatus(Order $order, OrderStatus $newStatus, ?string $cancelReason = null): Order
    {
        $order->status = $newStatus;

        if ($newStatus === OrderStatus::Cancelled && $cancelReason) {
            $order->cancel_reason = $cancelReason;
        }

        $order->save();

        return $order;
    }

    public function getStats(Carbon $from, Carbon $to): array
    {
        $orders = Order::whereBetween('created_at', [$from, $to])->get();

        return [
            'total' => $orders->count(),
            'revenue' => $orders->sum('total'),
            'pending' => $orders->where('status', OrderStatus::Pending)->count(),
            'completed' => $orders->where('status', OrderStatus::Completed)->count(),
        ];
    }
}
