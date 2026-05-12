<?php

namespace App\Http\Controllers;

use App\Models\Publisher;

class PublisherPageController extends Controller
{
    /**
     * Display the publisher partners listing page.
     */
    public function index()
    {
        $activeFilter = request('filter', 'all');

        $query = Publisher::withCount('books')->orderByDesc('id');

        $publishers = match ($activeFilter) {
            'partner'  => $query->where('is_partner', true)->get(),
            'domestic' => $query->whereRaw("name NOT REGEXP '[A-Za-z]'")->get(),
            'foreign'  => $query->whereRaw("name REGEXP '[A-Za-z]'")->get(),
            default    => $query->get(),
        };

        $totalPartners  = Publisher::where('is_partner', true)->count();
        $totalPublishers = Publisher::count();

        return view('pages.publishers', compact(
            'publishers',
            'activeFilter',
            'totalPartners',
            'totalPublishers',
        ));
    }
}
