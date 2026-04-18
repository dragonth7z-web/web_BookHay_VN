<?php

namespace App\Http\Controllers;

use App\Exceptions\CartNotFoundException;
use App\Exceptions\CartOperationException;
use App\Exceptions\InsufficientStockException;
use App\Models\Book;
use App\Repositories\CartRepository;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        private CartRepository $cartRepository
    ) {}

    public function index(Request $request)
    {
        $userId = session('user_id');

        if (!$userId) {
            return view('cart.index', ['cart' => null, 'items' => collect()]);
        }

        $cart = $this->cartRepository->getOrCreateForUser($userId);
        $items = $cart->items()->with('book')->get();

        return view('cart.index', compact('cart', 'items'));
    }

    public function add(Request $request, Book $book)
    {
        $quantity = (int) $request->input('quantity', 1);

        try {
            $userId = session('user_id');

            if (!$userId) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['error' => 'Vui lòng đăng nhập.'], 401);
                }
                return redirect()->route('login')->with('error', 'Vui lòng đăng nhập.');
            }

            if ($book->stock < $quantity) {
                throw new InsufficientStockException('Số lượng tồn kho không đủ.');
            }

            $cart = $this->cartRepository->getOrCreateForUser($userId);
            $existingItem = $this->cartRepository->findItemByBookId($cart, $book->id);

            if ($existingItem && ($existingItem->quantity + $quantity) > $book->stock) {
                throw new InsufficientStockException('So luong ton kho khong du.');
            }

            $this->cartRepository->addItem($cart, $book->id, $quantity);

            if ($request->expectsJson() || $request->ajax()) {
                $total = $cart->items()->get()->sum(function($item) {
                    return $item->quantity * $item->price_snapshot;
                });
                return response()->json([
                    'message' => 'Da them san pham vao gio hang.',
                    'cart_total' => $total
                ], 200);
            }

            return back()->with('success', 'Da them san pham vao gio hang.');
        } catch (InsufficientStockException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 422);
            }
            return back()->withErrors(['quantity' => $e->getMessage()]);
        } catch (CartNotFoundException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 404);
            }
            return back()->with('error', $e->getMessage());
        } catch (CartOperationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => 'Co loi xay ra, vui long thu lai'], 500);
            }
            return back()->with('error', 'Co loi xay ra, vui long thu lai');
        }
    }

    public function update(Request $request, int $id)
    {
        $quantity = (int) $request->input('quantity', 1);

        try {
            $item = $this->cartRepository->findItemById($id);

            if (!$item) {
                throw new CartNotFoundException('Khong tim thay san pham trong gio hang.');
            }

            $userId = session('user_id');
            if (!$userId || $item->cart->user_id !== $userId) {
                throw new CartNotFoundException('Không tìm thấy giỏ hàng.');
            }

            if ($quantity <= 0) {
                $this->cartRepository->removeItem($item);

                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['message' => 'Da xoa san pham khoi gio hang.'], 200);
                }
                return back()->with('success', 'Da xoa san pham khoi gio hang.');
            }

            if ($item->book && $item->book->stock < $quantity) {
                throw new InsufficientStockException('So luong ton kho khong du.');
            }

            $this->cartRepository->updateItemQuantity($item, $quantity);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Da cap nhat gio hang.'], 200);
            }
            return back()->with('success', 'Da cap nhat gio hang.');
        } catch (InsufficientStockException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 422);
            }
            return back()->withErrors(['quantity' => $e->getMessage()]);
        } catch (CartNotFoundException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 404);
            }
            return back()->with('error', $e->getMessage());
        } catch (CartOperationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => 'Co loi xay ra, vui long thu lai'], 500);
            }
            return back()->with('error', 'Co loi xay ra, vui long thu lai');
        }
    }

    public function remove(Request $request, int $id)
    {
        try {
            $item = $this->cartRepository->findItemById($id);

            if (!$item) {
                throw new CartNotFoundException('Khong tim thay san pham trong gio hang.');
            }

            $userId = session('user_id');
            if (!$userId || $item->cart->user_id !== $userId) {
                throw new CartNotFoundException('Không tìm thấy giỏ hàng.');
            }

            $this->cartRepository->removeItem($item);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Da xoa san pham khoi gio hang.'], 200);
            }
            return back()->with('success', 'Da xoa san pham khoi gio hang.');
        } catch (CartNotFoundException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 404);
            }
            return back()->with('error', $e->getMessage());
        } catch (CartOperationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => 'Co loi xay ra, vui long thu lai'], 500);
            }
            return back()->with('error', 'Co loi xay ra, vui long thu lai');
        }
    }

    public function clear(Request $request)
    {
        try {
            $userId = session('user_id');

            if (!$userId) {
                throw new CartNotFoundException('Không tìm thấy giỏ hàng.');
            }

            $cart = $this->cartRepository->getOrCreateForUser($userId);
            $this->cartRepository->clearCart($cart);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Da xoa toan bo gio hang.'], 200);
            }
            return back()->with('success', 'Da xoa toan bo gio hang.');
        } catch (CartNotFoundException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 404);
            }
            return back()->with('error', $e->getMessage());
        } catch (CartOperationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => 'Co loi xay ra, vui long thu lai'], 500);
            }
            return back()->with('error', 'Co loi xay ra, vui long thu lai');
        }
    }

    public function applyVoucher(Request $request)
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['message' => 'Chuc nang dang duoc phat trien.'], 200);
        }
        return back()->with('info', 'Chuc nang dang duoc phat trien.');
    }

    public function removeVoucher(Request $request)
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['message' => 'Chuc nang dang duoc phat trien.'], 200);
        }
        return back()->with('info', 'Chuc nang dang duoc phat trien.');
    }
}
