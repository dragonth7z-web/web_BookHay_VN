<?php

namespace Tests\Unit;

use App\Enums\OrderStatus;
use App\Models\Book;
use Tests\TestCase;

/**
 * Property-based tests for Anonymous Blade Components.
 *
 * **Validates: Requirements 9.2, 14.1, 14.4**
 */
class AnonymousComponentTest extends TestCase
{
    // =========================================================================
    // Property 7: x-status-badge ánh xạ đầy đủ mọi OrderStatus
    //
    // For any $status in OrderStatus enum with $type = 'order':
    //   - Rendered HTML must contain the corresponding Vietnamese label
    //   - Rendered HTML must NOT contain "Không xác định"
    //
    // **Validates: Requirements 9.2**
    // =========================================================================

    /**
     * Feature: decoupling-refactor, Property 7: x-status-badge ánh xạ đầy đủ mọi OrderStatus
     *
     * **Validates: Requirements 9.2**
     */
    public function test_status_badge_maps_all_order_statuses(): void
    {
        $expectedLabels = [
            OrderStatus::Pending->value   => 'Chờ xác nhận',
            OrderStatus::Confirmed->value => 'Đã xác nhận',
            OrderStatus::Shipping->value  => 'Đang giao',
            OrderStatus::Delivered->value => 'Đã giao',
            OrderStatus::Completed->value => 'Hoàn thành',
            OrderStatus::Cancelled->value => 'Đã hủy',
            OrderStatus::Returned->value  => 'Đã hoàn trả',
        ];

        foreach (OrderStatus::cases() as $status) {
            $view = $this->blade(
                '<x-status-badge :status="$status" type="order" />',
                ['status' => $status]
            );

            $expectedLabel = $expectedLabels[$status->value];

            $view->assertSee($expectedLabel, false);
            $view->assertDontSee('Không xác định', false);
        }
    }

    // =========================================================================
    // Property 6: x-book-card render không trigger thêm query (simplified)
    //
    // For a Book object with full attributes (no DB needed):
    //   - Rendered HTML must contain the book's title
    //   - Rendered HTML must contain loading="lazy" (or loading="eager" when eager=true)
    //
    // **Validates: Requirements 14.1, 14.4**
    // =========================================================================

    /**
     * Feature: decoupling-refactor, Property 6: x-book-card render không trigger thêm query
     *
     * **Validates: Requirements 14.1, 14.4**
     */
    public function test_book_card_renders_title_and_lazy_loading(): void
    {
        $titles = [
            'Lập Trình PHP Nâng Cao',
            'Design Patterns',
            'Clean Code',
            'The Pragmatic Programmer',
            'Refactoring',
        ];

        foreach ($titles as $title) {
            $book = new Book([
                'title'          => $title,
                'slug'           => \Illuminate\Support\Str::slug($title),
                'sale_price'     => 150000,
                'original_price' => 200000,
                'cover_image'    => null,
                'rating_avg'     => 4.5,
                'rating_count'   => 100,
                'is_featured'    => false,
            ]);

            // Test lazy loading (default)
            $view = $this->blade(
                '<x-book-card :book="$book" />',
                ['book' => $book]
            );

            $view->assertSee($title, false);
            $view->assertSee('loading="lazy"', false);

            // Test eager loading (when eager=true)
            $viewEager = $this->blade(
                '<x-book-card :book="$book" :eager="true" />',
                ['book' => $book]
            );

            $viewEager->assertSee($title, false);
            $viewEager->assertSee('loading="eager"', false);
        }
    }
}
