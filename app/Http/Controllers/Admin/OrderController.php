<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOrderRequest;
use App\Models\Order;
use App\Models\SystemLog;
use App\Repositories\OrderRepository;
use App\Services\OrderService;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $service,
        private OrderRepository $repo
    ) {}

    public function index()
    {
        $orders = $this->repo->paginatedWithFilters(request()->only(['status']), 20);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order = $this->repo->findWithFullDetails($order->id);
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        return view('admin.orders.edit', compact('order'));
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $statusEnum = OrderStatus::from($request->status);
        $this->service->updateStatus(
            $order,
            $statusEnum,
            $request->cancel_reason
        );
        SystemLog::ghi(
            type: 'data',
            action: 'update',
            description: 'Cập nhật trạng thái đơn hàng ' . $order->order_number . ' thành ' . $statusEnum->label(),
            level: 'info',
            objectType: 'Order',
            objectId: $order->id
        );
        return redirect()->route('admin.orders.show', $order)->with('success', 'Cập nhật đơn hàng thành công.');
    }

    public function destroy(Order $order)
    {
        $id = $order->id;
        $orderNumber = $order->order_number;
        $order->delete();
        SystemLog::ghi(
            type: 'data',
            action: 'delete',
            description: 'Xóa đơn hàng: ' . $orderNumber,
            level: 'warning',
            objectType: 'Order',
            objectId: $id
        );
        return redirect()->route('admin.orders.index')->with('success', 'Xóa thành công.');
    }
}
