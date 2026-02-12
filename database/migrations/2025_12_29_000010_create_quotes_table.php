<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('draft');
            $table->string('language')->default('sr-Latn');
            $table->date('date');
            $table->date('valid_until')->nullable();
            $table->text('notes')->nullable();
            $table->string('currency')->default('BAM');
            $table->string('quote_prefix')->nullable();
            $table->integer('quote_year');
            $table->integer('quote_number');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};
