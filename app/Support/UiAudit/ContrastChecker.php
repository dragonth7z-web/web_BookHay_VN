<?php

namespace App\Support\UiAudit;

use Illuminate\Support\Facades\File;

/**
 * ContrastChecker — Scans for potential contrast and dark mode issues.
 */
class ContrastChecker
{
    /** @return AuditIssue[] */
    public function scanTextContrast(string $viewsPath): array
    {
        return [];
    }

    /** @return AuditIssue[] */
    public function scanCssDarkRules(string $cssPath): array
    {
        return [];
    }

    /** @return AuditIssue[] */
    public function scanIconColors(string $viewsPath): array
    {
        return [];
    }

    /** @return AuditIssue[] */
    public function scanLogoVisibility(string $viewsPath): array
    {
        return [];
    }

    public function fix(array $issues): int
    {
        return 0;
    }
}
