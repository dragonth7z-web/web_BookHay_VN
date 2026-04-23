<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\BookStatus;
use App\Models\Book;
use App\Repositories\BookRepository;
use App\Services\BookService;

class BookController extends Controller
{
    public function __construct(
        protected BookService $bookService,
        protected BookRepository $bookRepo,
    ) {}

    public function index(Request $request)
    {
        $query = Book::with(['category', 'publisher', 'authors'])
            ->where('status', BookStatus::InStock);

        if ($request->filled('q')) {
            $matchedIds = $this->bookService->getFuzzySearchIds(trim($request->q));

            if ($matchedIds->isNotEmpty()) {
                $query->whereIn('id', $matchedIds);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        if ($request->filled('category')) {
            $query->whereIn('category_id', (array) $request->category);
        }

        if ($request->filled('publisher')) {
            $query->whereIn('publisher_id', (array) $request->publisher);
        }

        if ($request->filled('price_min')) {
            $query->where('sale_price', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('sale_price', '<=', $request->price_max);
        }

        $sortMap = [
            'price_asc'  => ['sale_price', 'asc'],
            'price_desc' => ['sale_price', 'desc'],
            'newest'     => ['id', 'desc'],
            'bestseller' => ['sold_count', 'desc'],
            'rating'     => ['rating_avg', 'desc'],
        ];

        [$sortCol, $sortDir] = $sortMap[$request->sort] ?? ['id', 'desc'];
        $query->orderBy($sortCol, $sortDir);

        $books      = $query->paginate(20)->withQueryString();
        $categories = $this->bookRepo->getAllCategories();
        $publishers = $this->bookRepo->getAllPublishers();

        return view('books.index', compact('books', 'categories', 'publishers'));
    }

    public function show(Book $book)
    {
        if ($book->status === BookStatus::Discontinued) {
            abort(404);
        }

        $book->load(['category', 'publisher', 'authors', 'reviews.user']);

        $book->sale_percent = ($book->original_price > 0 && $book->original_price > $book->sale_price)
            ? round((($book->original_price - $book->sale_price) / $book->original_price) * 100)
            : 0;

        $relatedBooks  = $this->bookRepo->getRelatedBooks($book->id, $book->category_id, 5);
        $fallbackBooks = $this->bookRepo->getFallbackBooks($book->id, $book->category_id, 6);

        return view('books.show', compact('book', 'relatedBooks', 'fallbackBooks'));
    }
}
