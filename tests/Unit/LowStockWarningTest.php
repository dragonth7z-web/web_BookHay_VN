<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Book;

/**
 * Feature: ui-ux-redesign
 * Property 10: Low Stock Warning Threshold
 * Validates: Requirements 19.2
 */
class LowStockWarningTest extends TestCase
{
    private const LOW_STOCK_THRESHOLD = 10;

    private function makeBook(array $attributes): Book
    {
        return new Book(array_merge([
            'title' => 'Test Book',
            'slug' => 'test-book',
            'original_price' => 60000,
            'sale_price' => 50000,
            'stock' => 50,
            'sold_count' => 0,
            'rating_avg' => 0,
            'rating_count' => 0,
            'cover_image' => null,
        ], $attributes));
    }

    /** @test */
    public function shows_warning_for_stock_values_below_threshold(): void
    {
        foreach (range(1, self::LOW_STOCK_THRESHOLD - 1) as $stock) {
            $book = $this->makeBook(['stock' => $stock]);

            $view = $this->blade('<x-book-card :book="$book" />', ['book' => $book]);
            $view->assertSee("Còn {$stock} sản phẩm", false);
        }
    }

    /** @test */
    public function does_not_show_warning_for_stock_at_threshold(): void
    {
        $book = $this->makeBook(['stock' => self::LOW_STOCK_THRESHOLD]);

        $view = $this->blade('<x-book-card :book="$book" />', ['book' => $book]);
        $view->assertDontSee("Còn " . self::LOW_STOCK_THRESHOLD . " sản phẩm", false);
    }

    /** @test */
    public function does_not_show_warning_for_stock_above_threshold(): void
    {
        foreach ([20, 50, 100] as $stock) {
            $book = $this->makeBook(['stock' => $stock]);

            $view = $this->blade('<x-book-card :book="$book" />', ['book' => $book]);
            $view->assertDontSee("Còn {$stock} sản phẩm", false);
        }
    }

    /** @test */
    public function does_not_show_warning_for_zero_stock(): void
    {
        $book = $this->makeBook(['stock' => 0]);

        $view = $this->blade('<x-book-card :book="$book" />', ['book' => $book]);
        $view->assertDontSee('Còn 0 sản phẩm', false);
    }
}

