<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Combo;
use App\Models\Book;
use App\Models\SystemLog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Traits\UploadsFile;
use Illuminate\Support\Facades\Cache;
use App\Models\Setting;

class ComboController extends Controller
{
    use UploadsFile;
    public function index()
    {
        $combos = Combo::withCount('books')->paginate(10);
        return view('admin.combo.index', compact('combos'));
    }

    public function create()
    {
        $books = Book::orderBy('title')->get();
        return view('admin.combo.create', compact('books'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'slug' => 'nullable|string|max:150|unique:combos,slug',
            'description' => 'nullable|string',
            'original_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'bg_from' => 'nullable|string|max:20',
            'bg_to' => 'nullable|string|max:20',
            'icon' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_visible' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
            'ids_books' => 'nullable|array',
            'ids_books.*' => 'exists:books,id',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $this->uploadFile($request->file('image'), 'uploads/combos');
        }

        $combo = Combo::create($validated);

        if ($request->has('ids_books')) {
            $combo->books()->sync($request->input('ids_books'));
        }

        SystemLog::ghi(
            type: 'data',
            action: 'create',
            description: 'Tạo combo mới: ' . $combo->name,
            level: 'info',
            objectType: 'Combo',
            objectId: $combo->id
        );

        // Clear homepage combo caches
        $limitCombos = Setting::where('key', 'home_combos_limit')->first()?->value ?? 6;
        $limitSeries = Setting::where('key', 'home_book_series_limit')->first()?->value ?? 4;
        Cache::forget("home_combos_{$limitCombos}");
        Cache::forget("home_combos_{$limitSeries}");

        return redirect()->route('admin.combo.index')->with('success', 'Thêm combo thành công!');
    }

    public function edit(Combo $combo)
    {
        $books = Book::orderBy('title')->get();
        $selectedBooks = $combo->books->pluck('id')->toArray();
        return view('admin.combo.edit', compact('combo', 'books', 'selectedBooks'));
    }

    public function update(Request $request, Combo $combo)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'slug' => 'required|string|max:150|unique:combos,slug,' . $combo->id . ',id',
            'description' => 'nullable|string',
            'original_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'bg_from' => 'nullable|string|max:20',
            'bg_to' => 'nullable|string|max:20',
            'icon' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_visible' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
            'ids_books' => 'nullable|array',
            'ids_books.*' => 'exists:books,id',
        ]);

        if ($request->hasFile('image')) {
            if ($combo->image) {
                Storage::disk('public')->delete($combo->image);
            }
            $validated['image'] = $this->uploadFile($request->file('image'), 'uploads/combos');
        }

        $combo->update($validated);

        if ($request->has('ids_books')) {
            $combo->books()->sync($request->input('ids_books'));
        } else {
            $combo->books()->detach();
        }

        SystemLog::ghi(
            type: 'data',
            action: 'update',
            description: 'Cập nhật combo: ' . $combo->name,
            level: 'info',
            objectType: 'Combo',
            objectId: $combo->id
        );

        // Clear homepage combo caches
        $limitCombos = Setting::where('key', 'home_combos_limit')->first()?->value ?? 6;
        $limitSeries = Setting::where('key', 'home_book_series_limit')->first()?->value ?? 4;
        Cache::forget("home_combos_{$limitCombos}");
        Cache::forget("home_combos_{$limitSeries}");

        return redirect()->route('admin.combo.index')->with('success', 'Cập nhật combo thành công!');
    }

    public function destroy(Combo $combo)
    {
        if ($combo->image) {
            Storage::disk('public')->delete($combo->image);
        }
        $id = $combo->id;
        $name = $combo->name;
        $combo->delete();

        SystemLog::ghi(
            type: 'data',
            action: 'delete',
            description: 'Xóa combo: ' . $name,
            level: 'warning',
            objectType: 'Combo',
            objectId: $id
        );

        // Clear homepage combo caches
        $limitCombos = Setting::where('key', 'home_combos_limit')->first()?->value ?? 6;
        $limitSeries = Setting::where('key', 'home_book_series_limit')->first()?->value ?? 4;
        Cache::forget("home_combos_{$limitCombos}");
        Cache::forget("home_combos_{$limitSeries}");

        return redirect()->route('admin.combo.index')->with('success', 'Xóa combo thành công!');
    }
}
