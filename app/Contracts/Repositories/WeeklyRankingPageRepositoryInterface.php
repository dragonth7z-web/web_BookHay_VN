<?php

namespace App\Contracts\Repositories;

use App\Models\WeeklyRanking;
use Illuminate\Support\Collection;

interface WeeklyRankingPageRepositoryInterface
{
    /**
     * Get the currently active weekly ranking with all items and books.
     */
    public function getActiveRanking(): ?WeeklyRanking;

    /**
     * Get the top-ranked book (rank = 1) from the active ranking.
     */
    public function getTopBook(): ?object;

    /**
     * Get ranked books from the active ranking, excluding rank 1.
     *
     * @param  int  $limit
     */
    public function getRankedBooks(int $limit = 10): Collection;

    /**
     * Get insight stats for the sidebar (new titles, active curator, discussion count).
     */
    public function getInsightStats(): array;

    /**
     * Get trending hashtags derived from categories and book titles.
     *
     * @param  int  $limit
     */
    public function getTrendingTags(int $limit = 8): Collection;
}
