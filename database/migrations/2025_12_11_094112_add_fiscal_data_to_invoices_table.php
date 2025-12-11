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
        Schema::table('invoices', function (Blueprint $table) {
            // Boolean flag for quick filtering
            $table->boolean('is_fiscalized')->default(false)->after('notes');
            
            // Key fiscal data for display and queries
            $table->string('fiscal_invoice_number')->nullable()->after('is_fiscalized');
            $table->string('fiscal_counter')->nullable()->after('fiscal_invoice_number');
            $table->text('fiscal_verification_url')->nullable()->after('fiscal_counter');
            $table->timestamp('fiscalized_at')->nullable()->after('fiscal_verification_url');
            
            // Complete OFS API response for audit/reprint/future use
            $table->json('fiscal_meta')->nullable()->after('fiscalized_at');
            
            // Optional: Add index for faster queries
            $table->index('is_fiscalized');
            $table->index('fiscal_invoice_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex(['is_fiscalized']);
            $table->dropIndex(['fiscal_invoice_number']);
            
            $table->dropColumn([
                'is_fiscalized',
                'fiscal_invoice_number',
                'fiscal_counter',
                'fiscal_verification_url',
                'fiscalized_at',
                'fiscal_meta',
            ]);
        });
    }
};
