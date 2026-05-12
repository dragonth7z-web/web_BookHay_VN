<?php

namespace App\Http\Controllers;

use App\Models\Collection;

class CollectionPageController extends Controller
{
    /**
     * Display all collections listing page.
     */
    public function index()
    {
        $collections = Collection::where('is_visible', true)
            ->orderBy('sort_order')
            ->get();

        // Featured = first 3 for the bento section
        $featured = $collections->take(3);

        return view('pages.collections', compact('collections', 'featured'));
    }

    /**
     * Display a single collection with its books.
     */
    public function show(Collection $collection)
    {
        if (!$collection->is_visible) {
            abort(404);
        }

        // If collection has a custom URL, redirect there
        if ($collection->url) {
            return redirect($collection->url);
        }

        return redirect()->route('books.search', ['collection' => $collection->id]);
    }
}
