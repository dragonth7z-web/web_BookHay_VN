<?php

namespace Tests\Unit;

use Tests\TestCase;

class AdminDesignTokenTest extends TestCase
{
    public function test_admin_css_tokens_defined(): void
    {
        $css = file_get_contents(base_path('resources/css/app.css'));

        $this->assertStringContainsString('--admin-sidebar-width', $css);
        $this->assertStringContainsString('--admin-sidebar-collapsed-width', $css);
        $this->assertStringContainsString('--admin-topbar-height', $css);
        $this->assertStringContainsString('--admin-surface', $css);
        $this->assertStringContainsString('--admin-surface-muted', $css);
        $this->assertStringContainsString('--admin-border', $css);
        $this->assertStringContainsString('--admin-text-primary', $css);
        $this->assertStringContainsString('--admin-text-muted', $css);
    }

    public function test_admin_component_classes_defined(): void
    {
        $css = file_get_contents(base_path('resources/css/app.css'));

        foreach ([
            '.admin-card',
            '.admin-table',
            '.admin-btn-primary',
            '.admin-btn-secondary',
            '.admin-input',
            '.stat-card',
            '.quick-action-btn',
        ] as $class) {
            $this->assertStringContainsString($class, $css, "Expected class '{$class}' to be defined in app.css");
        }
    }

    public function test_admin_dark_mode_overrides_defined(): void
    {
        $css = file_get_contents(base_path('resources/css/app.css'));

        $darkPos = strpos($css, '.dark {');
        $this->assertNotFalse($darkPos, "Expected a .dark { block in app.css");

        $darkBlock = substr($css, $darkPos);
        $this->assertStringContainsString('--admin-surface', $darkBlock, "Expected --admin-surface override inside .dark { block");
    }
}
