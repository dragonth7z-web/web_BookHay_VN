<?php

namespace Tests\Unit;

use Tests\TestCase;

/**
 * Feature: admin-ui-upgrade, Property 1: Trend indicator phản ánh đúng dấu của giá trị
 *
 * Validates: Requirements 4.4, 4.5
 */
class AdminTrendIndicatorTest extends TestCase
{
    /**
     * Property 1: Trend indicator phản ánh đúng dấu của giá trị
     *
     * For any numeric trend value:
     *   - trend >= 0 → renders text-green-600 + trending_up icon
     *   - trend < 0  → renders text-red-600  + trending_down icon
     *
     * Validates: Requirements 4.4, 4.5
     *
     * @dataProvider trendValueProvider
     */
    public function test_trend_indicator_renders_correct_color_and_icon(int $trendValue): void
    {
        $html = view('admin.partials.stat-card', [
            'label'      => 'Test Label',
            'value'      => '100',
            'trend'      => $trendValue,
            'icon'       => 'payments',
            'canvasId'   => 'sparklineTest',
            'footerText' => 'Test footer',
            'footerLink' => '#',
        ])->render();

        if ($trendValue >= 0) {
            $this->assertStringContainsString('text-green-600', $html, "Expected text-green-600 for trend={$trendValue}");
            $this->assertStringContainsString('trending_up', $html, "Expected trending_up icon for trend={$trendValue}");
            $this->assertStringNotContainsString('text-red-600', $html, "Did not expect text-red-600 for trend={$trendValue}");
            $this->assertStringNotContainsString('trending_down', $html, "Did not expect trending_down icon for trend={$trendValue}");
        } else {
            $this->assertStringContainsString('text-red-600', $html, "Expected text-red-600 for trend={$trendValue}");
            $this->assertStringContainsString('trending_down', $html, "Expected trending_down icon for trend={$trendValue}");
            $this->assertStringNotContainsString('text-green-600', $html, "Did not expect text-green-600 for trend={$trendValue}");
            $this->assertStringNotContainsString('trending_up', $html, "Did not expect trending_up icon for trend={$trendValue}");
        }
    }

    /**
     * Simulate 101 iterations with values from -50 to +50.
     */
    public static function trendValueProvider(): array
    {
        $cases = [];
        foreach (range(-50, 50) as $v) {
            $cases["trend_{$v}"] = [$v];
        }
        return $cases;
    }
}
