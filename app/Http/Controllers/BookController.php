<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use App\Enums\BookStatus;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with(['category', 'publisher', 'authors'])
            ->where('status', BookStatus::InStock);

        if ($request->filled('q')) {
            $kw = $request->q;
            $query->where(function ($q) use ($kw) {
                $q->where('title', 'like', "%$kw%")->orWhereHas('authors', fn($t) => $t->where('name', 'like', "%$kw%"));
            });
        }

        if ($request->filled('category')) {
            $query->whereIn('category_id', (array)$request->category);
        }

        if ($request->filled('publisher')) {
            $query->whereIn('publisher_id', (array)$request->publisher);
        }

        if ($request->filled('price_min')) {
            $query->where('sale_price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('sale_price', '<=', $request->price_max);
        }

        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('sale_price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('sale_price', 'desc');
                break;
            case 'newest':
                $query->orderByDesc('id');
                break;
            case 'bestseller':
                $query->orderByDesc('sold_count');
                break;
            case 'rating':
                $query->orderByDesc('rating_avg');
                break;
            default:
                $query->orderByDesc('id');
        }

        $books = $query->paginate(20)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $publishers = Publisher::orderBy('name')->get();

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

        $relatedBooks = Book::where('status', BookStatus::InStock)
            ->where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->orderByDesc('sold_count')
            ->take(5)
            ->get();

        $fallbackBooks = Book::where('status', BookStatus::InStock)
            ->where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->inRandomOrder()
            ->take(6)
            ->get();

        return view('books.show', compact('book', 'relatedBooks', 'fallbackBooks'));
    }
}
