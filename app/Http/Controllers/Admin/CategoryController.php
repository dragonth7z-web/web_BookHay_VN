<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SystemLog;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Exception;

class CategoryController extends Controller
{
    public function __construct(private CategoryRepository $repo)
    {
    }

    public function index()
    {
        $categories = $this->repo->getParentCategoriesWithChildren();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = $this->repo->getParentCategoriesOptions();
        return view('admin.categories.create', compact('parents'));
    }

    public function store(StoreCategoryRequest $request)
    {
        try {
            $category = Category::create($request->validated());
            
            SystemLog::ghi(
                type: 'data',
                action: 'create',
                description: 'Created new category: ' . $category->name,
                level: 'info',
                objectType: 'Category',
                objectId: $category->id
            );

            // Clear cache for homepage categories
            Cache::forget('home_sidebar_cats_12');

            return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
        } catch (Exception $e) {
            Log::error("Error in CategoryController@store: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to create category. Please try again.');
        }
    }

    public function edit(Category $category)
    {
        $parents = $this->repo->getParentCategoriesOptions($category->id);
        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        try {
            $category->update($request->validated());
            
            SystemLog::ghi(
                type: 'data',
                action: 'update',
                description: 'Updated category: ' . $category->name,
                level: 'info',
                objectType: 'Category',
                objectId: $category->id
            );

            // Clear cache for homepage categories
            Cache::forget('home_sidebar_cats_12');

            return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
        } catch (Exception $e) {
            Log::error("Error in CategoryController@update: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to update category. Please try again.');
        }
    }

    public function destroy(Category $category)
    {
        try {
            $categoryId = $category->id;
            $categoryName = $category->name;
            
            $category->delete();
            
            SystemLog::ghi(
                type: 'data',
                action: 'delete',
                description: 'Deleted category: ' . $categoryName,
                level: 'warning',
                objectType: 'Category',
                objectId: $categoryId
            );

            // Clear cache for homepage categories
            Cache::forget('home_sidebar_cats_12');

            return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
        } catch (Exception $e) {
            Log::error("Error in CategoryController@destroy: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete category. It might be linked to existing products.');
        }
    }
}
