<?php

namespace App\Services;

use App\Contracts\Repositories\WeeklyRankingPageRepositoryInterface;
use App\Models\WeeklyRanking;
use Illuminate\Support\Collection;

class WeeklyRankingPageService
{
    public function __construct(
        private WeeklyRankingPageRepositoryInterface $rankingPageRepo
    ) {}

    /**
     * Get the active weekly ranking model.
     */
    public function getActiveRanking(): ?WeeklyRanking
    {
        return $this->rankingPageRepo->getActiveRanking();
    }

    /**
     * Get the #1 ranked book.
     */
    public function getTopBook(): ?object
    {
        return $this->rankingPageRepo->getTopBook();
    }

    /**
     * Get all ranked books after rank 1.
     */
    public function getRankedBooks(int $limit = 10): Collection
    {
        return $this->rankingPageRepo->getRankedBooks($limit);
    }

    /**
     * Get sidebar insight stats.
     */
    public function getInsightStats(): array
    {
        return $this->rankingPageRepo->getInsightStats();
    }

    /**
     * Get trending hashtags for the sidebar.
     */
    public function getTrendingTags(int $limit = 8): Collection
    {
        return $this->rankingPageRepo->getTrendingTags($limit);
    }
}
