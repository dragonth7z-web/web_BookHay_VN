<?php

namespace App\Support\UiAudit;

use Illuminate\Support\Facades\File;

/**
 * GhostHunter — Scans for ghost/dead links and broken references in Blade views.
 */
class GhostHunter
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
                // Detect href="#" ghost links
                if (preg_match('/href\s*=\s*["\']#["\']/', $line)) {
                    $issues[] = new AuditIssue(
                        type: 'ghost_link',
                        file: $file->getRelativePathname(),
                        line: $lineNum + 1,
                        context: trim($line),
                        suggestion: 'Replace href="#" with a real route or remove the link.',
                        autoFixable: false
                    );
                }
            }
        }

        return $issues;
    }

    public function fix(array $issues): int
    {
        // Ghost links require manual review — not auto-fixable
        return 0;
    }
}
