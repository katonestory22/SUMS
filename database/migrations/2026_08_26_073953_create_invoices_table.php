<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();

            // Manual/standalone billing details (not linked to Client/Project records)
            $table->string('bill_to_name');
            $table->text('bill_to_address')->nullable();
            $table->string('title')->nullable(); // e.g. "Architectural Drawings"

            $table->date('issue_date');
            $table->date('due_date')->nullable();
            $table->string('status')->default('draft'); // draft, sent, cancelled

            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_percentage', 5, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);

            $table->text('notes')->nullable(); // terms/notes shown on the invoice

            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
