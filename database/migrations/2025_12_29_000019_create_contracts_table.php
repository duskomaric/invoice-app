<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('draft');
            $table->string('language')->default('sr-Latn');
            $table->date('date');
            $table->text('notes')->nullable();
            $table->string('currency')->default('BAM');

            $table->string('contract_prefix')->nullable();
            $table->integer('contract_year');
            $table->integer('contract_number');

            $table->string('file_path')->nullable();
            $table->string('file_original_name')->nullable();
            $table->string('file_mime')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
