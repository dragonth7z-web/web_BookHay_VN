<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FlashSale;
use App\Models\WeeklyRanking;
use App\Models\Book;
use App\Models\Combo;
use App\Models\Collection;
use App\Models\Publisher;
use App\Models\Category;
use App\Models\Banner;
use App\Models\Coupon;
use App\Models\Setting;
use App\Enums\BookStatus;
use Illuminate\Support\Facades\Schema;
use App\Http\Resources\BookResource;

use App\Repositories\HomeRepository;

class HomeController extends Controller
{
    protected $homeRepo;

    public function __construct(HomeRepository $homeRepo)
    {
        $this->homeRepo = $homeRepo;
    }

    public function index()
    {
        [$flashSaleBooks, $activeFlashSale] = $this->homeRepo->getFlashSaleBooks();

        $configs = $this->homeRepo->getConfigs();
        $limitCombos = (int) ($configs['home_combos_limit'] ?? 10);
        $limitSeries = (int) ($configs['home_book_series_limit'] ?? 4);

        $data = [
            'flashSaleBooks' => $flashSaleBooks,
            'activeFlashSale' => $activeFlashSale,
            'rankingCategories' => $this->homeRepo->getRankingCategories(8),
            'weeklyRankings' => $this->homeRepo->getWeeklyRankings(6),
            'featuredBooks' => $this->homeRepo->getFeaturedBooks(),
            'recommendedBooks' => $this->homeRepo->getRecommendedBooks(),
            'combos' => $this->homeRepo->getCombos($limitCombos),
            'collections' => $this->homeRepo->getCollections(15),
            'partners' => $this->homeRepo->getPartners(),
            'mainBanners' => $this->homeRepo->getBanners('home_main'),
            'miniBanners' => $this->homeRepo->getBanners('home_mini', 2),
            'giftBanners' => $this->homeRepo->getBanners('home_gift', 4),
            'sidebarCategories' => $this->homeRepo->getSidebarCategories(12),
            'bookSeries' => $this->homeRepo->getSeries($limitSeries),
            'latestBooks' => $this->homeRepo->getLatestBooks(15),
            'features' => array_slice($this->homeRepo->getQuickFeatures(), 0, 8),
            'vouchers' => $this->homeRepo->getVouchers(3),
            'youngAuthorsBooks' => $this->homeRepo->getYoungAuthorsBooks(15),
            'trendingKeywords' => $this->homeRepo->getTrendingKeywords(5),
            'configs' => $configs,
        ];

        return view('home', $data);
    }

    public function getShoppingTrendApi(Request $request)
    {
        $period = in_array($request->input('period'), ['day', 'week', 'month', 'year'])
            ? $request->input('period') : 'day';
        $categoryId = $request->input('category_id', 'all');

        $books = $this->homeRepo->getTrendingBooks($period, $categoryId, 10);

        // Inject rank and index for the Resource
        $books->each(function ($book, $index) {
            $book->index = $index;
            $book->rank = $index + 1;
        });

        return response()->json([
            'success' => true,
            'data' => BookResource::collection($books)
        ]);
    }

    public function getWeeklyRankingApi(Request $request)
    {
        $categoryId = $request->input('category_id');

        if ($categoryId === 'all' || empty($categoryId)) {
            $books = $this->homeRepo->getWeeklyRankings(6);
        } else {
            $books = $this->homeRepo->getTopBooksByCategory($categoryId, 6);
        }

        // Inject rank and index for the Resource
        $books->each(function ($book, $index) use ($categoryId) {
            $book->index = $index;
            $book->rank = $index + 1;
            // Ensure category name is set correctly if it was an "all" request
            if ($categoryId !== 'all' && !empty($categoryId)) {
                $book->category_name_override = $categoryId;
            }
        });

        return response()->json([
            'success' => true,
            'data' => BookResource::collection($books)
        ]);
    }

}
