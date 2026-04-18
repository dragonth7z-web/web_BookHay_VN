<?php

namespace App\Contracts\Repositories;

use App\Models\Cart;
use App\Models\CartItem;

interface CartRepositoryInterface
{
    public function getOrCreateForUser(int $userId): Cart;
    public function findItemByBookId(Cart $cart, int $bookId): ?CartItem;
    public function findItemById(int $itemId): ?CartItem;
    public function updateItemQuantity(CartItem $item, int $quantity): CartItem;
    public function addItem(Cart $cart, int $bookId, int $quantity): CartItem;
    public function removeItem(CartItem $item): void;
    public function clearCart(Cart $cart): void;
}
