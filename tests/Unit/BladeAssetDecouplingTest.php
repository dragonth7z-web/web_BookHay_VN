<?php

namespace Tests\Unit;

use Tests\TestCase;

/**
 * Validates: Requirements 1.2, 1.3, 2.2, 2.3, 3.3
 *
 * Ensures Blade views have no inline <style> or <script> blocks
 * and correctly reference external CSS/JS asset files.
 */
class BladeAssetDecouplingTest extends TestCase
{
    public function test_banners_blade_has_no_inline_style(): void
    {
        $content = file_get_contents(resource_path('views/home/banners.blade.php'));

        $this->assertStringNotContainsString('<style', $content);
        $this->assertStringContainsString("asset('css/home/banners.css')", $content);
    }

    public function test_flash_sale_blade_has_no_inline_style(): void
    {
        $content = file_get_contents(resource_path('views/home/flash-sale.blade.php'));

        $this->assertStringNotContainsString('<style', $content);
        $this->assertStringContainsString("asset('css/home/flash-sale.css')", $content);
    }

    public function test_header_blade_has_no_inline_style(): void
    {
        $content = file_get_contents(resource_path('views/components/header.blade.php'));

        $this->assertStringNotContainsString('<style', $content);
    }

    public function test_header_blade_has_no_inline_script(): void
    {
        $content = file_get_contents(resource_path('views/components/header.blade.php'));
        $layout  = file_get_contents(resource_path('views/layouts/app.blade.php'));

        $this->assertStringNotContainsString('<script', $content);
        $this->assertStringContainsString("asset('js/components/header.js')", $layout);
    }

    public function test_flash_sale_blade_has_no_inline_script_except_window_var(): void
    {
        $content = file_get_contents(resource_path('views/home/flash-sale.blade.php'));

        // PHP→JS bridge now uses <meta name="flash-sale-end"> instead of window var (AI_RULES compliant)
        $this->assertStringContainsString('flash-sale-end', $content);

        // External JS asset must be referenced
        $this->assertStringContainsString("asset('js/home/flash-sale.js')", $content);

        // Countdown logic must have been extracted to the external JS file
        $this->assertStringNotContainsString('(function ()', $content);
        $this->assertStringNotContainsString('function tick()', $content);

        // No inline script blocks (except the meta tag approach)
        $this->assertStringNotContainsString('setInterval', $content);
    }
}
