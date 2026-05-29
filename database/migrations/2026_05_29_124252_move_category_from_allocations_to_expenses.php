<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add category to expenses
        Schema::table('expenses', function (Blueprint $table) {
            $table->string('category')->nullable()->after('allocation_id');
        });

        // 2. Copy category from parent allocation into each expense
        DB::statement('
            UPDATE expenses e
            JOIN allocations a ON a.id = e.allocation_id
            SET e.category = a.category
        ');

        // 3. Drop category from allocations
        Schema::table('allocations', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('allocations', function (Blueprint $table) {
            $table->string('category')->nullable()->after('project_id');
        });

        DB::statement('
            UPDATE allocations a
            JOIN (
                SELECT allocation_id, MAX(category) as category
                FROM expenses
                GROUP BY allocation_id
            ) e ON e.allocation_id = a.id
            SET a.category = e.category
        ');

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
