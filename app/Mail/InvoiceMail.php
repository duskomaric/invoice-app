<?php

namespace App\Mail;

use App\Models\CompanySetting;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
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

        $subject = CompanySetting::get($subjectKey, $defaultSubject);
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
        $body = (string) CompanySetting::get($bodyKey, '');

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
                number_format($this->invoice->total / 100, 2).' BAM',
                $this->invoice->due_date->format('M d, Y'),
                CompanySetting::get('company_name', config('app.name')),
            ],
            $content
        );
    }

    public function attachments(): array
    {
        $service = app(InvoiceService::class);
        $view = $service->getPdfViewName($this->invoice);
        $bankAccounts = $service->resolveBankAccounts($this->invoice);
        $bankAccount = $bankAccounts->first();

        $pdf = Pdf::loadView($view, [
            'invoice' => $this->invoice,
            'bankAccounts' => $bankAccounts,
            'bankAccount' => $bankAccount,
        ]);
        $filename = $service->getPdfFilename($this->invoice);

        return [
            Attachment::fromData(fn () => $pdf->output(), $filename)
                ->withMime('application/pdf'),
        ];
    }
}
