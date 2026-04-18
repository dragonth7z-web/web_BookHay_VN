<?php

namespace App\Support\UiAudit;

use Illuminate\Support\Facades\File;

/**
 * ResponsiveGuard — Scans for non-responsive patterns in Blade views.
 */
class ResponsiveGuard
{
    /** @return AuditIssue[] */
    public function scan(string $viewsPath): array
    {
        $issues = [];

        if (! File::isDirectory($viewsPath)) {
            return $issues;
        }

        $files = File::allFiles($viewsPath);

        foreach ($files as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $content = File::get($file->getPathname());
            $lines   = explode("\n", $content);

            foreach ($lines as $lineNum => $line) {
                // Detect fixed pixel widths that may break on mobile
                if (preg_match('/style\s*=\s*["\'][^"\']*width\s*:\s*\d+px/', $line)) {
                    $issues[] = new AuditIssue(
                        type: 'fixed_width',
                        file: $file->getRelativePathname(),
                        line: $lineNum + 1,
                        context: trim($line),
                        suggestion: 'Use Tailwind responsive classes instead of fixed pixel widths.',
                        autoFixable: false
                    );
                }
            }
        }

        return $issues;
    }

    /** @return AuditIssue[] */
    public function checkSpecificLayouts(): array
    {
        return [];
    }

    public function fix(array $issues): int
    {
        return 0;
    }
}
