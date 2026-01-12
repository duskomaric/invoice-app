<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('income_book_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->date('booking_date');
            $table->text('description')->nullable();

            $table->bigInteger('income_products')->default(0);
            $table->bigInteger('income_goods')->default(0);
            $table->bigInteger('income_services')->default(0);
            $table->bigInteger('income_other')->default(0);
            $table->bigInteger('income_financial')->default(0);

            $table->bigInteger('total_income')->default(0);
            $table->bigInteger('vat_amount')->default(0);
            $table->string('review_status')->default('auto');

            $table->foreignId('payment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('quote_id')->nullable()->constrained('quotes')->nullOnDelete();
            $table->foreignId('proforma_id')->nullable()->constrained('proformas')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('income_book_entries');
    }
};
