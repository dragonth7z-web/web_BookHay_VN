<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\BookResource;
use App\Repositories\HomeRepository;
use App\Services\BookService;

class HomeController extends Controller
{
    public function __construct(
        protected HomeRepository $homeRepo,
        protected BookService $bookService,
    ) {}

    public function index()
    {
        [$flashSaleBooks, $activeFlashSale] = $this->homeRepo->getFlashSaleBooks();

        $configs     = $this->homeRepo->getConfigs();
        $limitCombos = (int) ($configs['home_combos_limit'] ?? 10);
        $limitSeries = (int) ($configs['home_book_series_limit'] ?? 4);

        $data = [
            'flashSaleBooks'    => $flashSaleBooks,
            'activeFlashSale'   => $activeFlashSale,
            'rankingCategories' => $this->homeRepo->getRankingCategories(8),
            'weeklyRankings'    => $this->homeRepo->getWeeklyRankings(6),
            'featuredBooks'     => $this->homeRepo->getFeaturedBooks(),
            'recommendedBooks'  => $this->homeRepo->getRecommendedBooks(),
            'combos'            => $this->homeRepo->getCombos($limitCombos),
            'collections'       => $this->homeRepo->getCollections(15),
            'partners'          => $this->homeRepo->getPartners(),
            'mainBanners'       => $this->homeRepo->getBanners('home_main'),
            'miniBanners'       => $this->homeRepo->getBanners('home_mini', 2),
            'giftBanners'       => $this->homeRepo->getBanners('home_gift', 4),
            'sidebarCategories' => $this->homeRepo->getSidebarCategories(12),
            'bookSeries'        => $this->homeRepo->getSeries($limitSeries),
            'latestBooks'       => $this->homeRepo->getLatestBooks(15),
            'features'          => array_slice($this->homeRepo->getQuickFeatures(), 0, 8),
            'vouchers'          => $this->homeRepo->getVouchers(3),
            'youngAuthorsBooks' => $this->homeRepo->getYoungAuthorsBooks(15),
            'trendingKeywords'  => $this->homeRepo->getTrendingKeywords(5),
            'configs'           => $configs,
        ];

        return view('home', $data);
    }

    public function searchSuggestionsApi(Request $request)
    {
        $q = trim($request->input('q', ''));

        if (strlen($q) >= 2) {
            $books = $this->bookService->fuzzySearch($q, 6)
                ->map(fn($book) => [
                    'id'     => $book->id,
                    'title'  => $book->title,
                    'slug'   => $book->slug,
                    'image'  => $book->cover_image_url,
                    'price'  => $book->sale_price,
                    'author' => $book->authors->pluck('name')->implode(', '),
                ]);

            return response()->json(['type' => 'results', 'books' => $books]);
        }

        $defaultData = $this->homeRepo->getSearchSuggestionsDefault();

        return response()->json(array_merge(['type' => 'default'], $defaultData));
    }

    public function getShoppingTrendApi(Request $request)
    {
        $period     = in_array($request->input('period'), ['day', 'week', 'month', 'year'])
            ? $request->input('period') : 'day';
        $categoryId = $request->input('category_id', 'all');

        $books = $this->homeRepo->getTrendingBooks($period, $categoryId, 10);

        $books->each(function ($book, $index) {
            $book->index = $index;
            $book->rank  = $index + 1;
        });

        return response()->json(['success' => true, 'data' => BookResource::collection($books)]);
    }

    public function getWeeklyRankingApi(Request $request)
    {
        $categoryId = $request->input('category_id');

        $books = ($categoryId === 'all' || empty($categoryId))
            ? $this->homeRepo->getWeeklyRankings(6)
            : $this->homeRepo->getTopBooksByCategory($categoryId, 6);

        $books->each(function ($book, $index) use ($categoryId) {
            $book->index = $index;
            $book->rank  = $index + 1;
            if ($categoryId !== 'all' && !empty($categoryId)) {
                $book->category_name_override = $categoryId;
            }
        });

        return response()->json(['success' => true, 'data' => BookResource::collection($books)]);
    }
}
