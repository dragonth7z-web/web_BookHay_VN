<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Book;
use App\Models\User;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\SystemLog;
use App\Enums\OrderStatus;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private const CANCELLED = [OrderStatus::Cancelled->value, OrderStatus::Returned->value];

    public function index()
    {
        $today = now()->startOfDay();
        $startOfMonth = now()->startOfMonth();
        $startOfLastMonth = now()->subMonth()->startOfMonth();
        $endOfLastMonth = now()->subMonth()->endOfMonth();

        // 1. STAT CARDS
        $revenueToday = Order::where('created_at', '>=', $today)
            ->whereNotIn('status', self::CANCELLED)->sum('total');

        $revenueYesterday = Order::whereDate('created_at', now()->subDay())
            ->whereNotIn('status', self::CANCELLED)->sum('total');

        $revenuePctChange = $revenueYesterday > 0
            ? round(($revenueToday - $revenueYesterday) / $revenueYesterday * 100, 1) : 0;

        $newOrdersCount = Order::where('created_at', '>=', $today)->count();
        $newOrdersYesterday = Order::whereDate('created_at', now()->subDay())->count();
        $ordersDiff = $newOrdersCount - $newOrdersYesterday;

        $pendingOrders = Order::where('status', OrderStatus::Pending)->count();

        $revenueMonth = Order::where('created_at', '>=', $startOfMonth)
            ->whereNotIn('status', self::CANCELLED)->sum('total');

        $revenueLastMonth = Order::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->whereNotIn('status', self::CANCELLED)->sum('total');

        $revenueMonthPct = $revenueLastMonth > 0
            ? round(($revenueMonth - $revenueLastMonth) / $revenueLastMonth * 100, 0) : 0;

        // 2. MINI METRIC BAR
        $totalOrdersMonth = Order::where('created_at', '>=', $startOfMonth)->count();
        $cancelledMonth = Order::where('created_at', '>=', $startOfMonth)
            ->whereIn('status', self::CANCELLED)->count();
        $cancelRate = $totalOrdersMonth > 0 ? round($cancelledMonth / $totalOrdersMonth * 100, 1) : 0;

        $totalOrdersLastMonth = Order::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
        $cancelledLastMonth = Order::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->whereIn('status', self::CANCELLED)->count();
        $cancelRateLast = $totalOrdersLastMonth > 0
            ? round($cancelledLastMonth / $totalOrdersLastMonth * 100, 1) : 0;

        $profitData = Book::whereNotNull('cost_price')
            ->where('sale_price', '>', 0)
            ->selectRaw('ROUND(AVG((sale_price - cost_price) / sale_price * 100), 1) as margin')
            ->first();
        $profitMargin = $profitData->margin ?? 0;

        $completedOrdersMonth = Order::where('created_at', '>=', $startOfMonth)
            ->whereNotIn('status', self::CANCELLED)->count();
        $aov = $completedOrdersMonth > 0 ? round($revenueMonth / $completedOrdersMonth, 0) : 0;

        $completedOrdersLastMonth = Order::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->whereNotIn('status', self::CANCELLED)->count();
        $aovLast = $completedOrdersLastMonth > 0
            ? round($revenueLastMonth / $completedOrdersLastMonth, 0) : 0;
        $aovDiff = $aov - $aovLast;

        $clv = User::where('total_spent', '>', 0)->avg('total_spent') ?? 0;

        $uniqueLoginsMonth = DB::table('login_histories')
            ->where('created_at', '>=', $startOfMonth)
            ->distinct('user_id')->count('user_id');
        $conversionRate = $uniqueLoginsMonth > 0
            ? round($completedOrdersMonth / $uniqueLoginsMonth * 100, 1) : 0;

        // 3. LOW STOCK
        $lowStockBooks = Book::where('stock', '<=', 10)->orderBy('stock')->take(5)->get();

        // 4. RECENT ORDERS
        $recentOrders = Order::with('user')->orderByDesc('created_at')->take(5)->get();

        // 5. CHART DATA
        $period = request('period', 'week');
        $chartData = ['labels' => [], 'thisPeriod' => [], 'lastPeriod' => []];

        if ($period === 'year') {
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $chartData['labels'][] = 'Tháng ' . $date->format('m');
                $chartData['thisPeriod'][] = round(
                    Order::whereMonth('created_at', $date->month)->whereYear('created_at', $date->year)
                        ->whereNotIn('status', self::CANCELLED)->sum('total') / 1000000,
                    1
                );
                $chartData['lastPeriod'][] = round(
                    Order::whereMonth('created_at', $date->month)->whereYear('created_at', $date->year - 1)
                        ->whereNotIn('status', self::CANCELLED)->sum('total') / 1000000,
                    1
                );
            }
        } elseif ($period === 'month') {
            for ($i = 29; $i >= 0; $i -= 3) {
                $date = now()->subDays($i);
                $chartData['labels'][] = $date->format('d/m');
                $chartData['thisPeriod'][] = round(
                    Order::whereBetween('created_at', [$date->copy()->subDays(2), $date])
                        ->whereNotIn('status', self::CANCELLED)->sum('total') / 1000000,
                    1
                );
                $chartData['lastPeriod'][] = round(
                    Order::whereBetween('created_at', [$date->copy()->subDays(32), $date->copy()->subDays(30)])
                        ->whereNotIn('status', self::CANCELLED)->sum('total') / 1000000,
                    1
                );
            }
        } else {
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $chartData['labels'][] = $date->format('d/m');
                $chartData['thisPeriod'][] = round(
                    Order::whereDate('created_at', $date)
                        ->whereNotIn('status', self::CANCELLED)->sum('total') / 1000000,
                    1
                );
                $chartData['lastPeriod'][] = round(
                    Order::whereDate('created_at', $date->copy()->subDays(7))
                        ->whereNotIn('status', self::CANCELLED)->sum('total') / 1000000,
                    1
                );
            }
        }

        $sparkRevenue = [];
        $sparkOrders = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = now()->subDays($i);
            $sparkRevenue[] = round(
                Order::whereDate('created_at', $d)->whereNotIn('status', self::CANCELLED)->sum('total') / 1000000,
                1
            );
            $sparkOrders[] = Order::whereDate('created_at', $d)->count();
        }

        // 6. TOP BOOKS
        $topBooks = OrderItem::select(
            'book_title_snapshot',
            DB::raw('SUM(quantity) as total_sold'),
            DB::raw('SUM(subtotal) as total_revenue')
        )
            ->groupBy('book_title_snapshot')
            ->orderByDesc('total_revenue')
            ->take(10)->get();

        // 7. CATEGORY REVENUE
        $categoryRevenue = DB::table('order_items')
            ->join('books', 'order_items.book_id', '=', 'books.id')
            ->join('categories', 'books.category_id', '=', 'categories.id')
            ->select('categories.name as category_name', DB::raw('SUM(order_items.subtotal) as revenue'))
            ->groupBy('categories.name')
            ->orderByDesc('revenue')
            ->take(8)->get();

        // 8. CUSTOMER SEGMENTS
        $totalCustomers = User::where('role_id', '!=', 1)->count();
        $returningCustomers = User::where('role_id', '!=', 1)
            ->whereHas('orders', fn($q) => $q->havingRaw('COUNT(*) >= 2'), '>=', 2)->count();
        $newCustomersMonth = User::where('role_id', '!=', 1)
            ->where('created_at', '>=', $startOfMonth)->count();
        $vipCustomers = User::where('total_spent', '>=', 2000000)->count();

        // 9. STOCK STATUS
        $stockInStock = Book::where('stock', '>', 10)->count();
        $stockLow = Book::where('stock', '>', 0)->where('stock', '<=', 10)->count();
        $stockOut = Book::where('stock', '<=', 0)->count();

        // 10. CANCEL REASONS
        $cancelReasons = Order::whereIn('status', self::CANCELLED)
            ->where('created_at', '>=', $startOfMonth)
            ->select('cancel_reason', DB::raw('COUNT(*) as cnt'))
            ->groupBy('cancel_reason')
            ->orderByDesc('cnt')
            ->take(4)->get()
            ->map(function ($item) use ($cancelledMonth) {
                return [
                    'label' => $item->cancel_reason ?: 'Không rõ',
                    'pct' => $cancelledMonth > 0 ? round($item->cnt / $cancelledMonth * 100, 0) : 0,
                    'color' => 'bg-orange-400',
                ];
            })->toArray();

        $reasonColors = ['bg-orange-400', 'bg-sky-500', 'bg-purple-500', 'bg-gray-400'];
        foreach ($cancelReasons as $i => &$r) {
            $r['color'] = $reasonColors[$i] ?? 'bg-gray-400';
        }

        // 11. SYSTEM LOGS
        $systemLogs = SystemLog::orderByDesc('created_at')->take(8)->get();

        $stats = [
            'revenue_today' => number_format($revenueToday, 0, ',', '.') . ' ₫',
            'revenue_pct' => $revenuePctChange,
            'new_orders' => $newOrdersCount,
            'orders_diff' => $ordersDiff,
            'pending_orders' => $pendingOrders,
            'conversion_rate' => $conversionRate,
            'revenue_month' => $revenueMonth,
            'revenue_month_pct' => $revenueMonthPct,
            'revenue_last_month' => $revenueLastMonth,
            'cancel_rate' => $cancelRate,
            'cancel_rate_last' => $cancelRateLast,
            'profit_margin' => $profitMargin,
            'aov' => $aov,
            'aov_diff' => $aovDiff,
            'aov_last' => $aovLast,
            'clv' => $clv,
        ];

        return view('admin.dashboard', compact(
            'stats',
            'lowStockBooks',
            'recentOrders',
            'chartData',
            'sparkRevenue',
            'sparkOrders',
            'topBooks',
            'categoryRevenue',
            'totalCustomers',
            'returningCustomers',
            'newCustomersMonth',
            'vipCustomers',
            'stockInStock',
            'stockLow',
            'stockOut',
            'cancelReasons',
            'systemLogs',
            'period'
        ));
    }
}
