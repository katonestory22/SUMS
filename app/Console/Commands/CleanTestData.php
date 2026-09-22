<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CleanTestData extends Command
{
    /**
     * php artisan app:clean-test-data
     * php artisan app:clean-test-data --force   (skip confirmation, e.g. in scripts)
     */
    protected $signature = 'app:clean-test-data {--force : Skip the confirmation prompt}';

    protected $description = 'Delete orphaned files, then truncate all test/dummy data tables while preserving users and project_types';

    /**
     * Tables that will be wiped. Everything else (users, project_types,
     * migrations, cache, jobs, sessions, etc.) is left untouched.
     */
    protected array $tablesToWipe = [
        'payments',
        'invoice_items',
        'invoices',
        'activity_evidences',
        'activity_edits',
        'phase_edits',
        'project_edits',
        'expense_edits',
        'progress_files',
        'activity_progress_histories',
        'activities',
        'expenses',
        'allocations',
        'phases',
        'company_expense_edits',
        'company_expenses',
        'reports',
        'projects',
        'clients',
    ];

    /**
     * table => column holding a path on the "public" disk.
     * These are read and deleted from disk BEFORE the table is truncated.
     */
    protected array $fileColumns = [
        'expenses' => 'receipt',
        'company_expenses' => 'receipt',
        'activity_evidences' => 'file_path',
        'progress_files' => 'file_path',
        'reports' => 'file_path',
    ];

    public function handle(): int
    {
        $this->warn('This will PERMANENTLY delete all data from the following tables:');
        $this->line('  ' . implode(', ', $this->tablesToWipe));
        $this->info('It will also delete the actual uploaded files (receipts, evidence photos, generated report PDFs) from storage/app/public.');
        $this->info('Preserved (not touched): users, project_types, and all framework tables.');

        if (!$this->option('force') && !$this->confirm('Are you sure you want to continue?', false)) {
            $this->line('Cancelled. No changes made.');
            return self::SUCCESS;
        }

        $this->deleteOrphanedFiles();

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach ($this->tablesToWipe as $table) {
            if (!DB::getSchemaBuilder()->hasTable($table)) {
                $this->warn("Skipped '{$table}' — table does not exist.");
                continue;
            }

            DB::table($table)->truncate();
            $this->line("Truncated: {$table}");
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->info('Done. Database and storage are clean and ready for real data.');

        return self::SUCCESS;
    }

    /**
     * Read every file path out of the file-bearing tables and delete
     * the actual files from the public disk before the rows disappear.
     */
    protected function deleteOrphanedFiles(): void
    {
        $deleted = 0;

        foreach ($this->fileColumns as $table => $column) {
            if (!DB::getSchemaBuilder()->hasTable($table)) {
                continue;
            }

            $paths = DB::table($table)
                ->whereNotNull($column)
                ->pluck($column)
                ->filter()
                ->unique()
                ->values();

            foreach ($paths as $path) {
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                    $deleted++;
                }
            }

            if ($paths->count()) {
                $this->line("Deleted {$paths->count()} file(s) referenced by {$table}.{$column}");
            }
        }

        $this->info("Total files removed from storage: {$deleted}");
    }
}
