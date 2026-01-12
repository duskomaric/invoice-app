<?php

namespace App\Filament\Resources\Quotes\Pages;

use App\Filament\Resources\Invoices\InvoiceResource;
use App\Filament\Resources\Proformas\ProformaResource;
use App\Filament\Resources\Quotes\QuoteResource;
use App\Services\DocumentConversionService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuote extends EditRecord
{
    protected static string $resource = QuoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('convert_to_proforma')
                ->label('Pretvori u predračun')
                ->icon('heroicon-o-arrow-right')
                ->color('warning')
                ->requiresConfirmation()
                ->action(function (DocumentConversionService $service) {
                    $proforma = $service->convertQuoteToProforma($this->record);

                    $this->redirect(ProformaResource::getUrl('edit', ['record' => $proforma]));
                }),
            Actions\Action::make('convert_to_invoice')
                ->label('Pretvori u fakturu')
                ->icon('heroicon-o-arrow-right')
                ->color('success')
                ->requiresConfirmation()
                ->action(function (DocumentConversionService $service) {
                    $invoice = $service->convertQuoteToInvoice($this->record);

                    $this->redirect(InvoiceResource::getUrl('edit', ['record' => $invoice]));
                }),
        ];
    }
}
