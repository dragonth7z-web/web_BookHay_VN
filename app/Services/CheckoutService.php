<?php

namespace App\Services;

use App\Enums\PaymentMethod;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\BookRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CheckoutService
{
    const SHIPPING_FEE = 30000;
    const FREE_SHIPPING_MIN = 200000;

    public function __construct(private BookRepository $bookRepo)
    {
    }

    public function getCartItems(int $userId): Collection
    {
        $cart = Cart::where('user_id', $userId)->first();
        if (!$cart)
            return collect();

        return CartItem::with('book')->where('cart_id', $cart->id)->get();
    }

    public function calculateShippingFee(float $subtotal): int
    {
        return $subtotal >= self::FREE_SHIPPING_MIN ? 0 : self::SHIPPING_FEE;
    }

    public function placeOrder(array $data, Collection $cartItems, int $userId): Order
    {
        $subtotal = $cartItems->sum(fn($i) => $i->quantity * $i->price_snapshot);
        $discount = $data['discount_amount'] ?? 0;
        $shippingFee = $this->calculateShippingFee($subtotal);
        $method = PaymentMethod::from($data['payment_method']);

        return DB::transaction(function () use ($data, $cartItems, $userId, $subtotal, $discount, $shippingFee, $method) {
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'user_id' => $userId,
                'coupon_id' => $data['coupon_id'] ?? null,
                'shipping_address' => $data['shipping_address'],
                'recipient_name' => $data['recipient_name'],
                'recipient_phone' => $data['recipient_phone'],
                'notes' => $data['notes'] ?? null,
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'discount_amount' => $discount,
                'total' => $subtotal - $discount + $shippingFee,
                'payment_method' => $method,
                'payment_status' => $method->defaultPaymentStatus(),
                'status' => \App\Enums\OrderStatus::Pending,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'book_id' => $item->book_id,
                    'book_title_snapshot' => $item->book->title,
                    'book_image_snapshot' => $item->book->cover_image,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->price_snapshot,
                    'subtotal' => $item->quantity * $item->price_snapshot,
                ]);

                $this->bookRepo->decrementStock($item->book_id, $item->quantity);
            }

            $cart = Cart::where('user_id', $userId)->first();
            CartItem::where('cart_id', $cart->id)->delete();

            return $order;
        });
    }
}
