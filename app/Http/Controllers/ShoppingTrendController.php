<?php

namespace App\Http\Controllers;

use App\Services\ShoppingTrendService;
use Illuminate\Http\Request;

class ShoppingTrendController extends Controller
{
    public function __construct(
        private ShoppingTrendService $trendService
    ) {}

    /**
     * Display the full shopping trend page.
     */
    public function index(Request $request)
    {
        $period     = $request->input('period', 'day');
        $categoryId = $request->input('category') ? (int) $request->input('category') : null;

        $books      = $this->trendService->getTrendingBooks($period, $categoryId, 10);
        $categories = $this->trendService->getFilterCategories(10);

        return view('pages.shopping-trend', compact('books', 'categories', 'period', 'categoryId'));
    }
}
