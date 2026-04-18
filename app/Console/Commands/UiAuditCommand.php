<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Support\UiAudit\AuditResult;

class UiAuditCommand extends Command
{
    protected $signature = 'app:ui-audit
                            {--dry-run : Chạy thử không ghi file}
                            {--fix : Tự động sửa các vấn đề tìm thấy}
                            {--module=all : Module cần chạy (ghost|responsive|contrast|performance|sweep|all)}';

    protected $description = 'Kiểm tra và sửa chữa codebase sau quá trình chuyển đổi CSS sang Tailwind';

    /** Thứ tự chạy các module */
    private const MODULE_ORDER = ['ghost', 'responsive', 'contrast', 'sweep', 'performance'];

    /** Map module name → service class */
    private const MODULE_MAP = [
        'ghost'       => \App\Support\UiAudit\GhostHunter::class,
        'responsive'  => \App\Support\UiAudit\ResponsiveGuard::class,
        'contrast'    => \App\Support\UiAudit\ContrastChecker::class,
        'performance' => \App\Support\UiAudit\PerformanceReporter::class,
        'sweep'       => \App\Support\UiAudit\StyleSweeper::class,
    ];

    public function handle(): int
    {
        $dryRun  = $this->option('dry-run');
        $fix     = $this->option('fix');
        $module  = strtolower($this->option('module') ?? 'all');

        // Validate module option
        $validModules = array_merge(['all'], array_keys(self::MODULE_MAP));
        if (! in_array($module, $validModules, true)) {
            $this->error("Module không hợp lệ: '{$module}'. Các giá trị hợp lệ: " . implode(', ', $validModules));
            return self::FAILURE;
        }

        // Determine which modules to run
        $modulesToRun = $module === 'all' ? self::MODULE_ORDER : [$module];

        $this->info('=== UI Audit ===');
        if ($dryRun) {
            $this->warn('[DRY RUN] Chế độ thử — không ghi file');
        } elseif ($fix) {
            $this->warn('[FIX] Chế độ sửa tự động — sẽ ghi file');
        } else {
            $this->line('[SCAN] Chế độ scan — chỉ báo cáo, không sửa');
        }
        $this->newLine();

        // Create backup before fixing
        $backupPath = null;
        if ($fix && ! $dryRun) {
            $backupPath = $this->createBackup();
            if ($backupPath === null) {
                $this->error('Không thể tạo backup. Hủy thao tác fix.');
                return self::FAILURE;
            }
            $this->info("Backup tạo tại: {$backupPath}");
            $this->newLine();
        }

        // Run modules and collect results
        /** @var AuditResult[] $results */
        $results = [];
        foreach ($modulesToRun as $moduleName) {
            $results[$moduleName] = $this->runModule($moduleName, $dryRun, $fix);
        }

        // Print summary report
        $this->printSummary($results);

        // Return failure if any module found issues (and we're not in fix mode)
        $hasUnfixedIssues = collect($results)->contains(
            fn(AuditResult $r) => $r->status === 'fail'
        );

        return $hasUnfixedIssues ? self::FAILURE : self::SUCCESS;
    }

    /**
     * Run a single module and return its AuditResult.
     */
    private function runModule(string $moduleName, bool $dryRun, bool $fix): AuditResult
    {
        $this->line("▶ Đang chạy module: <comment>{$moduleName}</comment>");

        $serviceClass = self::MODULE_MAP[$moduleName] ?? null;

        // If service class doesn't exist yet, return empty stub result
        if ($serviceClass === null || ! class_exists($serviceClass)) {
            $result = new AuditResult($moduleName, 'pass');
            $this->line("  <fg=yellow>⚠ Module '{$moduleName}' chưa được implement (stub)</>"); 
            $this->newLine();
            return $result;
        }

        try {
            /** @var object $service */
            $service = app($serviceClass);
            $result  = $this->dispatchModule($moduleName, $service, $dryRun, $fix);
        } catch (\Throwable $e) {
            $this->error("  Lỗi khi chạy module '{$moduleName}': " . $e->getMessage());
            $result = new AuditResult($moduleName, 'fail');
        }

        $icon = match ($result->status) {
            'pass'  => '<fg=green>✓</>',
            'fixed' => '<fg=cyan>✔</>',
            default => '<fg=red>✗</>',
        };

        $this->line("  {$icon} {$result->issueCount} vấn đề tìm thấy, {$result->fixCount} đã sửa");
        $this->newLine();

        return $result;
    }

    /**
     * Dispatch to the correct module method based on module name.
     */
    private function dispatchModule(string $moduleName, object $service, bool $dryRun, bool $fix): AuditResult
    {
        $viewsPath = resource_path('views');
        $cssPath   = public_path('css');
        $jsPath    = base_path('');

        return match ($moduleName) {
            'ghost' => $this->runGhostHunter($service, $viewsPath, $dryRun, $fix),
            'responsive' => $this->runResponsiveGuard($service, $viewsPath, $dryRun, $fix),
            'contrast' => $this->runContrastChecker($service, $viewsPath, $cssPath, $dryRun, $fix),
            'sweep' => $this->runStyleSweeper($service, $viewsPath, $jsPath, $dryRun, $fix),
            'performance' => $this->runPerformanceReporter($service, $viewsPath, $cssPath, $dryRun, $fix),
            default => new AuditResult($moduleName, 'pass'),
        };
    }

    private function runGhostHunter(object $service, string $viewsPath, bool $dryRun, bool $fix): AuditResult
    {
        $result = new AuditResult('ghost_hunter');
        $issues = $service->scan($viewsPath);
        $result->issues     = $issues;
        $result->issueCount = count($issues);

        if ($result->issueCount === 0) {
            $result->status = 'pass';
            return $result;
        }

        $result->status = 'fail';

        foreach ($issues as $issue) {
            $this->line("  <fg=red>✗</> {$issue->file}:{$issue->line}");
            $this->line("    Ghost link: {$issue->context}");
            if ($issue->suggestion) {
                $this->line("    → {$issue->suggestion}");
            }
        }

        if ($fix && ! $dryRun) {
            $fixed          = $service->fix($issues);
            $result->fixCount = $fixed;
            $result->status   = 'fixed';
        } elseif ($dryRun) {
            $this->line("  [DRY RUN] Sẽ xóa {$result->issueCount} ghost link(s)");
        }

        return $result;
    }

    private function runResponsiveGuard(object $service, string $viewsPath, bool $dryRun, bool $fix): AuditResult
    {
        $result = new AuditResult('responsive_guard');
        $issues = array_merge(
            $service->scan($viewsPath),
            $service->checkSpecificLayouts()
        );
        $result->issues     = $issues;
        $result->issueCount = count($issues);

        if ($result->issueCount === 0) {
            $result->status = 'pass';
            return $result;
        }

        $result->status = 'fail';

        foreach ($issues as $issue) {
            $this->line("  <fg=red>✗</> {$issue->file}:{$issue->line}");
            $this->line("    {$issue->context}");
        }

        if ($fix && ! $dryRun) {
            $fixed          = $service->fix($issues);
            $result->fixCount = $fixed;
            $result->status   = 'fixed';
        } elseif ($dryRun) {
            $this->line("  [DRY RUN] Sẽ sửa {$result->issueCount} vi phạm responsive");
        }

        return $result;
    }

    private function runContrastChecker(object $service, string $viewsPath, string $cssPath, bool $dryRun, bool $fix): AuditResult
    {
        $result = new AuditResult('contrast_checker');
        $issues = array_merge(
            $service->scanTextContrast($viewsPath),
            $service->scanCssDarkRules($cssPath),
            $service->scanIconColors($viewsPath),
            $service->scanLogoVisibility($viewsPath)
        );
        $result->issues     = $issues;
        $result->issueCount = count($issues);

        if ($result->issueCount === 0) {
            $result->status = 'pass';
            return $result;
        }

        $result->status = 'fail';

        foreach ($issues as $issue) {
            $this->line("  <fg=red>✗</> {$issue->file}:{$issue->line}");
            $this->line("    {$issue->context}");
        }

        if ($fix && ! $dryRun) {
            $fixed          = $service->fix($issues);
            $result->fixCount = $fixed;
            $result->status   = 'fixed';
        } elseif ($dryRun) {
            $this->line("  [DRY RUN] Sẽ sửa {$result->issueCount} vấn đề contrast/dark mode");
        }

        return $result;
    }

    private function runStyleSweeper(object $service, string $viewsPath, string $jsPath, bool $dryRun, bool $fix): AuditResult
    {
        $result = new AuditResult('style_sweeper');

        $legacyIssues = $service->scanLegacyClasses($viewsPath);
        $inlineIssues = $service->scanStaticInlineStyles($viewsPath);

        // Determine which legacy classes are JS-protected before fixing
        $legacyClassNames   = array_map(fn($i) => $i->context, $legacyIssues);
        $jsScanResults      = $service->scanJsSelectors($jsPath, $legacyClassNames);
        $jsProtectedClasses = array_map(fn($r) => $r->cssClass, $jsScanResults);

        $issues = array_merge($legacyIssues, $inlineIssues);
        $result->issues     = $issues;
        $result->issueCount = count($issues);

        if ($result->issueCount === 0) {
            $result->status = 'pass';
            return $result;
        }

        $result->status = 'fail';

        foreach ($jsScanResults as $jsScan) {
            $this->line("  <fg=yellow>⚠</> Class '{$jsScan->cssClass}' được dùng trong JS → sẽ đổi tên thành 'js-{$jsScan->cssClass}'");
        }

        foreach ($issues as $issue) {
            $this->line("  <fg=red>✗</> {$issue->file}:{$issue->line} — {$issue->context}");
        }

        if ($fix && ! $dryRun) {
            $fixed          = $service->fix($issues, $jsProtectedClasses);
            $result->fixCount = $fixed;
            $result->status   = 'fixed';
        } elseif ($dryRun) {
            $this->line("  [DRY RUN] Sẽ xử lý {$result->issueCount} vấn đề style");
        }

        return $result;
    }

    private function runPerformanceReporter(object $service, string $viewsPath, string $cssPath, bool $dryRun, bool $fix): AuditResult
    {
        $result = new AuditResult('performance_reporter');

        $unusedIssues    = $service->checkUnusedCssFiles($viewsPath, $cssPath);
        $duplicateIssues = $service->checkDuplicateRules(resource_path('css/app.css'));

        $issues = array_merge($unusedIssues, $duplicateIssues);
        $result->issues     = $issues;
        $result->issueCount = count($issues);
        $result->status     = $result->issueCount === 0 ? 'pass' : 'fail';

        // Always print the performance report
        $report = $service->generateReport();
        $this->line($report);

        return $result;
    }

    /**
     * Create a timestamped backup of views and CSS files before applying fixes.
     * Returns the backup path, or null on failure.
     */
    private function createBackup(): ?string
    {
        $timestamp  = now()->format('Y-m-d_His');
        $backupPath = storage_path("app/ui-audit-backup/{$timestamp}");

        try {
            // Backup resources/views
            $viewsSrc  = resource_path('views');
            $viewsDest = "{$backupPath}/views";
            File::copyDirectory($viewsSrc, $viewsDest);

            // Backup public/css
            $cssSrc  = public_path('css');
            $cssDest = "{$backupPath}/css";
            File::copyDirectory($cssSrc, $cssDest);

            // Backup resources/css
            $resCssSrc  = resource_path('css');
            $resCssDest = "{$backupPath}/resources_css";
            if (File::isDirectory($resCssSrc)) {
                File::copyDirectory($resCssSrc, $resCssDest);
            }

            return $backupPath;
        } catch (\Throwable $e) {
            $this->error('Lỗi tạo backup: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Print a formatted summary table of all module results.
     *
     * @param AuditResult[] $results
     */
    private function printSummary(array $results): void
    {
        $this->line('=== Tổng kết ===');
        $this->newLine();

        $totalIssues = 0;
        $totalFixes  = 0;

        foreach ($results as $moduleName => $result) {
            $icon = match ($result->status) {
                'pass'  => '<fg=green>✓ PASS </>',
                'fixed' => '<fg=cyan>✔ FIXED</>',
                default => '<fg=red>✗ FAIL </>',
            };

            $this->line(sprintf(
                '  %s  %-20s  issues: %d  fixes: %d',
                $icon,
                $moduleName,
                $result->issueCount,
                $result->fixCount
            ));

            $totalIssues += $result->issueCount;
            $totalFixes  += $result->fixCount;
        }

        $this->newLine();
        $this->line("Tổng: {$totalIssues} vấn đề, {$totalFixes} đã sửa");
        $this->newLine();
    }
}
