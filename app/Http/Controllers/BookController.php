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
            $rawQ     = trim($request->q);
            $q        = mb_strtolower($rawQ);
            $qNoSpace = str_replace(' ', '', $q);

            // Pass 1: SQL LIKE (exact + condensed)
            $pass1Ids = Book::where('status', BookStatus::InStock)
                ->where(function ($sub) use ($q, $qNoSpace) {
                    $sub->whereRaw('LOWER(title) LIKE ?', ["%{$q}%"])
                        ->orWhereRaw("REPLACE(LOWER(title), ' ', '') LIKE ?", ["%{$qNoSpace}%"])
                        ->orWhereHas('authors', fn($t) =>
                            $t->whereRaw('LOWER(name) LIKE ?', ["%{$q}%"])
                              ->orWhereRaw("REPLACE(LOWER(name), ' ', '') LIKE ?", ["%{$qNoSpace}%"])
                        );
                })
                ->pluck('id');

            if ($pass1Ids->isNotEmpty()) {
                $query->whereIn('id', $pass1Ids);
            } else {
                // Pass 2: bigram scoring
                $bigrams = [];
                for ($i = 0; $i < mb_strlen($q) - 1; $i++) {
                    $bigrams[] = mb_substr($q, $i, 2);
                }
                if (count($bigrams) >= 3) {
                    $needed = (int) ceil(count($bigrams) * 0.8);
                    $allBooks = Book::where('status', BookStatus::InStock)->select('id', 'title')->get();
                    $matchedIds = $allBooks->filter(function ($book) use ($bigrams, $needed) {
                        $tl = mb_strtolower($book->title);
                        $cnt = 0;
                        foreach ($bigrams as $bg) {
                            if (mb_strpos($tl, $bg) !== false) $cnt++;
                        }
                        return $cnt >= $needed;
                    })->pluck('id');

                    if ($matchedIds->isNotEmpty()) {
                        $query->whereIn('id', $matchedIds);
                    } else {
                        $query->whereRaw('1 = 0'); // no results
                    }
                } else {
                    $query->whereRaw('1 = 0');
                }
            }
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
