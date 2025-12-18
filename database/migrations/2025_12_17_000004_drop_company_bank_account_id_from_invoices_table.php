<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'company_bank_account_id')) {
                $table->dropConstrainedForeignId('company_bank_account_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('company_bank_account_id')
                ->nullable()
                ->after('company_id')
                ->constrained('company_bank_accounts')
                ->nullOnDelete();
        });
    }
};
