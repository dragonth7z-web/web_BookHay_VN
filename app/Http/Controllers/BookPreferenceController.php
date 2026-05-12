<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Category;
use App\Enums\BookStatus;

class BookPreferenceController extends Controller
{
    /**
     * Display the book preference customization page.
     */
    public function index()
    {
        // All visible top-level categories with book counts
        $categories = Category::where('is_visible', true)
            ->whereNull('parent_id')
            ->withCount(['books' => fn ($q) => $q->where('status', BookStatus::InStock)])
            ->orderBy('sort_order')
            ->get();

        // Top authors by book count
        $authors = Author::withCount('books')
            ->orderByDesc('books_count')
            ->take(6)
            ->get();

        return view('pages.book-preferences', compact('categories', 'authors'));
    }
}
