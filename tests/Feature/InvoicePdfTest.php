<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoicePdfTest extends TestCase
{
    use RefreshDatabase;

    public function test_pdf_uses_company_settings()
    {
        Setting::set('company_name', 'Test Company');
        Setting::set('company_address', '123 Test St');

        $invoice = Invoice::factory()->create();

        $html = (new \App\Services\InvoiceService)->getPdfHtml($invoice);

        $this->assertStringContainsString('Test Company', $html);
        $this->assertStringContainsString('123 Test St', $html);
    }

    public function test_pdf_uses_invoice_language()
    {
        $invoice = Invoice::factory()->create(['language' => 'sr']);

        $html = (new \App\Services\InvoiceService)->getPdfHtml($invoice);

        // Check for Serbian terms
        $this->assertStringContainsString('Faktura', $html); // Invoice
        $this->assertStringContainsString('Datum', $html); // Date
        $this->assertStringContainsString('Ukupno', $html); // Total
    }

    public function test_pdf_defaults_to_english()
    {
        $invoice = Invoice::factory()->create(['language' => 'en']);

        $html = (new \App\Services\InvoiceService)->getPdfHtml($invoice);

        // Check for English terms
        $this->assertStringContainsString('Invoice', $html);
        $this->assertStringContainsString('Date', $html);
        $this->assertStringContainsString('Total', $html);
    }
}
