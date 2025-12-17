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
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            
            // Basics
            $table->string('status')->default('draft');
            $table->string('language')->default('sr_latn'); // Default to Serbian Latin
            $table->date('date');
            $table->date('due_date')->nullable();
            
            // Payments
            $table->integer('amount_paid')->default(0);
            
            // Numbering & Currency
            $table->string('currency')->default('BAM');
            $table->string('invoice_number')->nullable();
            $table->unsignedInteger('sequence_number')->nullable();
            $table->year('sequence_year')->nullable();

            $table->unique(['company_id', 'invoice_number']);

            $table->text('notes')->nullable();
            
            // Recurring fields
            $table->boolean('is_recurring')->default(false);
            $table->string('frequency')->nullable(); // weekly, monthly, etc.
            $table->date('next_invoice_date')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('invoices')->nullOnDelete();

            // Fiscalization Data (OFS)
            $table->boolean('is_fiscalized')->default(false);
            $table->string('fiscal_invoice_number')->nullable(); // BFM-NUMBER
            $table->string('fiscal_counter')->nullable(); // e.g. 1/123/1
            $table->text('fiscal_verification_url')->nullable();
            $table->dateTime('fiscalized_at')->nullable();
            $table->json('fiscal_meta')->nullable(); // To store full response details if needed

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
