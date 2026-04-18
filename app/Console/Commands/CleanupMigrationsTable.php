<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CleanupMigrationsTable extends Command
{
    protected $signature = 'migrations:cleanup
                            {--dry-run : Show what would be done without making changes}';

    protected $description = 'Remove old Vietnamese/Rename migration records and register Clean_Migration records in the migrations table';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');

        // Collect Clean_Migration filenames (without .php extension)
        $cleanMigrations = collect(File::glob(database_path('migrations/2026_03_27_*.php')))
            ->map(fn($path) => pathinfo($path, PATHINFO_FILENAME))
            ->sort()
            ->values();

        if ($cleanMigrations->isEmpty()) {
            $this->error('No 2026_03_27_* migration files found in database/migrations/');
            return self::FAILURE;
        }

        $this->info("Found {$cleanMigrations->count()} Clean_Migration files.");

        if ($dryRun) {
            $this->warn('[DRY RUN] The following changes would be made:');
            $this->line('  DELETE FROM migrations WHERE migration LIKE "2026_02_26_%"');
            $this->line('  DELETE FROM migrations WHERE migration LIKE "2026_03_26_%"');
            $this->line("  INSERT {$cleanMigrations->count()} records for 2026_03_27_* files");
            return self::SUCCESS;
        }

        DB::transaction(function () use ($cleanMigrations) {
            // 1. Remove old Vietnamese_Migration records
            $deleted1 = DB::table('migrations')->where('migration', 'like', '2026_02_26_%')->delete();
            $this->line("  Deleted {$deleted1} records matching 2026_02_26_%");

            // 2. Remove old Rename_Migration records
            $deleted2 = DB::table('migrations')->where('migration', 'like', '2026_03_26_%')->delete();
            $this->line("  Deleted {$deleted2} records matching 2026_03_26_%");

            // 3. Determine next batch number
            $maxBatch = DB::table('migrations')->max('batch') ?? 0;
            $newBatch = $maxBatch + 1;

            // 4. Insert Clean_Migration records (skip if already registered)
            $existing = DB::table('migrations')
                ->where('migration', 'like', '2026_03_27_%')
                ->pluck('migration')
                ->flip();

            $toInsert = $cleanMigrations
                ->reject(fn($name) => $existing->has($name))
                ->map(fn($name) => ['migration' => $name, 'batch' => $newBatch])
                ->values()
                ->all();

            if (!empty($toInsert)) {
                DB::table('migrations')->insert($toInsert);
                $this->line("  Inserted " . count($toInsert) . " Clean_Migration records (batch {$newBatch})");
            } else {
                $this->line("  All Clean_Migration records already registered — nothing to insert.");
            }
        });

        $this->info('Migrations table cleanup completed successfully.');
        return self::SUCCESS;
    }
}
