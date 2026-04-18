<?php

namespace Tests\Unit;

use App\Repositories\BookRepository;
use App\Services\CheckoutService;
use PHPUnit\Framework\TestCase;

/**
 * Property 7: CheckoutService::calculateShippingFee() miễn phí khi đủ điều kiện
 * Validates: Requirements 5.1
 */
class CheckoutServiceShippingFeeTest extends TestCase
{
    private CheckoutService $service;

    protected function setUp(): void
    {
        parent::setUp();
        // BookRepository is only used in placeOrder, not calculateShippingFee
        // We can pass null safely since we only test calculateShippingFee
        $bookRepo = $this->createMock(BookRepository::class);
        $this->service = new CheckoutService($bookRepo);
    }

    /**
     * Property 7: For any subtotal >= 200000, shipping fee must be 0.
     * Validates: Requirements 5.1
     */
    public function test_shipping_fee_is_free_for_many_subtotals_at_or_above_threshold(): void
    {
        // Property-based: iterate over many systematic values >= 200000
        $testValues = [];

        // Boundary value
        $testValues[] = 200000;

        // Systematic increments above threshold
        for ($i = 1; $i <= 50; $i++) {
            $testValues[] = 200000 + ($i * 10000);
        }

        // Large values
        $testValues[] = 500000;
        $testValues[] = 1000000;
        $testValues[] = 9999999;

        // Random values >= 200000
        mt_srand(42);
        for ($i = 0; $i < 50; $i++) {
            $testValues[] = mt_rand(200000, 10000000);
        }

        foreach ($testValues as $subtotal) {
            $fee = $this->service->calculateShippingFee((float) $subtotal);
            $this->assertSame(
                0,
                $fee,
                "Expected shipping fee 0 for subtotal={$subtotal}, got {$fee}"
            );
        }
    }

    /**
     * For subtotal < 200000, shipping fee must be positive.
     * Validates: Requirements 5.1
     */
    public function test_shipping_fee_is_positive_for_subtotals_below_threshold(): void
    {
        $testValues = [];

        // Boundary: just below threshold
        $testValues[] = 199999;

        // Systematic values below threshold
        for ($i = 1; $i <= 20; $i++) {
            $testValues[] = $i * 5000;
        }

        // Zero and small values
        $testValues[] = 0;
        $testValues[] = 1;
        $testValues[] = 100000;

        // Random values < 200000
        mt_srand(42);
        for ($i = 0; $i < 30; $i++) {
            $testValues[] = mt_rand(0, 199999);
        }

        foreach ($testValues as $subtotal) {
            $fee = $this->service->calculateShippingFee((float) $subtotal);
            $this->assertGreaterThan(
                0,
                $fee,
                "Expected positive shipping fee for subtotal={$subtotal}, got {$fee}"
            );
        }
    }

    /**
     * Boundary test: exactly 200000 returns 0, exactly 199999 returns > 0.
     * Validates: Requirements 5.1
     */
    public function test_boundary_at_free_shipping_threshold(): void
    {
        $this->assertSame(0, $this->service->calculateShippingFee(200000.0));
        $this->assertGreaterThan(0, $this->service->calculateShippingFee(199999.0));
    }
}
