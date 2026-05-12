<?php

namespace App\Http\Controllers;

use App\Models\Combo;
use Illuminate\Support\Str;

class BookSeriesPageController extends Controller
{
    /**
     * Display the full book series / boxset listing page.
     */
    public function index()
    {
        $bookSeries = Combo::with(['books' => fn ($q) => $q->with('authors')->orderBy('id')])
            ->where('type', 'series')
            ->where('is_visible', true)
            ->orderBy('sort_order')
            ->paginate(10);

        return view('pages.book-series', compact('bookSeries'));
    }
}
