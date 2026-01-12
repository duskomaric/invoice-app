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
            $table->string('language'); // Default to Serbian Latin
            $table->date('date');
            $table->date('due_date')->nullable();

            // Payments
            $table->integer('amount_paid')->default(0);

            // Numbering & Currency
            $table->string('currency')->default('BAM');
            $table->string('invoice_prefix')->nullable();
            $table->string('invoice_prefix_key')->storedAs("COALESCE(invoice_prefix, '')");
            $table->unsignedSmallInteger('invoice_year');
            $table->string('invoice_number');
            $table->string('invoice_template')->nullable();

            $table->unique(['company_id', 'invoice_prefix_key', 'invoice_year', 'invoice_number'], 'invoices_company_prefixkey_year_number_unique');

            $table->text('notes')->nullable();

            // Recurring fields
            $table->boolean('is_recurring')->default(false);
            $table->string('frequency')->nullable(); // weekly, monthly, etc.
            $table->date('next_invoice_date')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('invoices')->nullOnDelete();

            // Source document (polymorphic)
            $table->nullableMorphs('sourceable');

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
