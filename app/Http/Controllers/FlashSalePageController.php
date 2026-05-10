<?php

namespace App\Http\Controllers;

use App\Services\FlashSalePageService;

class FlashSalePageController extends Controller
{
    public function __construct(
        private FlashSalePageService $flashSalePageService
    ) {}

    public function index()
    {
        $activeSale    = $this->flashSalePageService->getActiveSale();
        $upcomingSales = $this->flashSalePageService->getUpcomingSales(3);

        // Fallback books via Service → Repository — no direct Model calls in Controller
        $fallbackBooks = collect();
        if (!$activeSale || $activeSale->items->isEmpty()) {
            $fallbackBooks = $this->flashSalePageService->getFallbackBooks(8);
        }

        return view('pages.flash-sale', compact('activeSale', 'upcomingSales', 'fallbackBooks'));
    }
}
