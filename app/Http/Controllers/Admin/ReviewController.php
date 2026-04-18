<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['book', 'user'])->orderByDesc('created_at')->paginate(20);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function show(Review $review)
    {
        return view('admin.reviews.show', compact('review'));
    }

    public function update(Request $request, Review $review)
    {
        $review->update($request->only(['status']));
        return redirect()->route('admin.reviews.index')->with('success', 'Cập nhật thành công.');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->route('admin.reviews.index')->with('success', 'Xóa thành công.');
    }
}
