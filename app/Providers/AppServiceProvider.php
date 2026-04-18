<?php

namespace App\Providers;

use App\Contracts\Repositories\HomeRepositoryInterface;
use App\Contracts\Repositories\BookRepositoryInterface;
use App\Contracts\Repositories\CartRepositoryInterface;
use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Contracts\Repositories\PublisherRepositoryInterface;
use App\Contracts\Repositories\WeeklyRankingRepositoryInterface;
use App\Models\Book;
use App\Models\Order;
use App\Models\PurchaseOrder;
use App\Observers\SystemLogObserver;
use App\Repositories\HomeRepository;
use App\Repositories\BookRepository;
use App\Repositories\CartRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\OrderRepository;
use App\Repositories\PublisherRepository;
use App\Repositories\WeeklyRankingRepository;
use App\Services\BookService;
use App\Services\CheckoutService;
use App\Services\CouponService;
use App\Services\FlashSaleService;
use App\Services\OrderService;
use App\Services\PurchaseOrderService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind interfaces to concrete implementations
        $this->app->singleton(BookRepositoryInterface::class, BookRepository::class);
        $this->app->singleton(CartRepositoryInterface::class, CartRepository::class);
        $this->app->singleton(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->singleton(PublisherRepositoryInterface::class, PublisherRepository::class);
        $this->app->singleton(WeeklyRankingRepositoryInterface::class, WeeklyRankingRepository::class);
        $this->app->singleton(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->singleton(HomeRepositoryInterface::class, HomeRepository::class);

        // Keep concrete singletons for classes without interfaces yet
        $this->app->singleton(CheckoutService::class);
        $this->app->singleton(FlashSaleService::class);
        $this->app->singleton(OrderService::class);
        $this->app->singleton(BookService::class);
        $this->app->singleton(CouponService::class);
        $this->app->singleton(PurchaseOrderService::class);
    }

    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer(['components.header', 'layouts.app'], function ($view) {
            // 1. Lấy danh mục (Cập nhật tên cột Tiếng Anh: parent_id, is_visible, sort_order)
            $megaCategories = \App\Models\Category::whereNull('parent_id')
                ->where('is_visible', true)
                ->orderBy('sort_order')
                ->with([
                    'children' => function ($query) {
                        $query->where('is_visible', true)->orderBy('sort_order');
                    }
                ])
                ->get();

            // 2. BỔ SUNG: Lấy Flash Sales (Sử dụng đúng tên cột: start_date, end_date)
            $currentFlashSales = \App\Models\FlashSale::where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->get() ?? collect(); // Đảm bảo luôn trả về collection rỗng thay vì null

            $view->with([
                'megaCategories' => $megaCategories,
                'flashSales' => $currentFlashSales, // Biến này sẽ cứu lỗi dòng 48
            ]);
        });

        // Observers
        \App\Models\Book::observe(\App\Observers\SystemLogObserver::class);
        \App\Models\Order::observe(\App\Observers\SystemLogObserver::class);
        \App\Models\PurchaseOrder::observe(\App\Observers\SystemLogObserver::class);

        // Logging guard: catch undefined property / null access errors in development
        if (config('app.debug')) {
            set_error_handler(function (int $errno, string $errstr, string $errfile, int $errline): bool {
                // Only intercept notices/warnings about undefined properties or null access
                if ($errno === E_NOTICE || $errno === E_WARNING) {
                    if (str_contains($errstr, 'Undefined property') || str_contains($errstr, 'Attempt to read property')) {
                        Log::warning('[Undefined Property Guard] ' . $errstr, [
                            'file' => $errfile,
                            'line' => $errline,
                        ]);
                    }
                }
                // Return false to let PHP's default handler also run
                return false;
            }, E_NOTICE | E_WARNING);
        }
    }
}
