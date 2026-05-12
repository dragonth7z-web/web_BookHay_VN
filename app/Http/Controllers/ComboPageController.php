<?php

namespace App\Http\Controllers;

use App\Models\Combo;
use App\Enums\BookStatus;

class ComboPageController extends Controller
{
    /**
     * Display the full combo listing page.
     */
    public function index()
    {
        $combos = Combo::with(['books.category'])
            ->where('is_visible', true)
            ->orderBy('sort_order')
            ->get();

        // Build category tabs from the first book's category in each combo
        $categoryTabs = $combos->map(function ($combo) {
            $firstCat = $combo->books
                ->map(fn ($b) => $b->category?->name)
                ->filter()
                ->first();

            return $firstCat ? 'Combo ' . $firstCat : null;
        })->filter()->unique()->values();

        $activeCategory = request('category');

        // Filter combos by category tab if requested
        if ($activeCategory) {
            $combos = $combos->filter(function ($combo) use ($activeCategory) {
                $firstCat = $combo->books
                    ->map(fn ($b) => $b->category?->name)
                    ->filter()
                    ->first();

                $label = $firstCat ? 'Combo ' . $firstCat : null;
                return $label === $activeCategory;
            })->values();
        }

        return view('pages.combos', compact('combos', 'categoryTabs', 'activeCategory'));
    }
}
