<?php

namespace App\Contracts\Repositories;

interface CategoryRepositoryInterface
{
    public function getParentCategoriesWithChildren();
    public function getParentCategoriesOptions(?int $excludeId = null);
}
