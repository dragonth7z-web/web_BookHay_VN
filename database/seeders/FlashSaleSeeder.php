<?php

namespace Database\Seeders;

use App\Models\FlashSale;
use App\Models\FlashSaleItem;
use App\Models\Book;
use Illuminate\Database\Seeder;

class FlashSaleSeeder extends Seeder
{
    public function run(): void
    {
        // Get some books for the flash sale
        $books = Book::where('status', 'in_stock')
                    ->where('original_price', '>', 0)
                    ->limit(10)
                    ->get();

        if ($books->isEmpty()) {
            $this->command->warn('No books found for flash sale. Skipping...');
            return;
        }

        // Create an active flash sale that ends in 24 hours
        $flashSale = FlashSale::create([
            'name' => 'Flash Sale Đặc Biệt - Cuối Tuần',
            'start_date' => now()->subHours(2), // Started 2 hours ago
            'end_date' => now()->addHours(22), // Ends in 22 hours (24 hours total)
        ]);

        // Create flash sale items with discounted prices
        foreach ($books as $index => $book) {
            $discountPercent = rand(20, 70); // Random discount between 20-70%
            $flashPrice = (int) ($book->original_price * (1 - $discountPercent / 100));

            FlashSaleItem::create([
                'flash_sale_id' => $flashSale->id,
                'book_id' => $book->id,
                'flash_price' => max($flashPrice, 10000), // Minimum 10,000 VND
                'display_order' => $index + 1,
            ]);
        }

        $this->command->info("Created flash sale '{$flashSale->name}' with {$books->count()} items");
        $this->command->info("Flash sale ends at: {$flashSale->end_date}");
    }
}
