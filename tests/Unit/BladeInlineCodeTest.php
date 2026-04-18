<?php

namespace Tests\Unit;

use Tests\TestCase;

/**
 * Feature: ui-ux-redesign
 * Property 8: No Inline CSS/JS in Blade Templates
 * Validates: Requirements 18.1, 18.2
 */
class BladeInlineCodeTest extends TestCase
{
    /** @test */
    public function home_blade_files_have_no_inline_style_tags(): void
    {
        $bladeFiles = glob(resource_path('views/home/*.blade.php'));
        $this->assertNotEmpty($bladeFiles, 'Should find home blade files');

        foreach ($bladeFiles as $file) {
            $content = file_get_contents($file);
            $filename = basename($file);

            // Allow <link rel="stylesheet"> but not <style> tags
            $this->assertDoesNotMatchRegularExpression(
                '/<style[\s>]/i',
                $content,
                "File {$filename} must not contain inline <style> tags"
            );
        }
    }

    /** @test */
    public function home_blade_files_have_no_inline_script_code(): void
    {
        $bladeFiles = glob(resource_path('views/home/*.blade.php'));
        $this->assertNotEmpty($bladeFiles, 'Should find home blade files');

        foreach ($bladeFiles as $file) {
            $content = file_get_contents($file);
            $filename = basename($file);

            // Allow <script src="..."> but not <script> with inline code
            // Pattern: <script> tag without src attribute that contains actual code
            $this->assertDoesNotMatchRegularExpression(
                '/<script(?![^>]*\bsrc\b)[^>]*>\s*[^\s<]/',
                $content,
                "File {$filename} must not contain inline <script> code blocks"
            );
        }
    }

    /** @test */
    public function component_blade_files_have_no_inline_style_tags(): void
    {
        $bladeFiles = glob(resource_path('views/components/*.blade.php'));
        $this->assertNotEmpty($bladeFiles, 'Should find component blade files');

        // Exclude files that are known to have legitimate style tags (none expected)
        foreach ($bladeFiles as $file) {
            $content = file_get_contents($file);
            $filename = basename($file);

            $this->assertDoesNotMatchRegularExpression(
                '/<style[\s>]/i',
                $content,
                "Component {$filename} must not contain inline <style> tags"
            );
        }
    }
}
