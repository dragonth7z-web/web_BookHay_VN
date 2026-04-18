<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Smoke Tests for Weekly Ranking UI Spec
 *
 * Feature: weekly-ranking-ui
 * File-based tests — no database required.
 */
class WeeklyRankingUiSmokeTest extends TestCase
{
    // ─────────────────────────────────────────────────────────────────────────
    // 5.1 — Blade has no inline CSS/JS
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Feature: weekly-ranking-ui, Task 5.1
     *
     * Validates: Requirements 8.1, 8.2
     *
     * Assert the Blade template contains no inline styles, style tags,
     * inline event handlers, or inline script blocks (script without src).
     */
    public function test_blade_has_no_inline_css_or_js(): void
    {
        $bladePath = base_path('resources/views/home/weekly-ranking.blade.php');
        $this->assertFileExists($bladePath, 'weekly-ranking.blade.php does not exist');

        $content = file_get_contents($bladePath);

        $this->assertStringNotContainsString(
            'style="',
            $content,
            'Blade template contains inline style attribute (style="...")'
        );

        $this->assertStringNotContainsString(
            '<style>',
            $content,
            'Blade template contains a <style> tag'
        );

        $this->assertStringNotContainsString(
            'onclick=',
            $content,
            'Blade template contains inline onclick event handler'
        );

        // Assert no inline <script> blocks (script tags without a src attribute)
        // The @push('scripts') block uses <script src="..."> which is acceptable.
        $this->assertDoesNotMatchRegularExpression(
            '/<script(?![^>]*src=)[^>]*>/',
            $content,
            'Blade template contains an inline <script> block (without src attribute)'
        );
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 5.2 — CSS and JS files exist and contain required symbols
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Feature: weekly-ranking-ui, Task 5.2
     * Styles are in app.css (Tailwind project — no separate CSS file)
     *
     * Validates: Requirements 8.1, 8.2, 10.1
     */
    public function test_app_css_contains_rank_number_class(): void
    {
        $cssPath = base_path('resources/css/app.css');
        $this->assertFileExists($cssPath, 'resources/css/app.css does not exist');

        $content = file_get_contents($cssPath);
        $this->assertStringContainsString(
            '.rank-number',
            $content,
            'app.css does not contain .rank-number selector'
        );
    }

    /**
     * Feature: weekly-ranking-ui, Task 5.2
     *
     * Validates: Requirements 8.2, 10.1
     */
    public function test_js_file_exists_and_contains_weekly_ranking_manager(): void
    {
        $jsPath = public_path('js/home/weekly-ranking.js');
        $this->assertFileExists($jsPath, 'public/js/home/weekly-ranking.js does not exist');

        $content = file_get_contents($jsPath);
        $this->assertStringContainsString(
            'WeeklyRankingManager',
            $content,
            'weekly-ranking.js does not contain WeeklyRankingManager'
        );
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 5.3 — data-books attribute structure
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Feature: weekly-ranking-ui, Task 5.3
     *
     * Validates: Requirements 7.3
     *
     * Assert the Blade template uses data-books with json_encode and JSON_HEX_TAG
     * for safe JSON embedding.
     */
    public function test_blade_data_books_uses_json_encode_with_hex_tag(): void
    {
        $bladePath = base_path('resources/views/home/weekly-ranking.blade.php');
        $this->assertFileExists($bladePath, 'weekly-ranking.blade.php does not exist');

        $content = file_get_contents($bladePath);

        $this->assertStringContainsString(
            'data-books=',
            $content,
            'Blade template does not contain data-books attribute'
        );

        $this->assertStringContainsString(
            'json_encode',
            $content,
            'Blade template does not use json_encode for data-books'
        );

        $this->assertStringContainsString(
            'JSON_HEX_TAG',
            $content,
            'Blade template does not use JSON_HEX_TAG flag in json_encode'
        );
    }
}
