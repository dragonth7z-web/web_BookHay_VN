<?php

namespace App\Repositories;

use App\Models\WeeklyRanking;
use App\Models\WeeklyRankingItem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Contracts\Repositories\WeeklyRankingRepositoryInterface;
use Illuminate\Support\Facades\DB;

class WeeklyRankingRepository implements WeeklyRankingRepositoryInterface
{
    public function getActive(): ?WeeklyRanking
    {
        return WeeklyRanking::active()->with(['items.book'])->first();
    }

    public function paginated(int $perPage = 10): LengthAwarePaginator
    {
        return WeeklyRanking::withCount('items')
            ->orderByDesc('week_start')
            ->paginate($perPage);
    }

    public function syncItems(WeeklyRanking $ranking, array $items): void
    {
        DB::transaction(function () use ($ranking, $items) {
            WeeklyRankingItem::where('id_weekly_ranking', $ranking->id_weekly_ranking)->delete();

            foreach ($items as $rank => $bookId) {
                if (!empty($bookId)) {
                    WeeklyRankingItem::create([
                        'id_weekly_ranking' => $ranking->id_weekly_ranking,
                        'book_id' => $bookId,
                        'ranking' => $rank,
                    ]);
                }
            }
        });
    }
}
