<?php

namespace App\Mail;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Invoice $invoice,
        public ?string $customSubject = null,
        public ?string $customBody = null,
    ) {}

    public function envelope(): Envelope
    {
        if ($this->customSubject) {
            $subject = str_replace('{{ number }}', $this->invoice->id, $this->customSubject);
            return new Envelope(subject: $subject);
        }

        $locale = $this->invoice->language ?? 'en';
        app()->setLocale($locale);

        $subjectKey = $locale === 'sr' ? 'invoice_email_subject_sr' : 'invoice_email_subject';
        $defaultSubject = $locale === 'sr' ? 'Faktura #{{ number }}' : 'Invoice #{{ number }}';

        $subject = \App\Models\Setting::get($subjectKey, $defaultSubject);
        $subject = str_replace('{{ number }}', $this->invoice->id, $subject);

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        // ... (Log entry needs to be created regardless)
        $log = \App\Models\InvoiceEmailLog::create(['invoice_id' => $this->invoice->id]);
        $pixelUrl = route('email.pixel', $log->id);
        $clickUrl = route('email.click', $log->id);

        if ($this->customBody) {
             $body = $this->replacePlaceholders($this->customBody);
             return new Content(
                markdown: 'emails.invoice',
                with: [
                    'body' => $body,
                    'pixelUrl' => $pixelUrl,
                    'clickUrl' => $clickUrl,
                ],
            );
        }

        $locale = $this->invoice->language ?? 'en';
        app()->setLocale($locale);

        $bodyKey = $locale === 'sr' ? 'invoice_email_body_sr' : 'invoice_email_body';
        $body = \App\Models\Setting::get($bodyKey, '');
        
        $body = $this->replacePlaceholders($body);

        return new Content(
            markdown: 'emails.invoice',
            with: [
                'body' => $body,
                'pixelUrl' => $pixelUrl,
                'clickUrl' => $clickUrl,
            ],
        );
    }

    protected function replacePlaceholders(string $content): string
    {
        return str_replace(
            ['{{ client }}', '{{ number }}', '{{ amount }}', '{{ due_date }}', '{{ company }}'],
            [
                $this->invoice->client->name,
                $this->invoice->id,
                number_format($this->invoice->total / 100, 2) . ' BAM',
                $this->invoice->due_date->format('M d, Y'),
                \App\Models\Setting::get('company_name', config('app.name'))
            ],
            $content
        );
    }

    public function attachments(): array
    {
        $pdf = Pdf::loadView('pdf.invoice', ['invoice' => $this->invoice]);
        $filename = (new \App\Services\InvoiceService())->getPdfFilename($this->invoice);

        return [
            Attachment::fromData(fn () => $pdf->output(), $filename)
                ->withMime('application/pdf'),
        ];
    }
}
