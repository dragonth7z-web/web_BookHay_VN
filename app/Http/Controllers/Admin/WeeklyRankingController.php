<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreWeeklyRankingRequest;
use App\Models\SystemLog;
use App\Models\WeeklyRanking;
use App\Repositories\BookRepository;
use App\Repositories\WeeklyRankingRepository;
use Illuminate\Support\Facades\Cache;

class WeeklyRankingController extends Controller
{
    public function __construct(
        private WeeklyRankingRepository $repo,
        private BookRepository $bookRepo,
    ) {
    }

    public function index()
    {
        return view('admin.weekly-rankings.index', [
            'rankings' => $this->repo->paginated(),
        ]);
    }

    public function create()
    {
        return view('admin.weekly-rankings.create', [
            'books' => $this->bookRepo->available(),
        ]);
    }

    public function store(StoreWeeklyRankingRequest $request)
    {
        $ranking = WeeklyRanking::create([
            'week_name' => $request->name_ranking,
            'week_start' => $request->start_date,
            'week_end' => $request->end_date,
        ]);
        $this->repo->syncItems($ranking, $this->buildItemsMap($request));
        SystemLog::ghi(
            type: 'data',
            action: 'create',
            description: 'Tạo bảng xếp hạng tuần mới: ' . $ranking->week_name,
            level: 'info',
            objectType: 'WeeklyRanking',
            objectId: $ranking->id
        );
        Cache::forget('home_ranking_categories_8');
        return redirect()->route('admin.weekly-rankings.index')->with('success', 'Tạo bảng xếp hạng tuần thành công!');
    }

    public function show(WeeklyRanking $weeklyRanking)
    {
        $weeklyRanking->load('items.book');
        return view('admin.weekly-rankings.edit', compact('weeklyRanking'));
    }

    public function edit(WeeklyRanking $weeklyRanking)
    {
        $weeklyRanking->load('items.book');
        return view('admin.weekly-rankings.edit', [
            'weeklyRanking' => $weeklyRanking,
            'books' => $this->bookRepo->available(),
            'selectedByRank' => $weeklyRanking->items->keyBy('rank'),
        ]);
    }

    public function update(StoreWeeklyRankingRequest $request, WeeklyRanking $weeklyRanking)
    {
        $weeklyRanking->update([
            'week_name' => $request->name_ranking,
            'week_start' => $request->start_date,
            'week_end' => $request->end_date,
        ]);
        $this->repo->syncItems($weeklyRanking, $this->buildItemsMap($request));
        SystemLog::ghi(
            type: 'data',
            action: 'update',
            description: 'Cập nhật bảng xếp hạng tuần: ' . $weeklyRanking->week_name,
            level: 'info',
            objectType: 'WeeklyRanking',
            objectId: $weeklyRanking->id
        );
        Cache::forget('home_ranking_categories_8');
        return redirect()->route('admin.weekly-rankings.index')->with('success', 'Cập nhật bảng xếp hạng tuần thành công!');
    }

    public function destroy(WeeklyRanking $weeklyRanking)
    {
        $id = $weeklyRanking->id;
        $name = $weeklyRanking->week_name;
        $weeklyRanking->delete();
        SystemLog::ghi(
            type: 'data',
            action: 'delete',
            description: 'Xóa bảng xếp hạng tuần: ' . $name,
            level: 'warning',
            objectType: 'WeeklyRanking',
            objectId: $id
        );
        Cache::forget('home_ranking_categories_8');
        return redirect()->route('admin.weekly-rankings.index')->with('success', 'Xóa bảng xếp hạng tuần thành công!');
    }

    /** Chuyển items request thành [rank => book_id] */
    private function buildItemsMap(StoreWeeklyRankingRequest $request): array
    {
        return collect($request->input('items', []))
            ->mapWithKeys(fn($x, $rank) => [$rank => $x['book_id'] ?? null])
            ->all();
    }
}
