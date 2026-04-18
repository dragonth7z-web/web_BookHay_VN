<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::with(['user', 'items'])->paginate(20);
        return view('admin.carts.index', compact('carts'));
    }

    public function show(Cart $cart)
    {
        $cart->load(['user', 'items.book']);
        return view('admin.carts.show', compact('cart'));
    }

    public function destroy(Cart $cart)
    {
        $cart->delete();
        return redirect()->route('admin.carts.index')->with('success', 'Xóa thành công.');
    }
}
