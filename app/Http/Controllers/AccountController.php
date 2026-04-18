<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Order;
use App\Models\Review;
use App\Models\ShippingAddress;
use App\Models\Book;
use App\Enums\OrderStatus;

class AccountController extends Controller
{
    private function getUser(): ?User
    {
        return User::find(session('user_id'));
    }

    public function dashboard()
    {
        $user = $this->getUser();
        if (!$user)
            return redirect()->route('login');

        $recentOrders = Order::where('user_id', $user->id)
            ->with('items.book')
            ->latest()
            ->take(5)
            ->get();

        $orderStats = [
            'total' => Order::where('user_id', $user->id)->count(),
            'pending' => Order::where('user_id', $user->id)->where('status', OrderStatus::Pending)->count(),
            'shipping' => Order::where('user_id', $user->id)->where('status', OrderStatus::Shipping)->count(),
            'completed' => Order::where('user_id', $user->id)->where('status', OrderStatus::Completed)->count(),
        ];

        return view('account.dashboard', compact('user', 'recentOrders', 'orderStats'));
    }

    public function profile()
    {
        $user = $this->getUser();
        $shippingAddresses = ShippingAddress::where('user_id', $user->id)->get();
        return view('account.profile', compact('user', 'shippingAddresses'));
    }

    public function updateProfile(Request $request)
    {
        $user = $this->getUser();

        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
        ]);

        $user->update($request->only(['name', 'phone', 'date_of_birth', 'gender']));

        if ($request->filled('password')) {
            $request->validate([
                'current_password' => 'required',
                'password' => 'required|min:6|confirmed',
            ]);

            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
            }

            $user->update(['password' => Hash::make($request->password)]);
        }

        session(['user_name' => $user->name]);

        return back()->with('success', 'Cập nhật thông tin thành công!');
    }

    public function orders(Request $request)
    {
        $user = $this->getUser();
        $query = Order::where('user_id', $user->id)->with('items.book');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(10);
        return view('account.orders', compact('user', 'orders'));
    }

    public function orderShow($id)
    {
        $user = $this->getUser();
        $order = Order::where('id', $id)
            ->where('user_id', $user->id)
            ->with('items.book')
            ->firstOrFail();

        return view('account.orders.show', compact('user', 'order'));
    }

    public function wishlist()
    {
        $user = $this->getUser();
        $books = $user->readingLists()->with('book')->latest()->paginate(12);
        return view('account.wishlist', compact('user', 'books'));
    }

    public function toggleWishlist(Request $request, $bookId)
    {
        $user = $this->getUser();
        $exists = $user->readingLists()->where('book_id', $bookId)->exists();

        if ($exists) {
            $user->readingLists()->where('book_id', $bookId)->delete();
            $message = 'Đã xóa khỏi danh sách yêu thích.';
            $isWishlisted = false;
        } else {
            $user->readingLists()->create(['book_id' => $bookId, 'user_id' => $user->id]);
            $message = 'Đã thêm vào danh sách yêu thích!';
            $isWishlisted = true;
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'wishlisted' => $isWishlisted, 'message' => $message]);
        }

        return back()->with('success', $message);
    }

    public function reviews()
    {
        $user = $this->getUser();
        $reviews = Review::where('user_id', $user->id)
            ->with('book')
            ->latest()
            ->paginate(10);

        return view('account.reviews', compact('user', 'reviews'));
    }

    public function bookshelf()
    {
        $user = $this->getUser();
        $readingLists = $user->readingLists()->with('book')->latest()->paginate(12);
        return view('account.bookshelf', compact('user', 'readingLists'));
    }
}
