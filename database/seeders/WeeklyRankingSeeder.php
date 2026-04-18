<?php

namespace Database\Seeders;

use App\Models\WeeklyRanking;
use App\Models\WeeklyRankingItem;
use App\Models\Book;
use Illuminate\Database\Seeder;

class WeeklyRankingSeeder extends Seeder
{
    public function run(): void
    {
        // Remove expired rankings, keep only current week
        WeeklyRanking::whereDate('week_end', '<', now()->toDateString())->delete();

        // Skip if an active ranking already exists
        $existing = WeeklyRanking::active()->first();
        if ($existing) {
            $this->command->info("Active weekly ranking already exists: '{$existing->week_name}'. Skipping...");
            return;
        }

        // Get some books for the weekly ranking
        $books = Book::where('status', 'in_stock')
                    ->where('original_price', '>', 0)
                    ->orderByDesc('sold_count')
                    ->limit(10)
                    ->get();

        if ($books->isEmpty()) {
            $this->command->warn('No books found for weekly ranking. Skipping...');
            return;
        }

        $weekNumber = now()->weekOfYear;
        $year = now()->year;

        // Create an active weekly ranking for the current week
        $weeklyRanking = WeeklyRanking::create([
            'week_name' => "Bảng Xếp Hạng Tuần {$weekNumber} - {$year}",
            'week_start' => now()->startOfWeek(),
            'week_end' => now()->endOfWeek(),
        ]);

        // Create weekly ranking items
        foreach ($books as $index => $book) {
            WeeklyRankingItem::create([
                'weekly_ranking_id' => $weeklyRanking->id,
                'book_id' => $book->id,
                'rank' => $index + 1,
            ]);
        }

        $this->command->info("Created weekly ranking '{$weeklyRanking->week_name}' with {$books->count()} books");
    }
}
