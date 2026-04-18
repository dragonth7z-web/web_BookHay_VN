<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Traits\UploadsFile;
use Illuminate\Support\Facades\Cache;

class CollectionController extends Controller
{
    use UploadsFile;
    public function index()
    {
        $collections = Collection::orderBy('sort_order')->paginate(10);
        return view('admin.collection.index', compact('collections'));
    }

    public function create()
    {
        return view('admin.collection.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'      => 'required|string|max:150',
            'subtitle'   => 'nullable|string|max:150',
            'badge'      => 'nullable|string|max:50',
            'image'      => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'url'        => 'nullable|string|max:255',
            'is_visible' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->uploadFile($request->file('image'), 'uploads/collections');
        }

        $collection = Collection::create($validated);

        SystemLog::ghi(
            type: 'data',
            action: 'create',
            description: 'Thêm bộ sưu tập: ' . $collection->title,
            level: 'info',
            objectType: 'Collection',
            objectId: $collection->id
        );

        // Clear homepage collections cache
        Cache::forget('home_collections_6');

        return redirect()->route('admin.collections.index')->with('success', 'Thêm bộ sưu tập thành công!');
    }

    public function edit(Collection $collection)
    {
        return view('admin.collection.edit', compact('collection'));
    }

    public function update(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'title'      => 'required|string|max:150',
            'subtitle'   => 'nullable|string|max:150',
            'badge'      => 'nullable|string|max:50',
            'image'      => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'url'        => 'nullable|string|max:255',
            'is_visible' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            if ($collection->image) {
                Storage::disk('public')->delete($collection->image);
            }
            $validated['image'] = $this->uploadFile($request->file('image'), 'uploads/collections');
        }

        $collection->update($validated);

        SystemLog::ghi(
            type: 'data',
            action: 'update',
            description: 'Cập nhật bộ sưu tập: ' . $collection->title,
            level: 'info',
            objectType: 'Collection',
            objectId: $collection->id
        );

        // Clear homepage collections cache
        Cache::forget('home_collections_6');

        return redirect()->route('admin.collections.index')->with('success', 'Cập nhật bộ sưu tập thành công!');
    }

    public function destroy(Collection $collection)
    {
        if ($collection->image) {
            Storage::disk('public')->delete($collection->image);
        }
        $id = $collection->id;
        $title = $collection->title;
        $collection->delete();

        SystemLog::ghi(
            type: 'data',
            action: 'delete',
            description: 'Xóa bộ sưu tập: ' . $title,
            level: 'warning',
            objectType: 'Collection',
            objectId: $id
        );

        // Clear homepage collections cache
        Cache::forget('home_collections_6');

        return redirect()->route('admin.collections.index')->with('success', 'Xóa bộ sưu tập thành công!');
    }
}
