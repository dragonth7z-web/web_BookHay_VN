<?php

namespace App\Contracts\Repositories;

use Illuminate\Support\Collection as EloquentCollection;

interface HomeRepositoryInterface
{
    public function getFlashSaleBooks(): array;
    public function getWeeklyRankings(int $limit = 15): EloquentCollection;
    public function getFeaturedBooks(): EloquentCollection;
    public function getLatestBooks(int $limit = 12): EloquentCollection;
    public function getCombos(int $take = 12): EloquentCollection;
    public function getSeries(int $take = 4): EloquentCollection;
    public function getCollections(int $take = 6): EloquentCollection;
    public function getPartners(): EloquentCollection;
    public function getRecommendedBooks(int $take = 15): EloquentCollection;
    public function getBanners(string $position, ?int $take = null): EloquentCollection;
    public function getSidebarCategories(int $take = 12): EloquentCollection;
    public function getVouchers(int $take = 3): EloquentCollection;
    public function getYoungAuthorsBooks(int $take = 4): EloquentCollection;
    public function getConfigs(): EloquentCollection;
    public function getQuickFeatures(): array;
    public function getTrendingKeywords(int $limit = 5): EloquentCollection;
    public function getRankingCategories(int $limit = 8): EloquentCollection;
    public function getTopBooksByCategory(mixed $categoryId, int $limit = 5): EloquentCollection;
    public function getTrendingBooks(string $period = 'week', mixed $categoryId = null, int $limit = 10): EloquentCollection;
}
