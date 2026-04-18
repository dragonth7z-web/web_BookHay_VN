<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Book;

/**
 * Feature: ui-ux-redesign
 * Property 5: Book Card Discount Display
 * Validates: Requirements 8.5, 8.8
 */
class BookCardDiscountTest extends TestCase
{
    private function makeBook(array $attributes): Book
    {
        return new Book(array_merge([
            'title' => 'Test Book',
            'slug' => 'test-book',
            'original_price' => 100000,
            'sale_price' => 100000,
            'stock' => 50,
            'sold_count' => 0,
            'rating_avg' => 0,
            'rating_count' => 0,
            'cover_image' => null,
        ], $attributes));
    }

    /** @test */
    public function shows_discount_badge_when_original_price_greater_than_sale_price(): void
    {
        $book = $this->makeBook([
            'original_price' => 100000,
            'sale_price' => 70000,
        ]);

        $view = $this->blade('<x-book-card :book="$book" />', ['book' => $book]);
        $view->assertSee('Giảm 30%', false);
    }

    /** @test */
    public function shows_strikethrough_original_price_when_discounted(): void
    {
        $book = $this->makeBook([
            'original_price' => 100000,
            'sale_price' => 70000,
        ]);

        $view = $this->blade('<x-book-card :book="$book" />', ['book' => $book]);
        // Original price should appear (formatted)
        $view->assertSee('100.000', false);
    }

    /** @test */
    public function does_not_show_discount_badge_when_no_discount(): void
    {
        $book = $this->makeBook([
            'original_price' => 100000,
            'sale_price' => 100000,
        ]);

        $view = $this->blade('<x-book-card :book="$book" />', ['book' => $book]);
        $view->assertDontSee('Giảm', false);
    }

    /** @test */
    public function calculates_correct_discount_percentage(): void
    {
        $testCases = [
            ['original' => 200000, 'sale' => 150000, 'expected' => 25],
            ['original' => 80000,  'sale' => 60000,  'expected' => 25],
            ['original' => 120000, 'sale' => 100000, 'expected' => 17],
        ];

        foreach ($testCases as $case) {
            $book = $this->makeBook([
                'original_price' => $case['original'],
                'sale_price' => $case['sale'],
            ]);

            $view = $this->blade('<x-book-card :book="$book" />', ['book' => $book]);
            $view->assertSee("Giảm {$case['expected']}%", false);
        }
    }
}

