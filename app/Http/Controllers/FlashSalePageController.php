<?php

namespace App\Http\Controllers;

use App\Services\FlashSalePageService;
use App\Enums\BookStatus;
use Illuminate\Support\Facades\DB;

class FlashSalePageController extends Controller
{
    public function __construct(
        private FlashSalePageService $flashSalePageService
    ) {}

    public function index()
    {
        $activeSale    = $this->flashSalePageService->getActiveSale();
        $upcomingSales = $this->flashSalePageService->getUpcomingSales(3);

        // Fallback books when no active sale — lấy sách đang giảm giá từ DB
        $fallbackBooks = collect();
        if (!$activeSale || $activeSale->items->isEmpty()) {
            $fallbackBooks = \App\Models\Book::with(['authors', 'category'])
                ->where('status', \App\Enums\BookStatus::InStock)
                ->where('original_price', '>', \Illuminate\Support\Facades\DB::raw('sale_price'))
                ->orderByDesc('sold_count')
                ->take(8)
                ->get();
        }

        return view('pages.flash-sale', compact('activeSale', 'upcomingSales', 'fallbackBooks'));
    }
}
