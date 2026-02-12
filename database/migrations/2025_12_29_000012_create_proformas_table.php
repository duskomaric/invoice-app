<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proformas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('draft');
            $table->string('language')->default('sr-Latn');
            $table->date('date');
            $table->date('due_date')->nullable();
            $table->text('notes')->nullable();
            $table->string('currency')->default('BAM');
            $table->string('proforma_prefix')->nullable();
            $table->integer('proforma_year');
            $table->integer('proforma_number');
            $table->nullableMorphs('sourceable');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proformas');
    }
};
