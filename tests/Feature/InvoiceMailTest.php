<?php

namespace Tests\Feature;

use App\Mail\InvoiceMail;
use App\Models\Invoice;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceMailTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_uses_english_settings_by_default()
    {
        Setting::set('invoice_email_subject', 'English Subject');
        Setting::set('invoice_email_body', 'English Body');

        $invoice = Invoice::factory()->create(['language' => 'en']);

        $mail = new InvoiceMail($invoice);

        $this->assertEquals('English Subject', $mail->envelope()->subject);
        $this->assertStringContainsString('English Body', $mail->content()->with['body']);
    }

    public function test_email_uses_serbian_settings_when_selected()
    {
        Setting::set('invoice_email_subject_sr', 'Serbian Subject');
        Setting::set('invoice_email_body_sr', 'Serbian Body');

        $invoice = Invoice::factory()->create(['language' => 'sr']);

        $mail = new InvoiceMail($invoice);

        $this->assertEquals('Serbian Subject', $mail->envelope()->subject);
        $this->assertStringContainsString('Serbian Body', $mail->content()->with['body']);
    }
}
