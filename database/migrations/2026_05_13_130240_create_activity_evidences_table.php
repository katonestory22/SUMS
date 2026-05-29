<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activity_evidences', function (Blueprint $table) {

            $table->id();

            $table->foreignId('activity_id')
                ->constrained()
                ->onDelete('cascade');

            $table->string('file_path');

            $table->string('caption')->nullable();

            $table->foreignId('uploaded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_evidences');
    }
};
