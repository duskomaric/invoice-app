<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\InvoiceEmailLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailTrackingTest extends TestCase
{
    use RefreshDatabase;

    public function test_pixel_tracks_open()
    {
        $invoice = Invoice::factory()->create();
        $log = InvoiceEmailLog::create(['invoice_id' => $invoice->id]);

        $this->assertNull($log->opened_at);

        $response = $this->get(route('email.pixel', $log->id));

        $response->assertOk();
        $this->assertNotNull($log->fresh()->opened_at);
    }

    public function test_click_tracks_click_and_downloads_pdf()
    {
        $invoice = Invoice::factory()->create();
        $log = InvoiceEmailLog::create(['invoice_id' => $invoice->id]);

        $this->assertNull($log->clicked_at);

        $response = $this->get(route('email.click', $log->id));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertNotNull($log->fresh()->clicked_at);
    }
}
