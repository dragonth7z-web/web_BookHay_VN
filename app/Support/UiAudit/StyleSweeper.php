<?php

namespace App\Support\UiAudit;

use Illuminate\Support\Facades\File;

/**
 * StyleSweeper — Scans for legacy CSS classes and static inline styles.
 */
class StyleSweeper
{
    /** @return AuditIssue[] */
    public function scanLegacyClasses(string $viewsPath): array
    {
        return [];
    }

    /** @return AuditIssue[] */
    public function scanStaticInlineStyles(string $viewsPath): array
    {
        return [];
    }

    /**
     * @param  string[] $classNames
     * @return JsSelectorScanResult[]
     */
    public function scanJsSelectors(string $jsPath, array $classNames): array
    {
        return [];
    }

    public function fix(array $issues, array $jsProtectedClasses = []): int
    {
        return 0;
    }
}
