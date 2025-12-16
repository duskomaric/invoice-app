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
        // Seeding defaults for existing companies
        foreach (\App\Models\Company::cursor() as $company) {
            \App\Models\EmailTemplate::seedDefaults($company);
        }
    }

    public function down(): void
    {
        // Optional: Remove seeded templates? 
        // Best to leave them as user might have edited them.
    }
};
