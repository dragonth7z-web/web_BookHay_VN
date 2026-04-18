<?php

namespace App\Contracts\Repositories;

use App\Models\WeeklyRanking;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface WeeklyRankingRepositoryInterface
{
    public function getActive(): ?WeeklyRanking;
    public function paginated(int $perPage = 10): LengthAwarePaginator;
    public function syncItems(WeeklyRanking $ranking, array $items): void;
}
