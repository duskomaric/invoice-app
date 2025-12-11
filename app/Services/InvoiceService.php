<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Mail\InvoiceMail;
use App\Models\Invoice;
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

    public function sendEmail(Invoice $invoice, string $email): void
    {
        Mail::to($email)->send(new InvoiceMail($invoice));
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
