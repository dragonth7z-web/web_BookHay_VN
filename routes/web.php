<?php

use Illuminate\Support\Facades\Route;

// ─── Admin Controllers (mới - tiếng Anh) ─────────────────────────────────────
use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\PublisherController as AdminPublisherController;
use App\Http\Controllers\Admin\AuthorController as AdminAuthorController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PurchaseOrderController as AdminPurchaseOrderController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Admin\CartController as AdminCartController;
use App\Http\Controllers\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\LoginHistoryController as AdminLoginHistoryController;
use App\Http\Controllers\Admin\SearchHistoryController as AdminSearchHistoryController;
use App\Http\Controllers\Admin\ShippingAddressController as AdminShippingAddressController;
use App\Http\Controllers\Admin\PaymentTransactionController as AdminPaymentTransactionController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\CollectionController as AdminCollectionController;

// ─── Admin Controllers (giữ nguyên - chưa migrate) ───────────────────────────
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\ComboController;
use App\Http\Controllers\Admin\WeeklyRankingController;
use App\Http\Controllers\Admin\FlashSaleController;
use App\Http\Controllers\Admin\FeaturedWorksController;
use App\Http\Controllers\Admin\SystemLogController;

// ─── Frontend Controllers ─────────────────────────────────────────────────────
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\PageController;

// ============================================================================
// TRANG CHỦ
// ============================================================================
Route::get('/', [HomeController::class , 'index'])->name('home');
Route::get('/api/weekly-ranking', [HomeController::class, 'getWeeklyRankingApi'])->name('api.weekly-ranking');
Route::get('/api/shopping-trend', [HomeController::class, 'getShoppingTrendApi'])->name('api.shopping-trend');

// ============================================================================
// XÁC THỰC (Authentication)
// ============================================================================
Route::controller(AuthController::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', 'showLogin')->name('login');
        Route::post('login', 'login')->name('login.post');
        Route::get('register', 'showRegister')->name('register');
        Route::post('register', 'register')->name('register.post');
        Route::get('forgot-password', 'showForgotPassword')->name('password.request');
        Route::post('forgot-password', 'sendResetLink')->name('password.email');
    });
    
    Route::post('logout', 'logout')->name('logout');
});

// ============================================================================
// SÁCH – Danh sách & Chi tiết
// ============================================================================
Route::controller(BookController::class)->prefix('bookstore')->name('books.')->group(function () {
    Route::get('search', 'index')->name('search');
    Route::get('{book}', 'show')->name('show');
});

// ============================================================================
// GIỎ HÀNG
// ============================================================================
Route::group(['prefix' => 'cart', 'as' => 'cart.'], function () {
    Route::controller(CartController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('add/{book:id}', 'add')->name('add');
        Route::patch('update/{id}', 'update')->name('update');
        Route::delete('remove/{id}', 'remove')->name('remove');
        Route::post('apply-voucher', 'applyVoucher')->name('apply_voucher');
        Route::delete('remove-voucher', 'removeVoucher')->name('remove_voucher');
        Route::post('clear', 'clear')->name('clear');
    });
});

// ============================================================================
// THANH TOÁN
// ============================================================================
Route::group(['prefix' => 'checkout', 'as' => 'checkout.', 'middleware' => 'auth.custom'], function () {
    Route::controller(CheckoutController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
    });
});

// ============================================================================
// TÀI KHOẢN KHÁCH HÀNG
// ============================================================================
Route::group(['prefix' => 'account', 'as' => 'account.', 'middleware' => 'auth.custom'], function () {
    Route::controller(AccountController::class)->group(function () {
        Route::get('/', 'dashboard')->name('dashboard');
        Route::get('profile', 'profile')->name('profile');
        Route::post('profile', 'updateProfile')->name('profile.update');
        Route::get('orders', 'orders')->name('orders');
        Route::get('orders/{id}', 'orderShow')->name('orders.show');
        Route::get('bookshelf', 'bookshelf')->name('bookshelf');
        Route::get('wishlist', 'wishlist')->name('wishlist');
        Route::post('wishlist/{book}', 'toggleWishlist')->name('wishlist.toggle');
        Route::get('reviews', 'reviews')->name('reviews');
    });
});

// ============================================================================
// CÁC TRANG TĨNH / NỘI DUNG PHỤ
// ============================================================================
Route::group(['prefix' => 'pages', 'as' => 'pages.'], function () {
    Route::controller(PageController::class)->group(function () {
        Route::get('about', 'about')->name('about');
        Route::get('contact', 'contact')->name('contact');
        Route::post('contact', 'contactSubmit')->name('contact.submit');
        Route::get('faq', 'faq')->name('faq');
    });
    Route::get('shipping', [PageController::class, 'shipping'])->name('shipping');
    Route::get('return', [PageController::class, 'return'])->name('return');
    Route::get('privacy', [PageController::class, 'privacy'])->name('privacy');
    Route::get('terms', [PageController::class, 'terms'])->name('terms');
});

Route::get('order-tracking', [PageController::class, 'orderTracking'])->name('orders.tracking');
Route::get('stores', [PageController::class, 'stores'])->name('stores.index');

// ============================================================================
// COMBO, FLASH SALE, COLLECTIONS (Frontend)
// ============================================================================
Route::get('combo', [ComboController::class, 'index'])->name('combo.index');
Route::get('combo/{combo}', [ComboController::class, 'show'])->name('combo.show');
Route::get('flash-sale', [FlashSaleController::class, 'index'])->name('flash-sale.index');
Route::get('collections', [AdminCollectionController::class, 'index'])->name('collections.index');
Route::get('collections/{collection}', [AdminCollectionController::class, 'show'])->name('collections.show');

// ============================================================================
// CHECKOUT SUCCESS/FAILED
// ============================================================================
Route::get('checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('checkout/failed', [CheckoutController::class, 'failed'])->name('checkout.failed');

// ============================================================================
// ADMIN ROUTES
// ============================================================================
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'admin.custom'], function () {

    // Dashboard
    Route::get('/', [DashboardController::class , 'index'])->name('dashboard');

    // Nhóm 1: Người dùng & Phân quyền
    Route::resource('roles', AdminRoleController::class);
    Route::resource('users', AdminUserController::class);
    Route::resource('shipping-addresses', AdminShippingAddressController::class)
        ->only(['index', 'show', 'destroy']);
    Route::resource('login-histories', AdminLoginHistoryController::class)
        ->only(['index', 'show', 'destroy']);

    // Nhóm 2: Danh mục, Tác giả, NXB, Sách
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('publishers', AdminPublisherController::class);
    Route::resource('authors', AdminAuthorController::class);
    Route::resource('books', AdminBookController::class);

    // Nhóm 3: Khuyến mãi & Giỏ hàng
    Route::resource('coupons', AdminCouponController::class);
    Route::resource('carts', AdminCartController::class)->only(['index', 'show', 'destroy']);

    // Nhóm 4: Đơn hàng & Thanh toán
    Route::resource('orders', AdminOrderController::class)->except(['create', 'store']);
    Route::resource('payment-transactions', AdminPaymentTransactionController::class)
        ->only(['index', 'show', 'update', 'destroy']);

    // Nhóm 5: Tương tác
    Route::resource('reviews', AdminReviewController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::resource('comments', AdminCommentController::class)->only(['index', 'show', 'update', 'destroy']);

    // Nhóm 6: Hiển thị & Hệ thống
    Route::resource('notifications', AdminNotificationController::class)
        ->only(['index', 'create', 'store', 'show', 'destroy']);
    Route::resource('banner', BannerController::class);
    Route::resource('settings', AdminSettingController::class)->except(['show']);
    Route::resource('faq', FaqController::class)->except(['show']);
    Route::resource('search-histories', AdminSearchHistoryController::class)
        ->only(['index', 'show', 'destroy']);
    Route::resource('purchase-orders', AdminPurchaseOrderController::class)
        ->only(['index', 'create', 'store', 'show', 'destroy']);
    Route::resource('combo', ComboController::class);
    Route::resource('collections', AdminCollectionController::class);

    // Nhật ký hệ thống
    Route::controller(SystemLogController::class)->prefix('system-logs')->name('system-logs.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('clear-old', 'clearOld')->name('clear-old');
        Route::get('{systemLog}', 'show')->name('show');
        Route::delete('{systemLog}', 'destroy')->name('destroy');
    });

    // Marketing nội dung
    Route::resource('weekly-rankings', WeeklyRankingController::class)
        ->parameters(['weekly-rankings' => 'weeklyRanking']);
    Route::resource('flash-sales', FlashSaleController::class)
        ->parameters(['flash-sales' => 'flashSale']);

    // Tác Phẩm Tiêu Điểm
    Route::controller(FeaturedWorksController::class)->prefix('featured-works')->name('featured-works.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'update')->name('update');
    });
});
