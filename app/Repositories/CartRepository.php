<?php

namespace App\Repositories;

use App\Exceptions\CartOperationException;
use App\Models\Cart;
use App\Models\CartItem;
use App\Contracts\Repositories\CartRepositoryInterface;
use Illuminate\Support\Facades\DB;

class CartRepository implements CartRepositoryInterface
{
    public function getOrCreateForUser(int $userId): Cart
    {
        return Cart::firstOrCreate(['user_id' => $userId]);
    }

    public function findItemByBookId(Cart $cart, int $bookId): ?CartItem
    {
        return CartItem::where('cart_id', $cart->id)
            ->where('book_id', $bookId)
            ->first();
    }

    public function findItemById(int $itemId): ?CartItem
    {
        return CartItem::find($itemId);
    }

    public function updateItemQuantity(CartItem $item, int $quantity): CartItem
    {
        try {
            return DB::transaction(function () use ($item, $quantity) {
                $item->quantity = $quantity;
                $item->save();
                return $item;
            });
        } catch (\Throwable $e) {
            throw new CartOperationException('Failed to update cart item.', $e);
        }
    }

    public function addItem(Cart $cart, int $bookId, int $quantity): CartItem
    {
        try {
            return DB::transaction(function () use ($cart, $bookId, $quantity) {
                // Get book to ensure it exists and get its current price
                $book = \App\Models\Book::findOrFail($bookId);
                
                $item = $this->findItemByBookId($cart, $bookId);

                if ($item) {
                    $item->quantity += $quantity;
                    $item->price_snapshot = $book->sale_price; // Update snapshot to current price
                    $item->save();
                    return $item;
                }

                return CartItem::create([
                    'cart_id'  => $cart->id,
                    'book_id'  => $bookId,
                    'quantity' => $quantity,
                    'price_snapshot' => $book->sale_price,
                    'added_at' => now(),
                ]);
            });
        } catch (\Throwable $e) {
            throw new CartOperationException('Failed to add item to cart.', $e);
        }
    }

    public function removeItem(CartItem $item): void
    {
        try {
            DB::transaction(function () use ($item) {
                $item->delete();
            });
        } catch (\Throwable $e) {
            throw new CartOperationException('Failed to remove item from cart.', $e);
        }
    }

    public function clearCart(Cart $cart): void
    {
        try {
            DB::transaction(function () use ($cart) {
                CartItem::where('cart_id', $cart->id)->delete();
            });
        } catch (\Throwable $e) {
            throw new CartOperationException('Failed to clear cart.', $e);
        }
    }
}
