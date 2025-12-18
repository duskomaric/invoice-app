<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('company_bank_account_invoice')) {
            Schema::create('company_bank_account_invoice', function (Blueprint $table) {
                $table->id();
                $table->foreignId('company_bank_account_id')->constrained()->cascadeOnDelete();
                $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['company_bank_account_id', 'invoice_id'], 'cba_invoice_unique');
                $table->index(['invoice_id'], 'cba_invoice_invoice_id_index');
            });
        } else {
            try {
                Schema::table('company_bank_account_invoice', function (Blueprint $table) {
                    $table->unique(['company_bank_account_id', 'invoice_id'], 'cba_invoice_unique');
                    $table->index(['invoice_id'], 'cba_invoice_invoice_id_index');
                });
            } catch (Throwable $e) {
                // Ignore if indexes already exist.
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('company_bank_account_invoice');
    }
};
