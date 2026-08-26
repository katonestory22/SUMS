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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');

            // Manual, standalone bill-to details (not linked to Client/Project records)
            $table->string('bill_to_name');
            $table->text('bill_to_address')->nullable();
            $table->string('bill_to_email')->nullable();
            $table->string('bill_to_phone')->nullable();

            $table->date('invoice_date');
            $table->date('due_date')->nullable();
            $table->text('notes')->nullable();

            // Line items stored as JSON: [{description, quantity, rate, amount}, ...]
            $table->json('items');
            $table->decimal('total', 14, 2)->default(0);

            $table->string('file_path')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
