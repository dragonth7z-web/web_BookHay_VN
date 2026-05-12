<?php

namespace App\Repositories;

use App\Contracts\Repositories\SecondHandMarketRepositoryInterface;
use App\Enums\BookStatus;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class SecondHandMarketRepository implements SecondHandMarketRepositoryInterface
{
    public function getFeaturedBooks(int $take = 9): Collection
    {
        return Book::with(['authors', 'category'])
            ->where('status', BookStatus::InStock)
            ->inRandomOrder()
            ->take($take)
            ->get();
    }

    public function getMarketStats(): array
    {
        $totalBooks = Book::where('status', BookStatus::InStock)->count();

        return [
            [
                'icon'  => 'menu_book',
                'value' => number_format($totalBooks, 0, ',', '.') . '+',
                'label' => 'Đầu sách đang có',
                'color' => 'text-blue-600',
                'bg'    => 'bg-blue-50',
            ],
            [
                'icon'  => 'verified',
                'value' => '100%',
                'label' => 'Kiểm duyệt chất lượng',
                'color' => 'text-green-600',
                'bg'    => 'bg-green-50',
            ],
            [
                'icon'  => 'savings',
                'value' => '30-70%',
                'label' => 'Tiết kiệm so với mới',
                'color' => 'text-primary',
                'bg'    => 'bg-rose-50',
            ],
            [
                'icon'  => 'eco',
                'value' => '1.200+',
                'label' => 'Kg giấy được tái sử dụng',
                'color' => 'text-teal-600',
                'bg'    => 'bg-teal-50',
            ],
        ];
    }

    public function getFilterCategories(): array
    {
        $dbCategories = Category::whereHas('books', function ($q) {
            $q->where('status', BookStatus::InStock);
        })
        ->orderByDesc('id')
        ->take(4)
        ->get();

        $filters = [
            ['label' => 'Tất cả', 'icon' => 'apps', 'active' => true, 'id' => null],
        ];

        foreach ($dbCategories as $cat) {
            $filters[] = [
                'label'  => $cat->name,
                'icon'   => $cat->icon ?? 'auto_stories',
                'active' => false,
                'id'     => $cat->id,
            ];
        }

        return $filters;
    }
}
