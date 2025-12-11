<?php

namespace App\Filament\Resources\Invoices\Pages;

use App\Filament\Resources\Invoices\InvoiceResource;
use App\Services\OFSService;
use App\Services\PaymentService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;

    public function mount(): void
    {
        parent::mount();

        // Pre-fill currency from URL parameter
        $currency = request()->query('currency');

        if ($currency) {
            $this->data['currency'] = $currency;
        }
    }

    protected function afterCreate(): void
    {
        $invoice = $this->record;
        $service = new PaymentService();
        $service->allocatePayments($invoice->client);
    }
}
