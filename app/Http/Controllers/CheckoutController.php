<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\ShippingAddress;
use App\Services\CheckoutService;

class CheckoutController extends Controller
{
    public function __construct(private CheckoutService $service)
    {
    }

    public function index()
    {
        $userId = session('user_id');
        $items = $this->service->getCartItems($userId);

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        $subtotal = $items->sum(fn($i) => $i->quantity * $i->price_snapshot);
        $discountAmount = session('discount_amount', 0);
        $shippingAddresses = ShippingAddress::where('user_id', $userId)->get();

        return view('checkout.index', compact('items', 'shippingAddresses', 'subtotal', 'discountAmount'));
    }

    public function store(CheckoutRequest $request)
    {
        $userId = session('user_id');
        $items = $this->service->getCartItems($userId);

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        try {
            $order = $this->service->placeOrder(
                array_merge($request->validated(), [
                    'discount_amount' => session('discount_amount', 0),
                    'coupon_id' => session('coupon_id'),
                ]),
                $items,
                $userId
            );

            session()->forget(['coupon_id', 'coupon_info', 'discount_amount']);

            return redirect()->route('account.orders.show', $order->id)
                ->with('success', 'Đặt hàng thành công! Mã đơn hàng: #' . $order->id);
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi đặt hàng. Vui lòng thử lại.');
        }
    }

    /**
     * Trang thanh toán thành công.
     */
    public function success()
    {
        return view('checkout.success');
    }

    /**
     * Trang thanh toán thất bại.
     */
    public function failed()
    {
        return view('checkout.failed');
    }
}
