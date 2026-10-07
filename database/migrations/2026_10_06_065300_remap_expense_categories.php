<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Collapses the old 6-category list down to the new 4:
     * Labour, Materials, Services, Transport
     *
     *   Labour       -> Labour        (unchanged)
     *   Equipment    -> Materials
     *   Operations   -> Materials
     *   Miscellaneous -> Materials
     *   Travel       -> Transport
     *   Consulting   -> Services
     */
    private array $map = [
        'Equipment' => 'Materials',
        'Operations' => 'Materials',
        'Miscellaneous' => 'Materials',
        'Travel' => 'Transport',
        'Consulting' => 'Services',
    ];

    public function up(): void
    {
        foreach ($this->map as $old => $new) {
            DB::table('expenses')
                ->where('category', $old)
                ->update(['category' => $new]);
        }
    }

    public function down(): void
    {
        // One-way: the old 6-category split can't be reconstructed from the
        // merged 4-category data (e.g. "Materials" could have originally been
        // Equipment, Operations, or Miscellaneous). Nothing to safely reverse.
    }
};
