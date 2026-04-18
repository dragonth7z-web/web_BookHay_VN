<?php

namespace Tests\Unit;

use App\Enums\CouponType;
use App\Models\Coupon;
use App\Services\CouponService;
use PHPUnit\Framework\TestCase;

/**
 * Property-based tests for CouponService::calculateDiscount().
 *
 * **Validates: Requirements 5.10, 5.11**
 */
class CouponServiceCalculateDiscountTest extends TestCase
{
    private CouponService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CouponService();
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function makeCoupon(CouponType $type, float $value, ?float $maxDiscount = null): Coupon
    {
        $coupon = new Coupon();
        $coupon->type         = $type;
        $coupon->value        = $value;
        $coupon->max_discount = $maxDiscount;
        return $coupon;
    }

    // -------------------------------------------------------------------------
    // Property 5 – percentage formula: result == min(value/100 * orderAmount, max_discount)
    // **Validates: Requirements 5.10**
    // -------------------------------------------------------------------------

    /**
     * Property 5: For every valid (value, max_discount, orderAmount) triple the
     * percentage discount equals min(value / 100 * orderAmount, max_discount).
     *
     * **Validates: Requirements 5.10**
     */
    public function test_percentage_discount_formula_holds_for_many_inputs(): void
    {
        $cases = $this->generatePercentageCases();

        foreach ($cases as [$value, $maxDiscount, $orderAmount]) {
            $coupon   = $this->makeCoupon(CouponType::Percentage, $value, $maxDiscount);
            $result   = $this->service->calculateDiscount($coupon, $orderAmount);
            $expected = min($value / 100 * $orderAmount, $maxDiscount);

            $this->assertEqualsWithDelta(
                $expected,
                $result,
                1e-9,
                "Percentage formula failed for value={$value}, max_discount={$maxDiscount}, orderAmount={$orderAmount}"
            );
        }
    }

    /**
     * Property 5 edge case: when computed percentage exceeds max_discount,
     * result is capped at max_discount.
     *
     * **Validates: Requirements 5.10**
     */
    public function test_percentage_discount_is_capped_at_max_discount(): void
    {
        // 50% of 1 000 000 = 500 000, but max_discount = 100 000
        $coupon = $this->makeCoupon(CouponType::Percentage, 50, 100_000);
        $result = $this->service->calculateDiscount($coupon, 1_000_000);

        $this->assertEqualsWithDelta(100_000.0, $result, 1e-9);
    }

    /**
     * Property 5 edge case: when computed percentage is below max_discount,
     * result equals the computed percentage.
     *
     * **Validates: Requirements 5.10**
     */
    public function test_percentage_discount_below_max_discount_is_not_capped(): void
    {
        // 10% of 50 000 = 5 000, max_discount = 100 000
        $coupon = $this->makeCoupon(CouponType::Percentage, 10, 100_000);
        $result = $this->service->calculateDiscount($coupon, 50_000);

        $this->assertEqualsWithDelta(5_000.0, $result, 1e-9);
    }

    // -------------------------------------------------------------------------
    // Property 6 – fixed_amount formula: result == min(value, orderAmount)
    // **Validates: Requirements 5.11**
    // -------------------------------------------------------------------------

    /**
     * Property 6: For every valid (value, orderAmount) pair the fixed-amount
     * discount equals min(value, orderAmount).
     *
     * **Validates: Requirements 5.11**
     */
    public function test_fixed_amount_discount_formula_holds_for_many_inputs(): void
    {
        $cases = $this->generateFixedAmountCases();

        foreach ($cases as [$value, $orderAmount]) {
            $coupon   = $this->makeCoupon(CouponType::FixedAmount, $value);
            $result   = $this->service->calculateDiscount($coupon, $orderAmount);
            $expected = min($value, $orderAmount);

            $this->assertEqualsWithDelta(
                $expected,
                $result,
                1e-9,
                "Fixed-amount formula failed for value={$value}, orderAmount={$orderAmount}"
            );
        }
    }

    /**
     * Property 6 edge case: when fixed discount value exceeds orderAmount,
     * result is capped at orderAmount (cannot discount more than the order).
     *
     * **Validates: Requirements 5.11**
     */
    public function test_fixed_amount_discount_capped_at_order_amount(): void
    {
        // value = 500 000, orderAmount = 100 000 → discount = 100 000
        $coupon = $this->makeCoupon(CouponType::FixedAmount, 500_000);
        $result = $this->service->calculateDiscount($coupon, 100_000);

        $this->assertEqualsWithDelta(100_000.0, $result, 1e-9);
    }

    /**
     * Property 6 edge case: when fixed discount value is less than orderAmount,
     * result equals the coupon value.
     *
     * **Validates: Requirements 5.11**
     */
    public function test_fixed_amount_discount_equals_value_when_below_order_amount(): void
    {
        // value = 50 000, orderAmount = 300 000 → discount = 50 000
        $coupon = $this->makeCoupon(CouponType::FixedAmount, 50_000);
        $result = $this->service->calculateDiscount($coupon, 300_000);

        $this->assertEqualsWithDelta(50_000.0, $result, 1e-9);
    }

    // -------------------------------------------------------------------------
    // Input generators
    // -------------------------------------------------------------------------

    /**
     * Generate a broad set of (value %, max_discount, orderAmount) triples.
     *
     * @return array<array{float, float, float}>
     */
    private function generatePercentageCases(): array
    {
        $percentValues  = [1, 5, 10, 15, 20, 25, 30, 50, 75, 100];
        $maxDiscounts   = [10_000, 50_000, 100_000, 200_000, 500_000, 1_000_000];
        $orderAmounts   = [50_000, 100_000, 200_000, 500_000, 1_000_000, 5_000_000];

        $cases = [];
        foreach ($percentValues as $pct) {
            foreach ($maxDiscounts as $max) {
                foreach ($orderAmounts as $order) {
                    $cases[] = [(float) $pct, (float) $max, (float) $order];
                }
            }
        }
        return $cases;
    }

    /**
     * Generate a broad set of (value, orderAmount) pairs for fixed-amount coupons.
     *
     * @return array<array{float, float}>
     */
    private function generateFixedAmountCases(): array
    {
        $values       = [10_000, 20_000, 50_000, 100_000, 200_000, 500_000];
        $orderAmounts = [30_000, 50_000, 100_000, 150_000, 300_000, 500_000, 1_000_000];

        $cases = [];
        foreach ($values as $v) {
            foreach ($orderAmounts as $order) {
                $cases[] = [(float) $v, (float) $order];
            }
        }
        return $cases;
    }
}
