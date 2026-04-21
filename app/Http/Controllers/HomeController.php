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

    /**
     * Two-pass fuzzy search:
     * Pass 1: exact phrase + condensed (no-space) match  → fast SQL
     * Pass 2: bigram scoring in PHP (only when pass1 empty) → handles typos like "onpiece"
     */
    private function fuzzyBookSearch(string $rawQ, int $limit = 6): \Illuminate\Support\Collection
    {
        $q        = mb_strtolower(trim($rawQ));
        $qNoSpace = str_replace(' ', '', $q);

        // Pass 1 — SQL LIKE
        $pass1 = Book::with('authors')
            ->where('status', BookStatus::InStock)
            ->where(function ($sub) use ($q, $qNoSpace) {
                $sub->whereRaw('LOWER(title) LIKE ?', ["%{$q}%"])
                    ->orWhereRaw("REPLACE(LOWER(title), ' ', '') LIKE ?", ["%{$qNoSpace}%"])
                    ->orWhereHas('authors', fn($t) =>
                        $t->whereRaw('LOWER(name) LIKE ?', ["%{$q}%"])
                          ->orWhereRaw("REPLACE(LOWER(name), ' ', '') LIKE ?", ["%{$qNoSpace}%"])
                    );
            })
            ->orderByDesc('sold_count')
            ->take($limit)
            ->get();

        if ($pass1->isNotEmpty()) return $pass1;

        // Pass 2 — bigram scoring (PHP-side, only on title)
        $bigrams = [];
        for ($i = 0; $i < mb_strlen($q) - 1; $i++) {
            $bigrams[] = mb_substr($q, $i, 2);
        }
        if (count($bigrams) < 3) return collect();

        $needed = (int) ceil(count($bigrams) * 0.8);

        $candidates = Book::with('authors')
            ->where('status', BookStatus::InStock)
            ->select('id', 'title', 'slug', 'cover_image', 'sale_price', 'sold_count')
            ->get();

        $scored = $candidates
            ->map(function ($book) use ($bigrams, $needed) {
                $titleLower = mb_strtolower($book->title);
                $matchCount = 0;
                foreach ($bigrams as $bg) {
                    if (mb_strpos($titleLower, $bg) !== false) $matchCount++;
                }
                $book->_score = $matchCount;
                return $book;
            })
            ->filter(fn($b) => $b->_score >= $needed)
            ->sortByDesc('_score')
            ->take($limit)
            ->values();

        // Load authors for scored results
        if ($scored->isNotEmpty()) {
            $scored->load('authors');
        }

        return $scored;
    }

    public function searchSuggestionsApi(Request $request)
    {
        $q = trim($request->input('q', ''));

        if (strlen($q) >= 2) {
            $books = $this->fuzzyBookSearch($q, 6)
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

        // Default: hot keywords + featured categories
        $hotKeywords = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('search_histories')) {
            $hotKeywords = \Illuminate\Support\Facades\DB::table('search_histories')
                ->select('keyword', \Illuminate\Support\Facades\DB::raw('count(*) as cnt'))
                ->groupBy('keyword')
                ->orderByDesc('cnt')
                ->limit(6)
                ->get()
                ->map(fn($r) => [
                    'keyword' => $r->keyword,
                    'image'   => null,
                ]);
        }
        if ($hotKeywords->isEmpty()) {
            $hotKeywords = Book::where('status', BookStatus::InStock)
                ->orderByDesc('sold_count')
                ->take(6)
                ->get()
                ->map(fn($b) => [
                    'keyword' => $b->title,
                    'image'   => $b->cover_image_url,
                ]);
        }

        $categories = \App\Models\Category::where('is_visible', true)
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->take(4)
            ->get()
            ->map(fn($c) => [
                'id'    => $c->id,
                'name'  => $c->name,
                'image' => $c->image ? asset('storage/' . $c->image) : null,
                'slug'  => $c->slug,
            ]);

        $flashSale = \App\Models\FlashSale::active()->first();

        return response()->json([
            'type'        => 'default',
            'hotKeywords' => $hotKeywords,
            'categories'  => $categories,
            'flashSale'   => $flashSale ? ['name' => $flashSale->name] : null,
        ]);
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
