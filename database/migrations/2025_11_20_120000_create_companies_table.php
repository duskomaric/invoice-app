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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable()->unique();
            
            // Address & Contact
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->default('BiH');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            
            // Legal
            $table->string('identification_number')->nullable(); // JIB
            $table->string('vat_number')->nullable(); // PDV ID
            $table->string('bank_account')->nullable();
            
            // OFS / Fiscalization Configuration
            // These replace the global settings for multi-tenant setup
            $table->string('ofs_base_url')->default('https://pos.ofs.ba');
            $table->string('ofs_api_key')->nullable();
            $table->string('ofs_serial_number')->nullable();
            $table->string('ofs_pac')->nullable();
            
            $table->timestamps();
        });

        // Pivot table for User <-> Company (Many-to-Many)
        Schema::create('company_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            
            $table->unique(['company_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_user');
        Schema::dropIfExists('companies');
    }
};
