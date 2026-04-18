<?php

namespace App\Repositories;

use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Models\Category;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(private Category $model)
    {
    }

    public function getParentCategoriesWithChildren()
    {
        return $this->model->whereNull('parent_id')
            ->with('children')
            ->orderBy('sort_order')
            ->get();
    }

    public function getParentCategoriesOptions(?int $excludeId = null)
    {
        $query = $this->model->whereNull('parent_id')->orderBy('name');
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->get();
    }
}
