<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\InvoiceTemplate;
use App\Enums\InvoiceStatus;
use App\Mail\InvoiceMail;
use App\Models\CompanyBankAccount;
use App\Models\CompanySetting;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Collection;

class InvoiceService
{
    public function generatePdf(Invoice $invoice): string
    {
        $html = $this->getPdfHtml($invoice);
        return Pdf::loadHTML($html)->output();
    }

    public function getPdfViewName(Invoice $invoice): string
    {
        $template = $invoice->invoice_template
            ?: CompanySetting::get('default_invoice_template', InvoiceTemplate::Classic->value);

        return InvoiceTemplate::tryFrom($template)?->getViewName()
            ?? InvoiceTemplate::Classic->getViewName();
    }

    public function getPdfHtml(Invoice $invoice): string
    {
        app()->setLocale($invoice->language ?? 'en');

        $bankAccounts = $this->resolveBankAccounts($invoice);
        $bankAccount = $bankAccounts->first();

        return view($this->getPdfViewName($invoice), [
            'invoice' => $invoice,
            'bankAccounts' => $bankAccounts,
            'bankAccount' => $bankAccount,
        ])->render();
    }

    /**
     * @return Collection<int, CompanyBankAccount>
     */
    public function resolveBankAccounts(Invoice $invoice): Collection
    {
        if ($invoice->relationLoaded('bankAccounts')) {
            $selected = $invoice->bankAccounts;
        } else {
            $selected = $invoice->bankAccounts()->get();
        }

        if ($selected->isNotEmpty()) {
            return $selected;
        }

        $defaultId = (int) CompanySetting::get('default_company_bank_account_id', 0);

        if ($defaultId > 0) {
            $default = CompanyBankAccount::where('company_id', $invoice->company_id)
                ->where('id', $defaultId)
                ->first();

            return $default ? collect([$default]) : collect();
        }

        $default = CompanyBankAccount::where('company_id', $invoice->company_id)
            ->orderByDesc('is_default')
            ->orderBy('id')
            ->first();

        return $default ? collect([$default]) : collect();
    }

    public function getPdfFilename(Invoice $invoice): string
    {
        $format = CompanySetting::get('invoice_pdf_filename_format', 'invoice_{{ number }}.pdf');

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
        $smtpHost = CompanySetting::get('smtp_host');

        if ($smtpHost) {
            config([
                'mail.mailers.smtp.transport' => 'smtp',
                'mail.mailers.smtp.host' => $smtpHost,
                'mail.mailers.smtp.port' => CompanySetting::get('smtp_port'),
                'mail.mailers.smtp.username' => CompanySetting::get('smtp_username'),
                'mail.mailers.smtp.password' => CompanySetting::get('smtp_password'),
                'mail.mailers.smtp.encryption' => CompanySetting::get('smtp_encryption'),
                'mail.from.address' => CompanySetting::get('smtp_from_address') ?? config('mail.from.address'),
                'mail.from.name' => CompanySetting::get('smtp_from_name') ?? config('mail.from.name'),
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
