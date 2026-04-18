<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SystemLog;
use App\Http\Requests\Admin\UpdateFeaturedWorksRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Exception;

class FeaturedWorksController extends Controller
{
    public function index()
    {
        $books = Book::query()
            ->where('status', 'in_stock')
            ->with('category')
            ->orderByDesc('id')
            ->paginate(12);

        return view('admin.featured-works.index', [
            'books' => $books,
        ]);
    }

    public function update(UpdateFeaturedWorksRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $pageIds = collect($validatedData['page_ids'] ?? []);
            $featuredIds = collect($validatedData['featured_ids'] ?? []);

            DB::transaction(function () use ($pageIds, $featuredIds) {
                Book::query()
                    ->whereIn('id', $pageIds)
                    ->where('status', 'in_stock')
                    ->update(['is_featured' => 0]);

                $targetIds = $featuredIds->intersect($pageIds)->values()->all();
                if (!empty($targetIds)) {
                    Book::query()
                        ->whereIn('id', $targetIds)
                        ->where('status', 'in_stock')
                        ->update(['is_featured' => 1]);
                }
            });

            SystemLog::ghi(
                type: 'data',
                action: 'update',
                description: 'Updated Featured Works list',
                level: 'info',
                objectType: 'Book',
                objectId: null
            );

            // Clear cache for featured books section
            Cache::forget('featured_books');

            return redirect()->route('admin.featured-works.index')->with('success', 'Featured works updated successfully!');
        } catch (Exception $e) {
            Log::error("Error in FeaturedWorksController@update: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update featured works. Please try again.');
        }
    }
}
