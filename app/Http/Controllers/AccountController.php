<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Review;
use App\Models\Order;
use App\Models\ShippingAddress;
use App\Enums\OrderStatus;
use App\Services\NotificationService;
use App\Services\WishlistService;
use App\Services\DashboardService;
use App\Services\ShippingAddressService;
use App\Repositories\CouponRepository;

class AccountController extends Controller
{
    public function __construct(
        private NotificationService $notificationService,
        private WishlistService $wishlistService,
        private DashboardService $dashboardService,
        private ShippingAddressService $addressService,
        private CouponRepository $couponRepository
    ) {}

    private function getUser(): ?User
    {
        return User::find(session('user_id'));
    }

    public function dashboard()
    {
        $user = $this->getUser();
        if (!$user) {
            return redirect()->route('login');
        }

        $recentOrders    = $this->dashboardService->getRecentOrders($user->id);
        $orderStats      = $this->dashboardService->getOrderStats($user->id);
        $currentlyReading = $this->dashboardService->getCurrentlyReading($user->id);

        return view('account.dashboard', compact('user', 'recentOrders', 'orderStats', 'currentlyReading'));
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
            'name'          => 'required|string|max:100',
            'phone'         => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender'        => 'nullable|in:male,female,other',
        ]);

        $user->update($request->only(['name', 'phone', 'date_of_birth', 'gender']));

        if ($request->filled('password')) {
            $request->validate([
                'current_password' => 'required',
                'password'         => 'required|min:6|confirmed',
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
        $books = $this->wishlistService->getWishlistForUser($user->id);
        return view('account.wishlist', compact('user', 'books'));
    }

    public function toggleWishlist(Request $request, $bookId)
    {
        $user = $this->getUser();
        $result = $this->wishlistService->toggleWishlist($user->id, (int) $bookId);

        if ($request->expectsJson()) {
            return response()->json(['success' => true] + $result);
        }

        return back()->with('success', $result['message']);
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

    public function coupons()
    {
        $user    = $this->getUser();
        $coupons = $this->couponRepository->getActiveCoupons();
        return view('account.coupons', compact('user', 'coupons'));
    }

    public function addresses()
    {
        $user      = $this->getUser();
        $addresses = $this->addressService->getAddressesForUser($user->id);
        return view('account.addresses', compact('user', 'addresses'));
    }

    public function storeAddress(Request $request)
    {
        $user = $this->getUser();

        $request->validate([
            'recipient_name'  => 'required|string|max:100',
            'recipient_phone' => 'required|string|max:20',
            'province'        => 'required|string|max:100',
            'district'        => 'required|string|max:100',
            'ward'            => 'nullable|string|max:100',
            'address_detail'  => 'required|string|max:255',
            'is_default'      => 'nullable|boolean',
        ]);

        $this->addressService->createAddress($user->id, $request->only([
            'recipient_name', 'recipient_phone', 'province',
            'district', 'ward', 'address_detail', 'is_default',
        ]));

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Đã thêm địa chỉ mới.']);
        }

        return back()->with('success', 'Đã thêm địa chỉ mới.');
    }

    public function updateAddress(Request $request, int $id)
    {
        $user = $this->getUser();

        $request->validate([
            'recipient_name'  => 'required|string|max:100',
            'recipient_phone' => 'required|string|max:20',
            'province'        => 'required|string|max:100',
            'district'        => 'required|string|max:100',
            'ward'            => 'nullable|string|max:100',
            'address_detail'  => 'required|string|max:255',
            'is_default'      => 'nullable|boolean',
        ]);

        $this->addressService->updateAddress($id, $user->id, $request->only([
            'recipient_name', 'recipient_phone', 'province',
            'district', 'ward', 'address_detail', 'is_default',
        ]));

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Đã cập nhật địa chỉ.']);
        }

        return back()->with('success', 'Đã cập nhật địa chỉ.');
    }

    public function destroyAddress(int $id)
    {
        $user = $this->getUser();
        $this->addressService->deleteAddress($id, $user->id);

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Đã xóa địa chỉ.']);
        }

        return back()->with('success', 'Đã xóa địa chỉ.');
    }

    public function setDefaultAddress(int $id)
    {
        $user = $this->getUser();
        $this->addressService->setDefaultAddress($id, $user->id);

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Đã đặt làm địa chỉ mặc định.']);
        }

        return back()->with('success', 'Đã đặt làm địa chỉ mặc định.');
    }

    public function notifications(Request $request)
    {
        $user = $this->getUser();
        $notifications = $this->notificationService->getNotificationsForUser($user->id, $request->query('type'));
        $unreadCount   = $this->notificationService->getUnreadCountForUser($user->id);

        return view('account.notifications', compact('user', 'notifications', 'unreadCount'));
    }

    public function markNotificationRead(Request $request, $id)
    {
        $user = $this->getUser();
        $this->notificationService->markAsRead((int) $id, $user->id);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back();
    }

    public function markAllNotificationsRead(Request $request)
    {
        $user = $this->getUser();
        $this->notificationService->markAllAsRead($user->id);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Đã đánh dấu tất cả thông báo là đã đọc.');
    }
}
