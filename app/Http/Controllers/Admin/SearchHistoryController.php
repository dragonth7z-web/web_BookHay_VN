<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SearchHistory;

class SearchHistoryController extends Controller
{
    public function index()
    {
        $histories = SearchHistory::with('user')->orderByDesc('id')->paginate(20);
        return view('admin.search-histories.index', compact('histories'));
    }

    public function show(SearchHistory $searchHistory)
    {
        return view('admin.search-histories.show', compact('searchHistory'));
    }

    public function destroy(SearchHistory $searchHistory)
    {
        $searchHistory->delete();
        return redirect()->route('admin.search-histories.index')->with('success', 'Xóa thành công.');
    }
}
