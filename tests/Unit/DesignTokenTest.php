<?php

namespace Tests\Unit;

use Tests\TestCase;

/**
 * Feature: ui-ux-redesign
 * Property 1: Design Token Completeness
 * Validates: Requirements 1.6, 1.11, 1.12, 1.13
 */
class DesignTokenTest extends TestCase
{
    private string $appCssPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->appCssPath = resource_path('css/app.css');
    }

    /** @test */
    public function app_css_contains_all_required_brand_color_tokens(): void
    {
        $content = file_get_contents($this->appCssPath);
        $this->assertNotFalse($content, 'app.css should be readable');

        $requiredColors = [
            '--color-primary',
            '--color-primary-hover',
            '--color-primary-soft',
            '--color-background',
            '--color-secondary',
            '--color-pastel',
            '--color-gold',
        ];

        foreach ($requiredColors as $token) {
            $this->assertStringContainsString(
                $token,
                $content,
                "app.css must define CSS custom property: {$token}"
            );
        }
    }

    /** @test */
    public function app_css_contains_all_required_semantic_color_tokens(): void
    {
        $content = file_get_contents($this->appCssPath);

        $semanticTokens = [
            '--color-success',
            '--color-warning',
            '--color-danger',
        ];

        foreach ($semanticTokens as $token) {
            $this->assertStringContainsString(
                $token,
                $content,
                "app.css must define semantic token: {$token}"
            );
        }
    }

    /** @test */
    public function app_css_contains_all_required_shadow_tokens(): void
    {
        $content = file_get_contents($this->appCssPath);

        $shadowTokens = [
            '--shadow-sm',
            '--shadow-md',
            '--shadow-lg',
            '--shadow-brand',
        ];

        foreach ($shadowTokens as $token) {
            $this->assertStringContainsString(
                $token,
                $content,
                "app.css must define shadow token: {$token}"
            );
        }
    }

    /** @test */
    public function app_css_contains_all_required_typography_scale_tokens(): void
    {
        $content = file_get_contents($this->appCssPath);

        $typographyTokens = [
            '--text-xs',
            '--text-sm',
            '--text-md',
            '--text-lg',
            '--text-xl',
            '--text-2xl',
        ];

        foreach ($typographyTokens as $token) {
            $this->assertStringContainsString(
                $token,
                $content,
                "app.css must define typography token: {$token}"
            );
        }
    }

    /** @test */
    public function app_css_contains_font_family_tokens(): void
    {
        $content = file_get_contents($this->appCssPath);

        $fontTokens = [
            '--font-heading',
            '--font-body',
            '--font-number',
        ];

        foreach ($fontTokens as $token) {
            $this->assertStringContainsString(
                $token,
                $content,
                "app.css must define font family token: {$token}"
            );
        }
    }
}
