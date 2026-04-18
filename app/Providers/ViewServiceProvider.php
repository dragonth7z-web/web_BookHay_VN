<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
    //
    }

    public function boot(): void
    {
        // Share $megaCategories with the header component
        \Illuminate\Support\Facades\View::composer('components.header', function ($view) {
            $megaCategories = \App\Models\Category::whereNull('parent_id')
                ->where('is_visible', 1)
                ->orderBy('sort_order')
                ->with([
                'children' => function ($query) {
                $query->where('is_visible', 1)->orderBy('sort_order');
            }
            ])
                ->get();
            $view->with('megaCategories', $megaCategories);
        });
    }
}
