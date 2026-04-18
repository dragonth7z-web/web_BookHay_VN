<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Traits\UploadsFile;
use App\Http\Requests\Admin\StoreBannerRequest;
use App\Http\Requests\Admin\UpdateBannerRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Exception;

class BannerController extends Controller
{
    use UploadsFile;

    public function index()
    {
        $banners = Banner::orderBy('sort_order', 'asc')->paginate(10);
        return view('admin.banner.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banner.create');
    }

    public function store(StoreBannerRequest $request)
    {
        try {
            $validatedData = $request->validated();

            // Process image upload
            $validatedData['image'] = $this->processImage($request);

            $banner = Banner::create($validatedData);

            SystemLog::ghi(
                type: 'data',
                action: 'create',
                description: 'Created new banner: ' . ($validatedData['title'] ?? 'No title'),
                level: 'info',
                objectType: 'Banner',
                objectId: $banner->id
            );

            // Clear cache for homepage banners
            Cache::forget('home_banners_home_main_');
            Cache::forget('home_banners_home_mini_2');
            Cache::forget('home_banners_home_gift_3');

            return redirect()->route('admin.banner.index')
                ->with('success', 'Banner created successfully!');
        } catch (Exception $e) {
            Log::error("Error in BannerController@store: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to create banner. Please try again.');
        }
    }

    public function show(Banner $banner)
    {
        return view('admin.banner.show', compact('banner'));
    }

    public function edit(Banner $banner)
    {
        return view('admin.banner.edit', compact('banner'));
    }

    public function update(UpdateBannerRequest $request, Banner $banner)
    {
        try {
            $validatedData = $request->validated();
            $oldImagePath = $banner->image;

            // Process image upload (if new image exists)
            $newImage = $this->processImage($request);
            if ($newImage) {
                $validatedData['image'] = $newImage;
            } else {
                unset($validatedData['image']);
            }

            unset($validatedData['image_url']);
            
            // Perform DB Update first
            $banner->update($validatedData);

            // Only delete old image if the update was successful and there is a new image
            if ($newImage && $oldImagePath && !filter_var($oldImagePath, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($oldImagePath);
            }

            SystemLog::ghi(
                type: 'data',
                action: 'update',
                description: 'Updated banner: ' . ($validatedData['title'] ?? $banner->title),
                level: 'info',
                objectType: 'Banner',
                objectId: $banner->id
            );

            // Clear cache for homepage banners
            Cache::forget('home_banners_home_main_');
            Cache::forget('home_banners_home_mini_2');
            Cache::forget('home_banners_home_gift_3');

            return redirect()->route('admin.banner.index')
                ->with('success', 'Banner updated successfully!');
        } catch (Exception $e) {
            Log::error("Error in BannerController@update: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to update banner. Please try again.');
        }
    }

    public function destroy(Banner $banner)
    {
        try {
            $bannerId = $banner->id;
            $title = $banner->title;
            $imagePath = $banner->image;

            $banner->delete();

            // Delete file after DB record is successfully deleted
            if ($imagePath && !filter_var($imagePath, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($imagePath);
            }

            SystemLog::ghi(
                type: 'data',
                action: 'delete',
                description: 'Deleted banner: ' . ($title ?? 'No title'),
                level: 'warning',
                objectType: 'Banner',
                objectId: $bannerId
            );

            // Clear cache for homepage banners
            Cache::forget('home_banners_home_main_');
            Cache::forget('home_banners_home_mini_2');
            Cache::forget('home_banners_home_gift_3');

            return redirect()->route('admin.banner.index')
                ->with('success', 'Banner deleted successfully!');
        } catch (Exception $e) {
            Log::error("Error in BannerController@destroy: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete banner. Please try again.');
        }
    }

    /**
     * Handle image processing: priority to uploaded file, fallback URL
     */
    private function processImage($request): ?string
    {
        // Priority 1: File upload
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            return $this->uploadFile($request->file('image'), 'banners');
        }

        // Priority 2: Manual URL
        if ($request->filled('image_url')) {
            return $request->input('image_url');
        }

        // Priority 3: Old image value (backward compat)
        if ($request->filled('image') && is_string($request->input('image'))) {
            return $request->input('image');
        }

        return null;
    }
}
