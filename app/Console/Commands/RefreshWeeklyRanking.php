<?php

namespace App\Console\Commands;

use App\Models\Book;
use App\Models\WeeklyRanking;
use App\Models\WeeklyRankingItem;
use App\Enums\BookStatus;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class RefreshWeeklyRanking extends Command
{
    protected $signature = 'ranking:refresh {--limit=10 : Number of books in ranking}';
    protected $description = 'Refresh weekly ranking with top selling books for the current week';

    public function handle(): int
    {
        $limit = (int) $this->option('limit');

        // Remove expired rankings
        $deleted = WeeklyRanking::whereDate('week_end', '<', now()->toDateString())->delete();
        if ($deleted) {
            $this->info("Removed {$deleted} expired ranking(s).");
        }

        // Skip if active ranking already exists
        if (WeeklyRanking::active()->exists()) {
            $this->info('Active weekly ranking already exists. Nothing to do.');
            return self::SUCCESS;
        }

        // Get top selling books
        $books = Book::where('status', BookStatus::InStock)
            ->where('original_price', '>', 0)
            ->orderByDesc('sold_count')
            ->limit($limit)
            ->get();

        if ($books->isEmpty()) {
            $this->warn('No books available for ranking.');
            return self::FAILURE;
        }

        $weekNumber = now()->weekOfYear;
        $year = now()->year;

        $ranking = WeeklyRanking::create([
            'week_name' => "Bảng Xếp Hạng Tuần {$weekNumber} - {$year}",
            'week_start' => now()->startOfWeek(),
            'week_end' => now()->endOfWeek(),
        ]);

        foreach ($books as $index => $book) {
            WeeklyRankingItem::create([
                'weekly_ranking_id' => $ranking->id,
                'book_id' => $book->id,
                'rank' => $index + 1,
            ]);
        }

        // Clear related caches
        Cache::forget('home_ranking_categories_8');

        $this->info("Created '{$ranking->week_name}' with {$books->count()} books.");
        return self::SUCCESS;
    }
}
