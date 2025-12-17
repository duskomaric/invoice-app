<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Mail\InvoiceMail;
use App\Models\Invoice;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

class InvoiceService
{
    public function generatePdf(Invoice $invoice): string
    {
        $html = $this->getPdfHtml($invoice);
        return Pdf::loadHTML($html)->output();
    }

    public function getPdfHtml(Invoice $invoice): string
    {
        app()->setLocale($invoice->language ?? 'en');
        return view('pdf.invoice', ['invoice' => $invoice])->render();
    }

    public function getPdfFilename(Invoice $invoice): string
    {
        $format = \App\Models\Setting::get('invoice_pdf_filename_format', 'invoice_{{ number }}.pdf');

        return str_replace(
            ['{{ number }}', '{{ client }}'],
            [$invoice->id, \Illuminate\Support\Str::slug($invoice->client->name)],
            $format
        );
    }

    public function sendEmail(Invoice $invoice, string $email, ?string $subject = null, ?string $body = null): void
    {
        // Backup original config
        $originalTransport = config('mail.mailers.smtp');
        $originalFrom = config('mail.from');

        try {
            $this->configureMailer($invoice->company);
            Mail::to($email)->send(new InvoiceMail($invoice, $subject, $body));
        } finally {
            // Restore original config
            config(['mail.mailers.smtp' => $originalTransport]);
            config(['mail.from' => $originalFrom]);
            Mail::purge('smtp');
        }
    }

    protected function configureMailer(\App\Models\Company $company): void
    {
        $smtpHost = Setting::get('smtp_host');

        if ($smtpHost) {
            config([
                'mail.mailers.smtp.transport' => 'smtp',
                'mail.mailers.smtp.host' => $smtpHost,
                'mail.mailers.smtp.port' => Setting::get('smtp_port'),
                'mail.mailers.smtp.username' => Setting::get('smtp_username'),
                'mail.mailers.smtp.password' => Setting::get('smtp_password'),
                'mail.mailers.smtp.encryption' => Setting::get('smtp_encryption'),
                'mail.from.address' => Setting::get('smtp_from_address') ?? config('mail.from.address'),
                'mail.from.name' => Setting::get('smtp_from_name') ?? config('mail.from.name'),
            ]);

            Mail::purge('smtp');
        }
    }

    public function updateStatus(Invoice $invoice, int $paidAmount): void
    {
        $invoice->amount_paid = $paidAmount;

        if ($paidAmount >= $invoice->total) {
            $invoice->status = InvoiceStatus::Paid;
        } elseif ($paidAmount > 0) {
            $invoice->status = InvoiceStatus::Partial;
        } else {
            // Revert to Sent or Overdue based on due date
            $invoice->status = $invoice->due_date < now() ? InvoiceStatus::Overdue : InvoiceStatus::Sent;
        }

        $invoice->save();
    }
}
