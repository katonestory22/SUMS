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
        Schema::table('projects', function (Blueprint $table) {
            // remove the old 'type' column
            $table->dropColumn('project_type');

            // add foreign key column
            $table->foreignId('project_type_id')
                ->after('project_name') // optional, just to keep columns ordered
                ->constrained('project_types')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            Schema::table('projects', function (Blueprint $table) {
            // drop foreign key column
            $table->dropForeign(['project_type_id']);
            $table->dropColumn('project_type_id');

            // restore old 'type' column
            $table->enum('type', ['commercial', 'residential', 'infrastructure'])
                  ->after('name');
        });
        });
    }
};
