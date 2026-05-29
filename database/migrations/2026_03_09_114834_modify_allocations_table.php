<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('allocations', function (Blueprint $table) {

            $table->string('category')->after('project_id');

            $table->date('allocation_date')->after('amount');

            $table->text('notes')->nullable()->after('allocation_date');

            $table->dropColumn('date_received');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
