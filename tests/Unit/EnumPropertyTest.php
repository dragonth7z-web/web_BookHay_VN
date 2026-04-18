<?php

namespace Tests\Unit;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use PHPUnit\Framework\TestCase;

/**
 * Property-based tests for Enum behaviour.
 *
 * Validates: Requirements 3.2, 3.3
 */
class EnumPropertyTest extends TestCase
{
    /**
     * Property 4: PaymentMethod::defaultPaymentStatus() is consistent.
     *
     * For every PaymentMethod value:
     *   - COD  → PaymentStatus::Unpaid
     *   - all others → PaymentStatus::Paid
     *
     * **Validates: Requirements 3.2, 3.3**
     */
    public function test_defaultPaymentStatus_returns_unpaid_for_cod_and_paid_for_all_others(): void
    {
        foreach (PaymentMethod::cases() as $method) {
            $status = $method->defaultPaymentStatus();

            if ($method === PaymentMethod::COD) {
                $this->assertSame(
                    PaymentStatus::Unpaid,
                    $status,
                    "Expected COD to return Unpaid, got {$status->value}"
                );
            } else {
                $this->assertSame(
                    PaymentStatus::Paid,
                    $status,
                    "Expected {$method->value} to return Paid, got {$status->value}"
                );
            }
        }
    }

    /**
     * Explicit data-driven variant – one assertion per PaymentMethod case.
     *
     * **Validates: Requirements 3.2, 3.3**
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('paymentMethodProvider')]
    public function test_defaultPaymentStatus_per_case(
        PaymentMethod $method,
        PaymentStatus $expected
    ): void {
        $this->assertSame($expected, $method->defaultPaymentStatus());
    }

    public static function paymentMethodProvider(): array
    {
        return array_map(
            fn(PaymentMethod $m) => [
                $m,
                $m === PaymentMethod::COD ? PaymentStatus::Unpaid : PaymentStatus::Paid,
            ],
            PaymentMethod::cases()
        );
    }
}
