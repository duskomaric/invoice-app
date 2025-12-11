<?php

use App\Models\Invoice;
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
            // Currency support (EUR, BAM, etc.)
            $table->string('currency', 3)->default('BAM')->after('client_id');
            
            // Invoice number (e.g., "EUR-001/2025", "BAM-052/2025")
            $table->string('invoice_number')->nullable()->unique()->after('currency');
            
            // Sequence tracking
            $table->unsignedInteger('sequence_number')->nullable()->after('invoice_number');
            $table->year('sequence_year')->nullable()->after('sequence_number');
            
            // Indexes for performance
            $table->index(['currency', 'sequence_year']);
            $table->index('invoice_number');
        });

        // Backfill existing invoices with BAM currency and numbered format
        $this->migrateExistingInvoices();
    }

    /**
     * Migrate existing invoices to new numbering system
     */
    protected function migrateExistingInvoices(): void
    {
        $invoices = Invoice::orderBy('id')->get();
        
        foreach ($invoices as $invoice) {
            $year = $invoice->created_at->year;
            
            $invoice->currency = 'BAM';
            $invoice->sequence_number = $invoice->id;
            $invoice->sequence_year = $year;
            $invoice->invoice_number = sprintf('BAM-%03d/%d', $invoice->id, $year);
            
            // Use saveQuietly to avoid triggering observers
            $invoice->saveQuietly();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex(['currency', 'sequence_year']);
            $table->dropIndex(['invoice_number']);
            
            $table->dropColumn([
                'currency',
                'invoice_number',
                'sequence_number',
                'sequence_year',
            ]);
        });
    }
};
