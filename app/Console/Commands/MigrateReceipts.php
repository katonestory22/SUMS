<?php

namespace App\Console\Commands;


use App\Models\Expense;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MigrateReceipts extends Command
{
    protected $signature = 'migrate:receipts';
    protected $description = 'Move receipts from public/uploads/receipts to storage/app/public/receipts';

    public function handle()
    {
        $expenses = Expense::whereNotNull('receipt')->get();

        $moved = 0;
        $missing = 0;

        foreach ($expenses as $expense) {
            $oldPath = public_path($expense->receipt);
            $filename = basename($expense->receipt);
            $newPath = 'receipts/' . $filename;

            if (!file_exists($oldPath)) {
                $this->warn("Missing: {$expense->receipt}");
                $missing++;
                continue;
            }

            // Copy to storage disk
            Storage::disk('public')->put(
                $newPath,
                file_get_contents($oldPath)
            );

            // Update DB path
            $expense->update(['receipt' => $newPath]);

            // Delete old file
            unlink($oldPath);

            $this->info("Moved: {$filename}");
            $moved++;
        }

        $this->info("Done. Moved: {$moved}, Missing: {$missing}");
    }
}
