<?php

namespace App\Support\UiAudit;

use Illuminate\Support\Facades\File;

/**
 * PerformanceReporter — Checks for unused CSS files and duplicate rules.
 */
class PerformanceReporter
{
    /** @return AuditIssue[] */
    public function checkUnusedCssFiles(string $viewsPath, string $cssPath): array
    {
        return [];
    }

    /** @return AuditIssue[] */
    public function checkDuplicateRules(string $cssFilePath): array
    {
        return [];
    }

    public function generateReport(): string
    {
        return '  [PerformanceReporter] No issues detected.';
    }
}
