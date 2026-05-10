<?php

namespace App\Http\Controllers;

use App\Services\WeeklyRankingPageService;

class WeeklyRankingPageController extends Controller
{
    public function __construct(
        private WeeklyRankingPageService $rankingPageService
    ) {}

    /**
     * Display the full weekly ranking page.
     */
    public function index()
    {
        $activeRanking = $this->rankingPageService->getActiveRanking();
        $topBook       = $this->rankingPageService->getTopBook();
        $rankedBooks   = $this->rankingPageService->getRankedBooks(10);
        $insightStats  = $this->rankingPageService->getInsightStats();
        $trendingTags  = $this->rankingPageService->getTrendingTags(8);

        return view('pages.weekly-ranking', compact(
            'activeRanking',
            'topBook',
            'rankedBooks',
            'insightStats',
            'trendingTags',
        ));
    }
}
